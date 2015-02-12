<?php

namespace app\commands;

use yii\console\Controller;


class  ResponseController extends Controller
{
    /**
     * @var bool set to true to display debug info
     */
    public $debug = true;

    /**
     * Index
     *
     * @param bool $loop
     * @return bool
     */
    public function actionIndex($loop = false)
    {
        if (!$loop) {
            $this->job();

            return true;
        }
        while (true) {

            $this->job();
            sleep(3);
        }
    }


    /**
     * job
     * main function
     * for each file read content, parse, call ws
     * if success delete file
     *
     */
    protected function job()
    {
        $this->debug(' ');
        $this->debug('******* START ');
        $files = $this->getListFiles();

        $i = 0;

        foreach ($files as $file) {

            if ($file != '.' && $file != '..' && !is_dir($file)) {

                /*
                if (is_dir($file)) {
                    echo "è una directory **************";
                }
                */

                $this->debug(' ');
                $parsed = $this->parseFile($file);

                if (!empty($parsed['CIDDest'])) {
                    $this->debug('Call number: ' . $parsed['CIDDest']);
                } else {
                    $this->debug('Call number: unknow');
                }

                if (!empty($parsed['task'])) {
                    $this->debug('Task id: ' . $parsed['task']);
                } else {
                    $this->debug('Task id: unknow');
                }

                if (!empty($parsed['status'])) {
                    $this->debug('Status: ' . $parsed['status']);
                } else {
                    $this->debug('Status: unknow');
                }


                $response = false;

                if (isset($parsed['token']) && isset($parsed['id']) && isset($parsed['status'])) {
                    $response = $this->send($parsed);
                    $this->debug('Server Response Code: ' . $response->status);

                    if ($response->status) {
                        $this->deleteFile($file);
                    }

                }

                $i++;
            }


        }

        $this->debug(' ');
        $this->debug('******* END ');
        $this->debug('Total files found: ' . $i);
        $this->debug(' ');
    }


    /**
     * getListFiles
     * return array list of outgoing processed call files
     * dir is set in yii2 params
     *
     * @return array
     */
    protected function getListFiles()
    {
        return scandir(\Yii::$app->params['outgoingDoneFolder']);
    }


    /**
     * parseFile
     * parse file and return array
     *
     * @param $file
     * @return array
     */
    protected function parseFile($file)
    {
        $fullPath = \Yii::$app->params['outgoingDoneFolder'] . '/' . $file;

        $response = [
            'fileName' => $file,
            'fullPath' => $fullPath,
            'status'   => 'unknow'
        ];

        /**
         * get content
         */
        $content = trim(file_get_contents($fullPath));

        /**
         * get line row array
         */
        $params = preg_split("/\\r\\n|\\r|\\n/", $content);


        foreach ($params as $param) {

            $row = @explode(':', $param, 2);

            /**
             *  get setting
             */
            if ($row[0] == 'Set') {

                $setting = @explode('=', $row[1], 2);

                //   print_r($setting);

                if ($setting[0] == 'Url') {
                    $response['url'] = trim($setting[1]);
                }

                if ($setting[0] == 'Token') {
                    $response['token'] = trim($setting[1]);
                }

                if ($setting[0] == 'Id') {
                    $response['id'] = trim($setting[1]);
                }

                if ($setting[0] == 'CIDDest') {
                    $response['CIDDest'] = trim($setting[1]);
                }

                if ($setting[0] == 'task') {
                    $response['task'] = trim($setting[1]);
                }

            }

            if ($row[0] == 'Status') {
                $response['status'] = trim($row[1]);
            }
        }

        return $response;
    }


    /**
     * debug
     * display or not debug info
     *
     * @param $message
     * @return bool
     */
    protected function debug($message)
    {
        if ($this->debug) {
            $now = date('d-m-Y H:i:s');

            if (!$message) {

                $message = 'nessun valore ricevuto';
            }

            if (is_array($message) || is_object($message)) {
                print_r($message);
            } else {
                echo $now . ' - ' . $message . PHP_EOL;
            }
        }

        return true;
    }


    /**
     * Set curl options
     *
     * @param bool $debug
     * @return array
     */
    public function curlOptions($url, $method, $posts, $debug = false)
    {
        $options = [
            CURLOPT_HTTPHEADER     => [
                //'Content-type: application/json; charset=utf-8',
                //'Content-type: text/plain; charset=utf-8',
                //	'Accept: application/json',
                'Cache-Control: no-store, no-cache, must-revalidate',
                'Cache-Control: post-check=0, pre-check=0',
                'Pragma: no-cache',
                'Expires: 0'
            ],

            CURLOPT_RETURNTRANSFER => true,         // return web page
            CURLOPT_HEADER         => false,        // don't return headers
            CURLINFO_HEADER_OUT    => $debug,
            CURLOPT_FOLLOWLOCATION => true,         // follow redirects
            CURLOPT_ENCODING       => "",           // handle all encodings
            CURLOPT_AUTOREFERER    => true,         // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
            CURLOPT_TIMEOUT        => 120,          // timeout on response
            CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
            CURLOPT_SSL_VERIFYHOST => 0,            // don't verify ssl
            CURLOPT_SSL_VERIFYPEER => false,        //
            CURLOPT_VERBOSE        => $debug,       // verbose level
        ];


        if ($method == 'POST') {
            $options[CURLOPT_POST]       = true;
            $options[CURLOPT_POSTFIELDS] = $posts;
            $options[CURLOPT_URL]        = $url;
        } else {
            $options[CURLOPT_HTTPGET] = true;
            $options[CURLOPT_URL]     = $url . '?' . http_build_query($posts);
        }

        return $options;
    }


    /**
     * Exec call
     * get options from curlOptions
     *
     * @return mixed
     */
    public function send($params)
    {
        $ch = curl_init();

        $posts = [
            'key'    => $params['token'],
            'id'     => $params['id'],
            'status' => $params['status'],
            'task'   => $params['task']
        ];

        $this->debug($posts);
        curl_setopt_array($ch, $this->curlOptions($params['url'], 'POST', $posts, true));
        $data     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->debug('Sending to server: ' . $params['url']);

        if ($httpCode != 200) {
            $this->debug('Connection: KO - http code:' . $httpCode);

            return false;
        }

        $this->debug('Connection: OK 200');

        $encoded = json_decode($data);

        if (empty($encoded->status)) {
            return false;
        }

        return $encoded;
    }


    public function deleteFile($file)
    {
        $fullPath = \Yii::$app->params['outgoingDoneFolder'] . '/' . $file;
        $this->debug('Delete file: ' . $file);
        @unlink($fullPath);
    }

}

<?php

namespace app\commands;

use yii\console\Controller;


class  ResponseController extends Controller
{

    public $debug = true;

    /**
     * index
     * start process
     *
     * @param bool $loop
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


    protected function job()
    {
        $files = $this->getListFiles();

        $i = 0;

        foreach ($files as $file) {

            if ($file != '.' && $file != '..') {

                $parsed = $this->parseFile($file);
                $this->debug($parsed);

                $i++;
            }
        }

        $this->debug('Total files found: ' . $i);
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


    protected function parseFile($file)
    {
        $fullPath = \Yii::$app->params['outgoingDoneFolder'] . '/' . $file;

        $response = [
            'fileName' => $file,
            'fullPath' => $fullPath
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
                    $response['url'] = $setting[1];
                }

                if ($setting[0] == 'Token') {
                    $response['token'] = $setting[1];
                }

                if ($setting[0] == 'Id') {
                    $response['id'] = $setting[1];
                }

            }

            if ($row[0] == 'Status') {
                $response['status'] = $row[1];
            }
        }

        return $response;
    }


    protected function debug($message)
    {
        if ($this->debug) {

            if (is_array($message)) {
                print_r($message);
            } else {
                echo $message . PHP_EOL;
            }
        }

        return true;
    }


}

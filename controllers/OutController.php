<?php
/**
 * Send Controller
 *
 * @authors Giorgio Modoni <modogio@gmail.com>
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Send;


/**
 * Class SiteController
 *
 * @package app\controllers
 */
class OutController extends Controller
{
    /**
     * @var string foxbox spooler base path
     */
    public $tmpPath = "/outgoing/";

    /**
     * @var string outgoing asterisk dir
     */
    public $spool = "/var/spool/asterisk/outgoing/";


    /**
     * Declaring behaviours
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => 'app\components\Access',
            ],
        ];
    }

    /**
     * Actions
     *
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'app\components\ErrorWs',
            ],
        ];
    }

    /**
     * Send
     * main function for sms send
     * check validation and prepare to send SMS
     *
     * @return array
     */
    public function actionSend()
    {
        $model  = new Send();
        $params = ['Send' => \Yii::$app->request->post()];

        if ($model->load($params) && $model->validate()) {

            $stream = $this->createStream($model);

            if ($this->saveToFile($stream)) {

                return [
                    'status'  => 10,
                    'message' => 'saved',
                    //  'stream'  => $stream,
                ];

            };

            return [
                'status'  => 101,
                'message' => 'record not saved, error creating or deleteing file'
            ];

        }

        return [
            'status'  => 101,
            'message' => $model->getFirstErrors(),
            // 'number'  => $model->to
        ];
    }


    protected function createStream($model)
    {
        $stream = '';
        $stream .= 'Channel:local/' . $model->to . '@' . $model->context . '/n' . PHP_EOL;
        $stream .= 'MaxRetries:' . $model->maxRetries . PHP_EOL;
        $stream .= 'RetryTime:' . $model->retryTime . PHP_EOL;
        $stream .= 'WaitTime:' . $model->waitTime . PHP_EOL;
        $stream .= 'Set:CIDDest=' . $model->to . PHP_EOL;
        $stream .= 'Set:Id=' . $model->id . PHP_EOL;
        $stream .= 'Set:Url=' . $model->url . PHP_EOL;
        $stream .= 'Set:Token=' . $model->token . PHP_EOL;
        $stream .= 'Set:testo=' . $model->message . PHP_EOL;
        $stream .= 'Context:' . $model->context . PHP_EOL;
        $stream .= 'Extension:' . $model->extension . PHP_EOL;
        $stream .= 'Priority:' . $model->priority . PHP_EOL;
        $stream .= 'Archive:' . $model->archive . PHP_EOL;

        return $stream;
    }


    /**
     * saveToFile
     * save stream to file and copy to asterisk outgoing folder
     *
     * @param $stream
     * @return bool
     */
    protected function saveToFile($stream)
    {
        $filename = uniqid(rand(), true);
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $this->tmpPath . $filename;

        $handle = @fopen($filePath, "wb");
        @fwrite($handle, $stream);
        @fclose($handle);

        if (!@copy($filePath, $this->spool . $filename)) {
            return false;
        };

        if (!@unlink($filePath)) {
            return false;
        }

        return true;
    }

}
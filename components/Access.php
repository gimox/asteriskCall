<?php
namespace app\components;

use Yii;
use yii\base\ActionFilter;

class Access extends ActionFilter
{

    public static $format = 'FORMAT_JSON';

    public function beforeAction($action)
    {
        \Yii::$app->controller->enableCsrfValidation = false;
        $this->setBasicResponse();

        return $this->filterResponse($this->auth());
    }

    /**
     * setBasicResponse function.
     *
     * @access public
     * @return void
     *
     * set the basic response header for all REST response
     */
    public function setBasicResponse()
    {
        Yii::$app->response->getHeaders()->set('Cache-Control', 'no-store, no-cache, must-revalidate');
        Yii::$app->response->getHeaders()->set('Cache-Control', 'post-check=0, pre-check=0');
        Yii::$app->response->getHeaders()->set('Pragma', 'no-cache');
        Yii::$app->response->getHeaders()->set('Expires', '0');
    }


    /**
     * Filter response
     *
     * @param bool $response
     * @return bool
     */
    public function filterResponse($response = false)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if ($response) {


            return true;
        } else {
            Yii::$app->response->setStatusCode('401');

            return false;
        }
    }

    /**
     * check valid Key
     *
     * @return bool
     */
    public function auth()
    {
        $wsKey = $this->getApikey();

        $request = Yii::$app->request;
        $key     = $request->post('key');

        if ($key && $key == $wsKey) {

            return true;
        }

        return false;
    }


    public function getApikey()
    {
        return \Yii::$app->params['apiKey'];
    }


}
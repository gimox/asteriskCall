<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

/**
 * Class SiteController
 *
 * @package app\controllers
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
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
     * Render home page
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Render ApiKey Page
     *
     * @return string
     */
    public function actionApikey()
    {
        $key = \Yii::$app->params['apiKey'];

        return $this->render('apikey', [
            'key' => $key
        ]);

    }

    /**
     * Render About Page
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}

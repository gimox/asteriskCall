<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Send extends Model
{

    /**
     * @var int default priority
     */
    public $priority;

    /**
     * @var int number of recall after failed
     */
    public $maxRetries;

    /**
     * @var int time for retry a call when not success
     */
    public $retryTime;

    /**
     * @var int ring time in seconds
     */
    public $waitTime;

    /**
     * @var string outgoing number ->CIDDest
     */
    public $to;

    /**
     * @var string url to call after response
     */
    public $url;

    /**
     * @var string web service token
     */
    public $token;

    /**
     * @var string asterisk context
     */
    public $context;

    /**
     * @var string asterisk context
     */
    public $extension;

    /**
     * @var string yes/no generate text report response
     */
    public $archive;

    /**
     * @var
     */
    public $message;


    public $id;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [

            [['to', 'message','context', 'extension'], 'required'],

            ['priority', 'integer'],
            ['priority', 'default', 'value' => 1],

            ['maxRetries', 'number'],
            ['maxRetries', 'default', 'value' => 999999],

            ['retryTime', 'number'],
            ['retryTime', 'default', 'value' => 15],

            ['waitTime', 'number'],
            ['waitTime', 'default', 'value' => 15],

            ['archive', 'in', 'range' => ['Yes', 'No']],
            ['archive', 'default', 'value' => 'Yes'],

            ['to', 'string', 'max' => 30],

            [['url', 'context'], 'string', 'max' => 160],
            ['token', 'string', 'max' => 160],
            ['message', 'string', 'max' => 250],

            ['id', 'integer'],

        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'maxRetries' => 'maxRetries',
            'retryTime'  => 'retryTime',
            'waitTime'   => 'waitTime',
            'to'         => 'to',
            'url'        => 'url',
            'token'      => 'token',
            'context'    => 'context',
            'extension'  => 'extension',
            'priority'   => 'priority',
            'archive'    => 'archive',
            'id'         => 'id,'
        ];
    }

}
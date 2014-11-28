<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title                   = 'Api Key';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
       Your Api Key is:
    </p>

    <code><?=$key?></code>
<br/>
    <br/>

    <blockquote>
        to change the key go to applicationdir/config/params.php<br/>
        and change 'apiKey'=>'yourvalues'
        </blockquote>


</div>

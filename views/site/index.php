<?php
/* @var $this yii\web\View */
$this->title = 'Asterisk Call Out API | home page';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Asterisk Call Web Service</h1>
        <p class="lead">i'm running</p>
    </div>

<?php
/*
$start = $stop = array();

$start['uniqid'] = microtime(TRUE);
for ($x = 0; $x< 1000; $x++) {
    $val = uniqid("longprefix");
}
$stop['uniqid'] = microtime(TRUE);

$start['mt_rand'] = microtime(TRUE);
for ($x=0; $x<1000; $x++) {
    $val = mt_rand(0, 1000000);
}
$stop['mt_rand'] = microtime(TRUE);

$start['sha1/mt_rand'] = microtime(TRUE);
for ($x=0; $x<1000; $x++) {
    $val = sha1(mt_rand(0, 1000000));
}
$stop['sha1/mt_rand'] = microtime(TRUE);

$start['long_prefix'] = microtime(TRUE);
for ($x=0; $x<1000; $x++) {
    $val =  uniqid("longprefix",true);
}
$stop['long_prefix'] = microtime(TRUE);


foreach ($start as $key=>$startval) {
    echo "{$key}: " . ($stop[$key] - $startval) . "<br />";
}


echo uniqid("s",true);
*/
$request   = Yii::$app->request;



/*
$testo = "9fF o o-";
if (preg_match("/^[0-9a-zA-Z ]{1,30}$/", $testo)) {
    echo "Il riconoscimento è avvenuto.";
} else {
    echo "Testo non riconosciuto.";
}
*/

?>

    <div class="body-content"></div>
</div>

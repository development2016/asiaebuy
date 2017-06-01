<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetHtml;
use yii\widgets\Menu;
use yii\bootstrap\Modal;
use app\models\LoginForm;
use yii\helpers\Url;



AppAssetHtml::register($this);





?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>

</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo">
<?php $this->beginBody() ?>


<div class="wrapper">

<div class="container-fluid">
             


<?= $content ?>

</div>

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php/*
Modal::begin([
    'header' =>'AsiaEBuy',
    'id' => 'modal',
    'size' => 'modal-lg',
    'clientOptions' => ['backdrop' => false, 'keyboard' => TRUE],
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],

]);

echo "<div id='modalContent'></div>";
Modal::end();


Modal::begin([
    'header' =>'AsiaEBuy',
    'id' => 'modalmd',
    'size' => 'modal-md',
    'clientOptions' => ['backdrop' => false, 'keyboard' => TRUE],
    'options' => [
        'tabindex' => false // important for Select2 to work properly
    ],

]);

echo "<div id='modalContentMd'></div>";
Modal::end();
*/
?>
<?php $this->endPage() ?>

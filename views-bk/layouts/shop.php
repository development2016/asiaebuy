<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAssetShop;
use yii\widgets\Menu;
use yii\bootstrap\Modal;
use yii\helpers\Url;



AppAssetShop::register($this);

$script = <<< JS
$(document).ready(function(){

    var typingTimer;                //timer identifier
    var doneTypingInterval = 0;  //time in ms, 2 second for example  100 = 0.1 sec / 1000 = 1 sec


    $('#myInput').on('keyup', function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping, doneTypingInterval);
    });


    $('#myInput').on('keydown', function () {
      clearTimeout(typingTimer);
    });


    function doneTyping () {
        var inputVal = $('.show-search-div').val();

        $.ajax({
            type: 'POST',
            url: 'search',
            data: {value: inputVal},

            success: function(data) {

                $('.show-search-info').show();
                $('.info').html(data);

            }

        })



    }



}); 
JS;
$this->registerJs($script);


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
<body class="page-container-bg-solid">
<?php $this->beginBody() ?>

        <div class="page-wrapper">
            <div class="page-wrapper-row">
                <div class="page-wrapper-top">
                    <!-- BEGIN HEADER -->
                    <div class="page-header">
                        <!-- BEGIN HEADER TOP -->
                        <div class="page-header-top">
                            <div style="background-color: #e56867; height: 30px;">

                                <div style="margin: auto; width: 60%; text-align: right;padding: 5px;">

                                    <span style="color: #fff;"> 
                                    <?php if (Yii::$app->user->isGuest) { ?>
                                        <?= Html::a('Register', ['/site/buyer'],['style' => 'text-decoration:none;color:#fff;']) ?>
                                    <?php } else { ?>
                                            <?php echo  '<b>Welcome : </b>' .Yii::$app->user->identity->username; ?>
                                    <?php } ?>

                                    </span>
                                </div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2">
                                        <div class="page-logo">
                                            <a href="<?php echo Yii::$app->request->baseUrl.'/shop' ?>">
                                                <img src="<?php echo Yii::$app->request->baseUrl;?>/metronic/assets/layouts/layout3/img/logo-red-intense.png" alt="logo" class="logo-default">
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-7 col-sm-7">
                                        <div class="top-menu">
                                 
                                            <div class="input-group">
                                                <input type="text" id="myInput" class="form-control show-search-div" placeholder="Search for products, brands, shops">
                                                <span class="input-group-btn">
                                                    <button class="btn blue-steel uppercase bold" type="button"><i class="glyphicon glyphicon-search"></i></button>
                                                </span>
                                            </div>
                                            <div style="display: none;" class="show-search-info">
                                                <div class="info">





                               


                                                </div>


                                            </div>
                                                     
                                        </div>
                                    </div>


                                    
                                    <div class="col-md-3 col-sm-3">
                                        <a href="javascript:;" class="menu-toggler"></a>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- END HEADER TOP -->
                        <!-- BEGIN HEADER MENU -->
                        <div class="page-header-menu">
                            <div class="container">


                                <div class="hor-menu  ">
                                    <ul class="nav navbar-nav">
                                        <li class="menu-dropdown classic-menu-dropdown active">
                                            <a href="javascript:;"> Menu 1
                                                <span class="arrow"></span>
                                            </a>

                                        </li>
                                        <li class="menu-dropdown mega-menu-dropdown  ">
                                            <a href="javascript:;"> Menu 2
                                                <span class="arrow"></span>
                                            </a>


                                        </li>
                                        <li class="menu-dropdown classic-menu-dropdown ">
                                            <a href="javascript:;"> Menu 3
                                                <span class="arrow"></span>
                                            </a>

                                        </li>
                                        <li class="menu-dropdown mega-menu-dropdown  mega-menu-full">
                                            <a href="javascript:;"> Menu 4
                                                <span class="arrow"></span>
                                            </a>

                                        </li>
                                        <li class="menu-dropdown classic-menu-dropdown ">
                                            <a href="javascript:;"> Menu 5
                                                <span class="arrow"></span>
                                            </a>

                                        </li>
                                        <li class="menu-dropdown classic-menu-dropdown ">
                                            <a href="javascript:;">
                                                Menu 5
                                                <span class="arrow"></span>
                                            </a>


                                        </li>
                                    </ul>
                                </div>
                                <!-- END MEGA MENU -->
                            </div>
                        </div>
                        <!-- END HEADER MENU -->
                    </div>
                    <!-- END HEADER -->
                </div>
            </div>
            <div class="page-wrapper-row full-height">
                <div class="page-wrapper-middle">
                    <!-- BEGIN CONTAINER -->
                    <div class="page-container">
                        <!-- BEGIN CONTENT -->
                        <div class="page-content-wrapper">
                            <!-- BEGIN CONTENT BODY -->
                            <!-- BEGIN PAGE HEAD-->
                            <div class="page-head">
                                <div class="container">
                                    <!-- BEGIN PAGE TITLE -->
                                    <div class="page-title">

                      
                                        <?= Breadcrumbs::widget([
                                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                        ]) ?>




                                    </div>
                                    <!-- END PAGE TITLE -->


                                </div>
                            </div>
                            <!-- END PAGE HEAD-->
                            <!-- BEGIN PAGE CONTENT BODY -->
                            <div class="page-content">
                                <div class="container">




                                    <?= $content ?>

                                </div>
                            </div>
                            <!-- END PAGE CONTENT BODY -->
                            <!-- END CONTENT BODY -->
                        </div>
                        <!-- END CONTENT -->

                    </div>
                    <!-- END CONTAINER -->
                </div>
            </div>
            <div class="page-wrapper-row">
                <div class="page-wrapper-bottom">
                    <!-- BEGIN FOOTER -->
                    <!-- BEGIN PRE-FOOTER -->
                    <div class="page-prefooter">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                                    <h2>About</h2>
                                    <p> Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam dolore. </p>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs12 footer-block">
                                    <h2>Subscribe Email</h2>
                                    <div class="subscribe-form">
                                        <form action="javascript:;">
                                            <div class="input-group">
                                                <input type="text" placeholder="mail@email.com" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn" type="submit">Submit</button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                                    <h2>Follow Us On</h2>
                                    <ul class="social-icons">
                                        <li>
                                            <a href="javascript:;" data-original-title="rss" class="rss"></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-original-title="facebook" class="facebook"></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-original-title="twitter" class="twitter"></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-original-title="googleplus" class="googleplus"></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-original-title="linkedin" class="linkedin"></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-original-title="youtube" class="youtube"></a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-original-title="vimeo" class="vimeo"></a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12 footer-block">
                                    <h2>Contacts</h2>
                                    <address class="margin-bottom-40"> Phone: 800 123 3456
                                        <br> Email:
                                        <a href="mailto:info@metronic.com">info@metronic.com</a>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END PRE-FOOTER -->
                    <!-- BEGIN INNER FOOTER -->
                    <div class="page-footer">
                        <div class="container"> 2016 &copy; AsiaEBuy

                        </div>
                    </div>
                    <div class="scroll-to-top">
                        <i class="icon-arrow-up"></i>
                    </div>
                    <!-- END INNER FOOTER -->
                    <!-- END FOOTER -->
                </div>
            </div>
        </div>


<?php $this->endBody() ?>
</body>
</html>
<?php
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





?>
<?php $this->endPage() ?>

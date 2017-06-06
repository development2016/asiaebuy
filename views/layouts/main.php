<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use app\models\User;
use app\models\Company;

$company = Company::compid();



AppAsset::register($this);

$script = <<< JS
$(document).ready(function(){


    $("#user-pulsate").pulsate({color:"#bf1c56"});
    $("#warehouse-pulsate").pulsate({color:"#bf1c56"});
    $("#company-pulsate").pulsate({color:"#bf1c56"});



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
<body class="page-header-fixed page-sidebar-closed-hide-logo">
<?php $this->beginBody() ?>


<div class="wrapper">

            <!-- BEGIN HEADER -->
            <header class="page-header">
                <nav class="navbar mega-menu" role="navigation">
                    <div class="container-fluid">
                        <div class="clearfix navbar-fixed-top">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="toggle-icon">
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </span>
                            </button>
                            <!-- End Toggle Button -->
                            <!-- BEGIN LOGO -->
                            <a id="index" class="page-logo" href="<?php echo Yii::$app->request->baseUrl; ?>">
                            
                                <img src="<?php echo Yii::$app->request->baseUrl; ?>/metronic/assets/layouts/layout5/img/logo.png" alt="Logo"> </a>
                            <!-- END LOGO -->
                            <!-- BEGIN SEARCH -->
               

                            <!-- END SEARCH -->
                            <!-- BEGIN TOPBAR ACTIONS -->
                            <div class="topbar-actions">

            
                                <!-- BEGIN USER PROFILE -->
                                <div  class="btn-group-img btn-group"  >
                                    <button type="button"  class="btn btn-sm md-skip dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                        <span>Hi, <?php echo Yii::$app->user->isGuest ? 'Guest' : Yii::$app->user->identity->username; ?></span>
                                        <img src="<?php echo Yii::$app->request->baseUrl; ?>/metronic/assets/layouts/layout5/img/avatar1.jpg" alt=""> </button>

                                    <?php
                                    echo Menu::widget([
                                        'items' => [
                                            [
                                                'label' => '<i class="icon-user"></i> My Profile', 
                                                'url' => ['site/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                            ],
                                            [
                                                'label' => '<i class="icon-users"></i> List User', 
                                                'url' => ['user/list-user','company_id'=>(string)$company->company],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a><li class="divider"> </li>',
                                                'visible' => User::checkMenu('3009'),
                                                //'options'=>['id'=>'user-pulsate'],
                                             
                                            ],
                                            [
                                                'label' => '<i class="icon-users"></i> List User', 
                                                'url' => ['user/list-user','company_id'=>(string)$company->company],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a><li class="divider"> </li>',
                                                'visible' => User::checkMenu('2009'),
                                                //'options'=>['id'=>'user-pulsate'],
                                             
                                            ],




                                            [
                                                'label' => '<i class="icon-home"></i> Manage Warehouse', 
                                                'url' => ['/company/manage-warehouse','company_id'=>(string)$company->company],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3010'),
                                                //'options'=>['id'=>'warehouse-pulsate'],
                                          
                                            ],
                                            [
                                                'label' => '<i class="icon-bag"></i> Manage Company', 
                                                'url' => ['/company/manage-company','company_id'=>(string)$company->company],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a><li class="divider"> </li>',
                                                'visible' => User::checkMenu('3011'),
                                                //'options'=>['id'=>'company-pulsate'],
                   
                                            ],
                                            [
                                                'label' => '<i class="icon-bag"></i> Manage Company', 
                                                'url' => ['/company/manage-company','company_id'=>(string)$company->company],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a><li class="divider"> </li>',
                                                'visible' => User::checkMenu('2010'),
                                                //'options'=>['id'=>'company-pulsate'],
                   
                                            ],
                                            [
                                                'label' => '<i class="icon-key"></i> Log out', 
                                                'url' => ['site/logout'],
                                                'template'=> '<a href="{url}" class="text-uppercase" data-method="POST">{label}</a>',
          
                                            ],



                                        ],
                                        'options' => [
                                            'class' => 'dropdown-menu-v2',
                                            'role' => 'menu',
                                            //'id'=>'pulsate-regular'


                                        ],
                                       //'itemOptions'=> ['class' => 'dropdown dropdown-fw dropdown-fw-disabled'],
           
                                        'encodeLabels' => false,

                                    ]);
                                    ?>


                                </div>
                                <!-- END USER PROFILE -->
     
                            </div>
                            <!-- END TOPBAR ACTIONS -->
                        </div>

                        <!-- BEGIN HEADER MENU -->
                        <div class="nav-collapse collapse navbar-collapse navbar-responsive-collapse">

                                    <?php
                                    echo Menu::widget([
                                        'items' => [
                                            [
                                                'label' => '<i class="icon-home"></i>Dashboard', 
                                                'url' => ['site/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                //'options'=>['class'=>'active open selected'],
                                            ],
                                            [
                                                'label' => '<i class="icon-eyeglasses"></i>Source', 
                                                'url' => ['source/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3001'),
                                            ],
                                            [
                                                'label' => '<i class="icon-puzzle"></i>Request', 
                                                'url' => ['request/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3002'),
                                            ],
                                            [
                                                'label' => '<i class="icon-paper-plane"></i>Order', 
                                                'url' => ['order/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3003'),
                                            ],
                                            [
                                                'label' => '<i class="icon-check"></i>Order Confirmation', 
                                                'url' => ['order-confirm/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3004'),
                                            ],
                                            [
                                                'label' => 'Receipt / Invoice', 
                                                'url' => ['receipt/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3005'),
                                            ],
                                            [
                                                'label' => '<i class="icon-wallet"></i>Payment', 
                                                'url' => ['#'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3007'),
                                            ],
                                            [
                                                'label' => '<i class="icon-puzzle"></i>Request', 
                                                'url' => ['request/request'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('3008'),
                                            ],


                                            [
                                                'label' => '<i class="icon-bag"></i>Item', 
                                                'url' => ['item/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2001'),
                                            ],
                                            [
                                                'label' => '<i class="icon-list"></i>Lead', 
                                                'url' => ['lead/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2002'),
                                            ],
                                            [
                                                'label' => '<i class="icon-notebook"></i>Quote', 
                                                'url' => ['quote/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2003'),
                                            ],
                                            [
                                                'label' => '<i class="icon-paper-plane"></i>Order', 
                                                'url' => ['order/order'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2004'),
                                            ],
                                            [
                                                'label' => '<i class="icon-check"></i>Order Confirmation', 
                                                'url' => ['order-confirm/order-confirm'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2005'),
                                            ],
                                                                                [
                                                'label' => 'Ship / Invoice', 
                                                'url' => ['ship/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2006'),
                                            ],
                                            [
                                                'label' => '<i class="icon-wallet"></i>Payment', 
                                                'url' => ['#'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('2008'),
                                            ],

                                            [
                                                'label' => '<i class="icon-puzzle"></i>Request', 
                                                'url' => ['request/index'],
                                                'template'=> '<a href="{url}" class="text-uppercase">{label}</a>',
                                                'visible' => User::checkMenu('16'),
                                            ],
      


                                        ],
                                        'options' => [
                                            'class' => 'nav navbar-nav',

                                        ],
                                        'itemOptions'=> ['class' => 'dropdown dropdown-fw dropdown-fw-disabled'],
                                        'activateParents'=>true,
                                        'encodeLabels' => false,

                                    ]);
                                    ?>



                        </div>
                        <!-- END HEADER MENU -->
                    </div>
                    <!--/container-->
                </nav>
            </header>

                <!-- END HEADER -->
                <div class="container-fluid">
                    <div class="page-content">

                        <!-- BEGIN BREADCRUMBS -->
                        <!--<div class="breadcrumbs">
                            <h1>Dashboard</h1>

                            <ol class="breadcrumb">
                                <li>
                                    <a href="#">Home</a>
                                </li>
                                <li class="active">Dashboard</li>
                            </ol>
                        </div> -->


                        <!-- END BREADCRUMBS -->
                        <!-- BEGIN PAGE BASE CONTENT -->
                        <div>
                            <?= $content ?>

                        </div>
                        <!-- END PAGE BASE CONTENT -->
                    </div>
                </div>
        <!-- BEGIN FOOTER -->
        <p class="copyright"> 2016 &copy; AsiaEBuy

        </p>
        <a href="#index" class="go2top">
            <i class="icon-arrow-up"></i>
        </a>
        <!-- END FOOTER -->
</div>

    <!-- END CONTAINER -->




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

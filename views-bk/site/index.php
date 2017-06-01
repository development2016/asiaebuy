<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\LookupTitle;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;
use app\models\Company;

//$return = Company::Company();
$company = Company::compid();
//$user = Company::User();



/* @var $this yii\web\View */
$script = <<< JS
$(document).ready(function(){

    $('.frq').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $("#div-user-pulsate").pulsate({color:"#bf1c56"});
    $("#div-warehouse-pulsate").pulsate({color:"#bf1c56"});
    $("#div-company-pulsate").pulsate({color:"#bf1c56"});



}); 
JS;
$this->registerJs($script);
$this->title = 'Dashboard';
?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
</ol>
</div>

<div class="row">

    <div  class="col-lg-12 col-xs-12 col-sm-12">


        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase"><?= Html::encode($this->title) ?></span>
                </div>

            </div>
            <div class="portlet-body">


            </div>

        </div>

    </div>

</div>
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\LookupModel;
use app\models\LookupBrand;
use app\models\LookupCountry;
use app\models\LookupState;




$country = LookupCountry::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['country']])->one();
$state = LookupState::find()->where(['id'=>$list[0]['sellers'][0]['warehouses'][0]['state']])->one();

$this->title = 'Delivery Order';

$script = <<< JS
$(document).ready(function(){


}); 
JS;
$this->registerJs($script);

$install = $shipping = $price =  $amount = $subTotal = $deductGst = $total = $grandTotal = $showPrice = $showShipping = $showInstall = $showSubtotal = 0;

$brand = LookupBrand::find()->where(['id'=>$list[0]['sellers'][0]['items'][0]['brand']])->one();
$mdl = LookupModel::find()->where(['id'=>$list[0]['sellers'][0]['items'][0]['model']])->one();
?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?> - <?= $list[0]['project_no']; ?></h1>
    <h5><?= $list[0]['title']; ?> <span class="pull-right"><b>Due Date : </b> <?= $list[0]['due_date']; ?></span></h5>

</div>

<div class="row">

    <div class="col-lg-2 col-xs-12 col-sm-12">
    </div>

    <div class="col-lg-8 col-xs-12 col-sm-12">
        <div class="page-content-col">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="invoice-content-2 bordered">
                <div class="row invoice-head">
                    <div class="col-md-3 col-xs-3">
                        <div class="invoice-logo">
                            <img src="<?php echo Yii::$app->request->baseUrl;?>/metronic/assets/pages/media/invoice/asiaebuy.png" class="img-responsive" alt="" />

                        </div>
                    </div>
                    <div class="col-md-9 col-xs-9">
         
                            
                                <h3>
                                <?= $return_asiaebuy->company_name ?>
                                </h3>
                                <h6>Co.No. <?= $return_asiaebuy->company_registeration_no ?> , GST Registeration No. : <?= $return_asiaebuy->tax_no ?></h6>
                                <h4>
                                <?= $return_asiaebuy->address ?> , <?= $return_asiaebuy->zip_code ?> , <?= $return_asiaebuy->city ?> , <?= $return_asiaebuy->states->state ?> , <?= $return_asiaebuy->countrys->country ?>
                                </h4>
                                <h5>
                                    <span class="bold">TEL : </span> <?= $return_asiaebuy->telephone_no ?>
                                    &nbsp;
                                    <span class="bold">FAX : </span> <?= $return_asiaebuy->fax_no ?>
                                </h5>
                                <h5>
                                    <span class="bold">EMAIL : </span> <?= $return_asiaebuy->email ?>
                                </h5>
                            

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-5 col-xs-5">
                    </div>
                    <div class="col-md-4 col-xs-2">
                        <h2 class="bold">
                            DELIVERY ORDER
                        </h2>
                    </div>
                     <div class="col-md-3 col-xs-5">
                     </div>
                </div>
                
                    <hr class="border-default">
                <div class="row invoice-cust-add">

                    <div class="col-md-7">
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">To : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['buyer'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Fullfilled By : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['seller'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Delivery Address : </h4></div>
                            <div class="col-md-7"> 
                                    <?php if (empty($list[0]['sellers'][0]['warehouses'])) { ?>

                                       
                                    <?php } else { ?>

                                        <h4>
                                            <?= $list[0]['sellers'][0]['warehouses'][0]['warehouse_name'] ?>
                                            <br>
                                            <?= $list[0]['sellers'][0]['warehouses'][0]['address'] ?>,<?= $state->state ?>,<?= $country->country ?>
                                            <br>
                                            <label class="bold">P.I.C : </label> <?= $list[0]['sellers'][0]['warehouses'][0]['person_in_charge'] ?>
                                            <br>
                                            <label class="bold">Contact : </label> : <?= $list[0]['sellers'][0]['warehouses'][0]['contact'] ?>
                                
                                        </h4>

                                    <?php } ?>

                            </div>
                        </div>


                    </div>

                    <div class="col-md-2">

                    </div>

                    <div class="col-md-3">
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">DO No : </h4></div>
                            <div class="col-md-7"> <h4 class="bold"><?= $list[0]['sellers'][0]['delivery_order_no'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Date : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['date_delivery_order'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Term : </h4></div>
                            <div class="col-md-7"> <h4><?= $companySeller->term ?></h4></div>
                        </div>
                    </div>


                </div>
                    
                <div class="row invoice-body">
                    <div class="col-xs-12 table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><h4><span class="bold">ITEM</span></h4></th>
                                    <th><h4><span class="bold">DETAILS</span></h4></th>
                                    <th><h4><span class="bold">QUANTITY</span></h4></th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <h4><span class="text-center"><?= $list[0]['sellers'][0]['items'][0]['item_name'] ?></span></h4>
                                    </td>
                                    <td >
                                        <h4>
                                        <span class="bold">Brand : </span> <?= $brand->brand ?>
                                        <br><br>
                                        <span class="bold">Model : </span> <?= $mdl->model ?>
                                        <br><br>
                                        <span></span> <?= $list[0]['sellers'][0]['items'][0]['specification'] ?>
                                        </h4>
                                    </td>
                                    <td>
                                        <h4>
                                            <span><?= $list[0]['sellers'][0]['quantity']; ?></span>
                                        </h4>
                                        
                                    </td>

                                </tr>
                            </tbody>


                        </table>

                    </div>
                </div>
                    <hr class="border-default">
                <div class="row invoice-cust-add">
                    <div class="col-md-12 col-xs-12">

                        <h3>
                            <span><b>Total</b> (Quantity) :</span> 
                            <span class="pull-right bold">
                                <?= $list[0]['sellers'][0]['quantity']; ?>
                            </span>
                        </h3>
                    </div>

                </div>


                <div class="row">
                    <div class="col-xs-12">


                    </div>
                </div>

                    
            </div>


        </div>
    </div>

    <div class="col-lg-2 col-xs-12 col-sm-12">
    </div>

</div>




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





$this->title = 'Purchase Requisition';

$script = <<< JS
$(document).ready(function(){

    
}); 
JS;
$this->registerJs($script);

$amount = $sumAmount = $install = $showInstall = $sumInstall = $shipping = $showShipping = $sumShipping = $price = $showPrice = $sumPrice = 0;

?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?> - <?= $list[0]['project_no']; ?></h1>
    <h5><?= $list[0]['title']; ?> <span class="pull-right"><b>Due Date : </b> <?= $list[0]['due_date']; ?></span></h5>

</div>

<div class="row">


    <div class="col-lg-12 col-xs-12 col-sm-12">
        <div class="page-content-col">


            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="invoice-content-2 bordered">
                <div class="row invoice-head">
                    <div class="col-md-3 col-xs-12">
                        <div class="invoice-logo">
                        <img src="<?php echo Yii::$app->request->baseUrl;?>/<?php echo $companyBuyer->logo; ?>" class="img-responsive" alt="" />
                        </div>
                    </div>
                    <div class="col-md-9 col-xs-12">

                                <h3>
                                <?= $companyBuyer->company_name ?>
                                </h3>
                                <h6>Co.No. <?= $companyBuyer->company_registeration_no ?> , GST Registeration No. : <?= $companyBuyer->tax_no ?></h6>
                                <h4>
                                <?= $companyBuyer->address ?> , <?= $companyBuyer->zip_code ?> , <?= $companyBuyer->city ?> , <?= $companyBuyer->states->state ?> , <?= $companyBuyer->countrys->country ?>
                                </h4>
                                <h5>
                                    <span class="bold">TEL : </span> <?= $companyBuyer->telephone_no ?>
                                    &nbsp;
                                    <span class="bold">FAX : </span> <?= $companyBuyer->fax_no ?>
                                </h5>
                                <h5>
                                    <span class="bold">EMAIL : </span> <?= $companyBuyer->email ?>
                                </h5>
                            
                            

                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-4">
                    </div>
                    <div class="col-md-5 col-xs-5">
                        <h2 class="bold">
                            PURCHASE REQUISITION
                        </h2>
                    </div>
                     <div class="col-md-3 col-xs-3">
                     </div>
                </div>



                    <hr class="border-default">
                <div class="row invoice-cust-add">

                    <div class="col-md-7">
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">To : </h4></div>
                            <div class="col-md-7"> <h4><?= $return_asiaebuy->company_name ?></h4></div>
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
                            <div class="col-md-5"> <h4 class="bold">PR No : </h4></div>
                            <div class="col-md-7"> <h4 class="bold"><?= $list[0]['sellers'][0]['purchase_requisition_no'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Date : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['date_purchase_requisition'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Term : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['term'] ?></h4></div>
                        </div>
                    </div>


                </div>
                    
                <div class="row invoice-body">
                    <div class="col-xs-12 table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><h4><span class="bold">NO</span></h4></th>
                                    <th><h4><span class="bold">ITEM</span></h4></th>
                                    <th><h4><span class="bold">DETAILS</span></h4></th>
                                    <th><h4><span class="bold">C.I.T</span></h4></th>
                                    <th><h4><span class="bold">SHIPPING CHARGE</span></h4></th>
                                    <th><h4><span class="bold">QUANTITY</span></h4></th>
                                    <th><h4><span class="bold">UNIT PRICE</span></h4></th>
                                    <th><h4><span class="bold">AMOUNT</span></h4></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $arrayItem = -1; $i=0; foreach ($list[0]['sellers'][0]['items'] as $key => $value) { $i++; $arrayItem++; ?>
                                <tr>
                                    <td>
                                        <h4><span><?= $i; ?></span></h4>
                                    </td>
                                    <td>
                                        <h4><span class="text-center"><?= $value['item_name']; ?></span></h4>


                                    </td>
                                    <td >
                                        <h4>
                                        <span class="bold">Brand : </span> <?= $value['brand'] ?>
                                        <br><br>
                                        <span class="bold">Model : </span> <?= $value['model'] ?>
                                        <br><br>
                                        <span></span> <?= $value['specification'] ?>
                                        </h4>
 
                                    </td>
                                    <td>
                                        <h4><span>

                                            <?php if (empty($value['install'])) { ?>

                                                <?= 'Empty'; ?>

                                            <?php } elseif ($value['install'] == 'No') { ?>

                                                <?= $value['install']; ?>

                                            <?php } else { ?>

                                                <?= $showInstall = number_format((float)$value['installation_price'],2,'.',','); ?>

                                                <?php 
                                                    $install = $value['installation_price']; 

                                                    $sumInstall += $install;
                                                  ?>

                                            <?php } ?>

                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>

                                            <?php if (empty($value['shipping'])) { ?>

                                                <?= 'Empty'; ?>

                                            <?php } elseif ($value['shipping'] == 'No') { ?>

                                                <?= $value['shipping']; ?>

                                            <?php } else { ?>

                                                <?= $showShipping = number_format((float)$value['shipping_price'],2,'.',','); ?>

                                                <?php 
                                                $shipping = $value['shipping_price']; 

                                                $sumShipping += $shipping;  ?>
                                                

                                            <?php } ?>
                                            




                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>
                 
                                            
                                        <?= $value['quantity']; ?>



                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>
                                    
                                            
                                            <?= $showPrice = number_format((float)$value['cost'],2,'.',','); ?>

                                                <?php 
                                                $price = $value['cost']; 

                                                $sumPrice += $price;  ?>
                                                



                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span class="bold">
                                        <?php $amount =  $value['quantity'] * $value['cost']; 

                                        echo number_format((float)$amount,2,'.',','); 
                                        $sumAmount += $amount;


                                        ?></span></h4>
                                    </td>


                                </tr>
                                <?php } ?> 
                            </tbody>


                        </table>

                    </div>
                </div>
                    <hr class="border-default">
                <div class="row invoice-cust-add">
                    <div class="col-md-12 col-xs-12">
                        <h4>
                            <span>Sub-Total : </span> 
                            <span class="pull-right">
                                <?php echo  number_format((float)$sumAmount,2,'.',','); ?>
                                    
                            </span>
                        </h4>
                        <h4>
                            <span>Shipping Charge : </span>
                            <span class="pull-right">
                                <?php echo  number_format((float)$sumShipping,2,'.',','); ?>
                            </span>
                        </h4>
                        <h4>
                            <span>Commissioning,Installation, & Training Charge : </span>
                            <span class="pull-right">
                                <?php echo  number_format((float)$sumInstall,2,'.',','); ?>
                            </span>
                        </h4>

                        <?php 
                               $total = $sumAmount + $sumShipping + $sumInstall;

                               $deductGst = $total * ($list[0]['sellers'][0]['tax'] / 100 );

                                
                        ?>
                        <h4>
                            <span><?= $list[0]['sellers'][0]['tax']; ?>% GST : </span> <span class="pull-right font-red-sunglo bold">

                                <?php  echo number_format((float)$deductGst,2,'.',','); ?>
                                
                            </span>
                        </h4>
                        <br>
                        <h3>
                            <span><b>Total</b> (<?= $list[0]['sellers'][0]['tax']; ?>% GST Included) : </span> <span class="pull-right bold">
                                <?php

                                    $grandTotal = $total + $deductGst;
                                    echo number_format((float)$grandTotal,2,'.',','); 

                                 ?>
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



</div>




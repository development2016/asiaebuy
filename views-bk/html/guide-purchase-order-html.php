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

$this->title = 'Purchase Order';



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
                            <img src="<?php echo Yii::$app->request->baseUrl;?>/company-logo/<?php echo $companyBuyer->logo; ?>" class="img-responsive" alt="" />
                        </div>
                    </div>
                    <div class="col-md-9 col-xs-9">
    
                            
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
                            PURCHASE ORDER
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
                                            <?= $list[0]['sellers'][0]['warehouses'][0]['address'] ?>,<?= $list[0]['sellers'][0]['warehouses'][0]['state'] ?>,<?= $list[0]['sellers'][0]['warehouses'][0]['country'] ?>
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
                            <div class="col-md-7"> <h4 class="bold"><?= $list[0]['sellers'][0]['purchase_order_no'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Date : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['date_purchase_order'] ?></h4></div>
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
                                    <th><h4><span class="bold">UNIT PRICE</span></h4></th>
                                
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
                                    <td style="width: 15%;">
                                        <h4>
                                            <span>
                                            <?php 

                                            if (empty($list[0]['sellers'][0]['items'][0]['discount'])) {

                                                echo $showPrice = $list[0]['sellers'][0]['items'][0]['cost'];
                                                $price;

                                            } else {

                                                $showPrice = $list[0]['sellers'][0]['items'][0]['cost'] * ($list[0]['sellers'][0]['items'][0]['discount'] / 100);  
                                                $showPrice = $list[0]['sellers'][0]['items'][0]['cost'] - $showPrice;
                                                echo number_format((float)$showPrice,2,'.',',');

                                                $price = $showPrice;
                                            }
                                             


                                            ?>
                                                
                                            </span>
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
                        <h4>
                            <span>Sub-Total : </span> 
                            <span class="pull-right">
                                <?php $subTotal = $price * $list[0]['sellers'][0]['quantity'];

                                    echo $showSubtotal =  number_format((float)$subTotal,2,'.',',');

                                 ?>
                                
                            </span>
                        </h4>
                        <h4>
                            <span>Shipping : </span>
                            <span class="pull-right">
                                <?php if (empty($list[0]['sellers'][0]['shipping'])) { ?>
                                    
                                    <?= '-'; ?>

                                <?php } elseif ($list[0]['sellers'][0]['shipping'] == 'No') { ?>

                                    <?= '-'; ?>

                                <?php } else { ?>

                                    <?= $showShipping = number_format((float)$list[0]['sellers'][0]['shipping_price'],2,'.',','); ?>


                                <?php } ?>
                            </span>
                        </h4>
                        <h4>
                            <span>Commisioning , Installation, & Training Charge : </span>
                            <span class="pull-right">
                                <?php if (empty($list[0]['sellers'][0]['install'])) { ?>
                                    
                                    <?= '-'; ?>

                                <?php } elseif ($list[0]['sellers'][0]['install'] == 'No') { ?>

                                    <?= '-'; ?>

                                <?php } else { ?>

                                    <?= $showInstall = number_format((float)$list[0]['sellers'][0]['installation_price'],2,'.',','); ?>


                                <?php } ?>
                            </span>



                        </h4>

                                <?php 
                               $total = $subTotal + $shipping + $install;

                               $deductGst = $total * ($return_asiaebuy->gst_cost / 100 );

                                
                                ?>

                       

                        <h4>
                            <span>6% GST : </span> <span class="pull-right font-red-sunglo bold">

                                <?php  echo number_format((float)$deductGst,2,'.',','); ?>
                                
                            </span>
                        </h4>
                        <br>
                        <h3>
                            <span><b>Total</b> (6% GST Included) : </span> <span class="pull-right bold">
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

    <div class="col-lg-2 col-xs-12 col-sm-12">
    </div>

</div>




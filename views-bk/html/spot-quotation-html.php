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

$this->title = 'Quotation';


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
                            <img src="<?php echo Yii::$app->request->baseUrl;?>/metronic/assets/pages/media/invoice/asiaebuy.png" class="img-responsive" alt="" />

                        </div>
                    </div>
                    <div class="col-md-9 col-xs-12">

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
                    <div class="col-md-2 col-xs-2">
                        <h2 class="bold">
                            QUOTATION
                        </h2>
                    </div>
                     <div class="col-md-5 col-xs-5">
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



                    </div>

                    <div class="col-md-2">

                    </div>

                    <div class="col-md-3">
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">PR No : </h4></div>
                            <div class="col-md-7"> <h4 class="bold"><?= $list[0]['sellers'][0]['quotation_no'] ?></h4></div>
                        </div>
                        <div class="row static-info">
                            <div class="col-md-5"> <h4 class="bold">Date : </h4></div>
                            <div class="col-md-7"> <h4><?= $list[0]['sellers'][0]['date_quotation'] ?></h4></div>
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
                                    <th><h4><span class="bold">COMMISSIONING,INSTALLATION,& TRAINING CHARGE</span></h4></th>
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

                                                <?= 'Empty' ?>

                                            <?php } elseif ($value['install'] == 'No') { ?>

                                                <?= $value['install'] ?>

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

                                                <?= 'Empty' ?>

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

                               $deductGst = $total * ($list[0]['tax_value'] / 100 );

                                
                        ?>
                        <h4>
                            <span><?= $list[0]['tax_value']; ?>% GST : </span> <span class="pull-right font-red-sunglo bold">

                                <?php  echo number_format((float)$deductGst,2,'.',','); ?>
                                
                            </span>
                        </h4>
                        <br>
                        <h3>
                            <span><b>Total</b> (<?= $list[0]['tax_value']; ?>% GST Included) : </span> <span class="pull-right bold">
                                <?php

                                    $grandTotal = $total + $deductGst;
                                    echo number_format((float)$grandTotal,2,'.',','); 

                                 ?>
                            </span>
                        </h3>


                    </div>

                </div>




                    
            </div>


        </div>
    </div>



</div>




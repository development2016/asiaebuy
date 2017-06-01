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

$script = <<< JS
$(document).ready(function(){

    $('.installation').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.shipping').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('#create').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $('.edit_model').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.edit_sale').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.quantity').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });


    $('.unit_price').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });
    

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

        <?= Html::a('Add Item',FALSE, ['value'=>Url::to([
            'lead/item',
            'seller'=>$seller,
            'project'=>(string)$project,
            'path' => 'lead'
            ]),'class' => 'btn btn-primary pull-right','id'=>'create','style'=>'margin:20px;']) ?>


            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="invoice-content-2 bordered">
                <div class="row invoice-head">
                    <div class="col-md-3 col-xs-12">
                        <div class="invoice-logo">
                            <img src="<?php echo Yii::$app->request->baseUrl;?>/metronic/assets/pages/media/invoice/asiaebuy.png" class="img-responsive" alt="" />

                        </div>
                    </div>
                    <div class="col-md-9 col-xs-12">
                            <h2 class="bold">
                                QUOTATION
                            </h2>
                            
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
                    <hr class="border-default">
                <div class="row invoice-cust-add">
                    <div class="col-md-4 col-xs-4">
                        <h4>
                            <span class="bold">Attention : </span> <span class="pull-right"><?= $list[0]['buyer'] ?></span>
                        </h4>
                        <h4>
                            <span class="bold">Fullfil By : </span> <span class="pull-right"><?= $list[0]['sellers'][0]['seller'] ?></span>
                        </h4>
                    </div>
                    <div class="col-md-4 col-xs-4">
                    </div>
                    <div class="col-md-4 col-xs-4">
                        <h4>
                            <span class="bold">Quotation No :</span><span class="pull-right bold"><?= $list[0]['sellers'][0]['quotation_no'] ?></span>
                        </h4>
                        <h4>
                            <span class="bold">Date : </span> <span class="pull-right"><?= $list[0]['sellers'][0]['date_quotation'] ?></span>
                        </h4>
                        <h4>
                            <span class="bold">Term : </span> <span class="pull-right"><?= $companySeller->term ?></span>
                        </h4>


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
                                    <th><h4><span class="bold">COMMISSIONING,INSTALLATION,& TRAINING CHARGE</span></h4></th>
                                    <th><h4><span class="bold">SHIPPING CHARGE</span></h4></th>
                                    <th><h4><span class="bold">QUANTITY</span></h4></th>
                                    <th><h4><span class="bold">UNIT PRICE</span></h4></th>
                                    <th><h4><span class="bold">AMOUNT</span></h4></th>
                                    <th><h4><span class="bold">ACTION</span></h4></th>
                                
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
                                            <br>
                                            <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                            'information/sale-item-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'spot'
                                            ]),'class'=>'edit_model']); ?>


                                    </td>
                                    <td >
                                        <h4>
                                        <span class="bold">Brand : </span> <?= $value['brand'] ?>
                                        <br><br>
                                        <span class="bold">Model : </span> <?= $value['model'] ?>
                                        <br><br>
                                        <span></span> <?= $value['specification'] ?>
                                        </h4>
                                            <br>
                                            <?= Html::a('Edit',FALSE, ['value'=>Url::to([
                                            'information/sale-detail-update',
                                            'project'=>(string)$list[0]['_id'],
                                            'seller'=>$list[0]['sellers'][0]['seller'],
                                            //'item_id'=>$value['item_id'],
                                            'arrayItem' => $arrayItem,
                                            'path' => 'spot'
                                            ]),'class'=>'edit_sale']); ?>




                                    </td>
                                    <td>
                                        <h4><span>

                                            <?php if (empty($value['install'])) { ?>

                                                <?= Html::a('Empty',FALSE, ['value'=>Url::to([
                                                'information/sale-installation-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                               // 'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ]),'class'=>'installation']); ?>

                                            <?php } elseif ($value['install'] == 'No') { ?>

                                                <?= Html::a($value['install'],FALSE, ['value'=>Url::to([
                                                'information/sale-installation-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ]),'class'=>'installation']); ?>

                                            <?php } else { ?>

                                                <?= Html::a($showInstall = number_format((float)$value['installation_price'],2,'.',','),FALSE, ['value'=>Url::to([
                                                'information/sale-installation-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ]),'class'=>'installation']); ?>

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

                                                <?= Html::a('Empty',FALSE, ['value'=>Url::to([
                                                'information/sale-shipping-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ]),'class'=>'shipping']); ?>

                                            <?php } elseif ($value['shipping'] == 'No') { ?>

                                                <?= Html::a($value['shipping'],FALSE, ['value'=>Url::to([
                                                'information/sale-shipping-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                               // 'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ]),'class'=>'shipping']); ?>

                                            <?php } else { ?>

                                                <?= Html::a($showShipping = number_format((float)$value['shipping_price'],2,'.',','),FALSE, ['value'=>Url::to([
                                                'information/sale-shipping-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ]),'class'=>'shipping']); ?>

                                                <?php 
                                                $shipping = $value['shipping_price']; 

                                                $sumShipping += $shipping;  ?>
                                                

                                            <?php } ?>
                                            




                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>
                 
                                            
                                        <?= Html::a($value['quantity'],FALSE, ['value'=>Url::to([
                                            'information/sale-quantity-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                            ]),'class'=>'quantity']); ?>



                                        </span></h4>
                                    </td>
                                    <td>
                                        <h4><span>
                                    
                                            
                                            <?= Html::a($showPrice = number_format((float)$value['cost'],2,'.',','),FALSE, ['value'=>Url::to([
                                            'information/sale-cost-update',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                            ]),'class'=>'unit_price']); ?>

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
                                    <td>
                                        <div class="margin-bottom-5">

                                                <?= Html::a('<i class="fa fa-remove"></i>', [
                                                'information/sale-remove',
                                                'project'=>(string)$list[0]['_id'],
                                                'seller'=>$list[0]['sellers'][0]['seller'],
                                                //'item_id'=>$value['item_id'],
                                                'arrayItem' => $arrayItem,
                                                'path' => 'spot'
                                                ], ['class' => 'btn red btn-outline','title'=>'Remove']) ?>

                                            </div>
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

                        <?= Html::a('Submit', [
                        'generate/generate-spot-quotation',
                        'seller'=>$seller,
                        'project'=> (string)$project
                        ], ['class' => 'btn btn-lg blue hidden-print uppercase print-btn']) ?>

                   
                    </div>
                </div>

                    
            </div>


        </div>
    </div>



</div>




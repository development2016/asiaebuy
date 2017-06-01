<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\rating\StarRating;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Item */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('.rfq').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.cart').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });



}); 
JS;
$this->registerJs($script);






?>
<div class="page-content-inner">
    <div class="mt-content-body">

      <?php if(Yii::$app->session->hasFlash('request')):?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert"></button>
               <?php echo  Yii::$app->session->getFlash('request'); ?>
          </div>
      <?php endif; ?>
    

        <div class="row">

            <div class="col-md-6 col-sm-6">
                <div class="portlet light ">


                <?php if (empty($model['images'])) { ?>
                    <img src="<?php echo Yii::$app->request->baseUrl;?>/image/image-not-found.png" class="img-responsive">
                <?php } else { ?>
                    <img src="<?php echo Yii::$app->request->baseUrl;?>/image/product/<?php echo $model['images'][0]['details']; ?>" class="img-responsive"/>
                <?php   } ?>

                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <div class="portlet light " >

                   <h1 class="uppercase margin-bottom-20">
                        <?= $model->item_name ?>
                   </h1>
                       
                   <?= Html::a('Be the first to review this product', ['#'], []) ?>

                   <div class="row">
                       <div class="col-md-6 col-sm-6">
                            <h2 class="uppercase ">
                            MYR <?= $model->cost ?>

                            </h2>
                       </div>
                       <div class="col-md-6 col-sm-6 ">
                            <h4 class="uppercase pull-right">
                            <b class="">IN STOCK</b>
                            <p></p>
                            Code No : 

                            </h4>

                       </div>

                   </div>

                   <hr class="margin-bottom-20">

                   <div class="row">
                       <div class="col-md-6 col-sm-6 ">
                            <span>
                                Brand : <?= $model->brands->brand ?>
                            </span>
                            <br>
                            <span>
                                Availability : <?= $model->stock ?>
                            </span>
                       </div>
                   </div>

                   <hr class="margin-bottom-20">

                   <div class="row" >
                       <div class="col-md-12 col-sm-12 ">
                            <span class="bold"> 
                                Specification
                            </span>
                            <p></p>
                            <span>
                                <ul>
                                    <li><?= $model->specification ?></li>
                                </ul>
                                
                            </span>
                       </div>
                   </div>
                   <div class="row" >
                       <div class="col-md-12 col-sm-12 ">
                            <span class="bold"> 
                                Shipping
                            </span>
                            <p></p>
                            <span>
                            <?php if ($checkState->locate == 'West') { ?>
                              <ul>
                              <?php  foreach ($model['shippings'] as $key => $value) { ?>
                                  <li>West To West : <?php echo $value['aTa']; ?></li>
                                  <li>West To East : <?php echo $value['aTb']; ?></li>
                              <?php } ?>
                              </ul>
                            <?php } elseif ($checkState->locate == 'East') { ?>
                              <ul>
                              <?php  foreach ($model['shippings'] as $key => $value) { ?>
                                  <li>East To East : <?php echo $value['aTa']; ?></li>
                                  <li>East To West : <?php echo $value['aTb']; ?></li>
                              <?php } ?>
                              </ul>
                            <?php } ?>

                            </span>
                       </div>
                   </div>


                   <hr class="margin-bottom-20">
                  <?php if (Yii::$app->user->isGuest) { ?>

                  <?php } else { ?>
                    <span class="margin-bottom-40">

                        <?= Html::a('GEN. QUOTE',FALSE, ['value'=>Url::to(['project/cart',
                          'item_id'=>(string)$model->_id,
                          'seller'=>$model->owner_item,
                          'path' => 'product-details',
                          'item_name' => $model->item_name
                          ]),'class' => 'btn btn-lg btn-primary cart','id'=>'cart']) ?>
            

                        <?= Html::a('REQUEST',FALSE, ['value'=>Url::to(['project/add',
                          'item_id'=>(string)$model->_id,
                          'seller'=>$model->owner_item,
                          'path' => 'product-details'
                          ]),'class' => 'btn btn-lg btn-outline grey-mint rfq pull-right','id'=>'rfq']) ?>

                  <?php } ?>
                    </span>
                   
                    <p class="margin-bottom-20"></p>

                   <div class="row">
                       <div class="col-md-12 col-sm-12 ">
                            <span>
                                <a href="#" class="ahref-for-shop" >
                                    <i class="icon-heart" title="Add to Wishlist"></i> Add to Wishlist
                                </a>
                            </span>
                            <span style="margin-left: 100px;">
                                <a href="#" class="ahref-for-shop pull-right">
                                    <i class="icon-bar-chart" title="Add to Compare"></i> Add to Compare
                                </a>
                            </span>

                       </div>


                    </div>

                </div>




            </div>


        </div>


        <div class="row">

            <div class="col-md-12 col-sm-12">
             

                                                         <div class="tabbable-custom ">
                                                            <ul class="nav nav-tabs ">
                                                                <li class="active">
                                                                    <a href="#tab_5_1" data-toggle="tab"> Description </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#tab_5_2" data-toggle="tab"> Review </a>
                                                                </li>
                  
                                                            </ul>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="tab_5_1" style="padding: 20px">
                                                                    <?= $model->specification ?>
                                                                    
                                                                </div>
                                                                <div class="tab-pane" id="tab_5_2" style="padding: 20px;">


                                                                <h3>You`re reviewing</h3>
                                                                <h3 class="bold"><?= $model->item_name ?></h3>

                                                                <p class="margin-bottom-20"></p>

                                                                <?php $form = ActiveForm::begin(); ?>

                                                                <span>Your Rating <i class="font-red-soft">*</i></span>

                                                                <p class="margin-bottom-20"></p>

                                                                <?php
                                                                    echo $form->field($model, 'reviews[0][rating]')->widget(StarRating::classname(), [
                                                                        'pluginOptions' => [
                                                                        ]
                                                                    ])->label('Rating');
                                                                ?>

     

                                                                <div class="row">
                                                                    <div class="col-md-6 col-sm-6">

                                                                        <?= $form->field($model2, 'reviews[0][name]')->textInput(['maxlength' => true])->label('Name <i class="font-red-soft">*</i>') ?>

                                                                        <?= $form->field($model2, 'reviews[0][summary]')->textInput(['maxlength' => true])->label('Summary <i class="font-red-soft">*</i>') ?>

                                                                        <?= $form->field($model2, 'reviews[0][review]')->textarea(['rows' => 6])->label('Review <i class="font-red-soft">*</i>') ?>


                                                                        <?= Html::submitButton($model2->isNewRecord ? 'SUBMIT' : 'SUBMIT', ['class' => $model->isNewRecord ? 'btn btn-grey ' : 'btn btn-grey']) ?>
                           
                                                                    </div>

                                                                </div>





                                                                <?php ActiveForm::end(); ?>






          
                                                                </div>

                                                            </div>
                                                        </div>

            </div>

        </div>




    </div>

</div>

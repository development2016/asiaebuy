
<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shop';
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
?>                                    <!-- BEGIN PAGE CONTENT INNER -->
<div class="page-content-inner">
    <div class="mt-content-body">

      <?php if(Yii::$app->session->hasFlash('request')):?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert"></button>
               <?php echo  Yii::$app->session->getFlash('request'); ?>
          </div>
      <?php endif; ?>

      <?php if(Yii::$app->session->hasFlash('cart')):?>
          <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert"></button>
               <?php echo  Yii::$app->session->getFlash('cart'); ?>
          </div>
      <?php endif; ?>



    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="portlet light ">

                <img src="<?php echo Yii::$app->request->baseUrl;?>/shop-image/1.jpg" class="img-responsive">

            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-4 col-sm-4">
            <div class="portlet light " style="height: 474px;">
                ADVERTISEMENT 1
            </div>
        </div>
            <div class="col-md-8 col-sm-8">
                <div class="portlet light " style="height: 234px;">
                     ADVERTISEMENT 2
                </div>
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="portlet light " style="height: 215px;">
                             ADVERTISEMENT 3
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="portlet light " style="height: 215px;">
                             ADVERTISEMENT 5
                        </div>
                    </div>

                </div>


            </div>
            <div class="col-md-8 col-sm-8">
                <div class="portlet light " style="height: 234px;">
                     ADVERTISEMENT 4
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="portlet light " style="height: 234px;">
                     ADVERTISEMENT 6
                </div>
            </div>


    </div>

    <h3>New Arrival !</h3>
<div class="row">
    <?php foreach ($model as $key => $value) { ?>
    <div class="col-lg-3 col-md-6">

        <div class="portlet light">
            <span class="pull-right">
                <a href="#" class="ahref-for-shop-index"><i class="icon-heart" title="Add to Wishlist"></i></a>
                &nbsp;
                <a href="#" class="ahref-for-shop-index"><i class="icon-bar-chart" title="Add to Compare"></i></a>
            </span>

            <div class="card-icon">
                <?php if (empty($value['images'])) { ?>
                    <img src="<?php echo Yii::$app->request->baseUrl;?>/image/image-not-found.png"  class="img-responsive"/>
                <?php } else { ?>
                    <img src="<?php echo Yii::$app->request->baseUrl;?>/image/product/<?php echo $value['images'][0]['thumbnail']; ?>" />
                <?php   } ?>
                
            </div>
            <br>
            <div class="card-title">
                <span>
                    <?= Html::a($value['item_name'], ['/shop/view', 'id' => (string)$value['_id']], ['style' => 'text-decoration:none;color:#3d3f42;']) ?>
                </span>
            </div>
            <div class="card-desc">

              <?php if (empty($value['discount'])) { ?>
                <span><b> MYR <?= $value['cost']; ?></b><span>
              <?php } else { ?>
              <span><b> MYR <?php $dis =  $value['cost'] * ($value['discount'] / 100); echo $value['cost'] - $dis; ?></b><span>
              <br>
              <strike style='color:red'>
                <span><b> Before : MYR <?= $value['cost']; ?></b><span>
              </strike>

              <?php } ?>        


            </div>
            <?php if (Yii::$app->user->isGuest) { ?>

            <?php } else { ?>


                <?= Html::a('GEN. QUOTE',FALSE, ['value'=>Url::to(['project/cart',
                          'item_id'=>(string)$value['_id'],
                          'seller'=>$value['owner_item'],
                          'path' => 'frontend',
                          'item_name' => $value['item_name']
                          ]),'class' => 'btn btn-primary cart','id'=>'cart']) ?>
            

                <?= Html::a('REQUEST',FALSE, ['value'=>Url::to(['project/add',
                          'item_id'=>(string)$value['_id'],
                          'seller'=>$value['owner_item'],
                          'path' => 'frontend'
                          ]),'class' => 'btn btn-default rfq pull-right','id'=>'rfq']) ?>
            <?php } ?>




        </div>

    </div>
    <?php } ?>
</div>






    <h3>Hot Item !</h3>
<div class="row">
    
    <div class="col-lg-12 col-md-12">

        <div class="portlet light" style="height: 234px;">



        </div>

    </div>

</div>




    <h3>By Brand !</h3>
<div class="row">

    <div class="col-lg-12 col-md-12">

        <div class="portlet light" style="height: 234px;">



        </div>

    </div>

</div>






    </div>
</div>
    <!-- END PAGE CONTENT INNER -->

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$script = <<< JS
$(document).ready(function(){


    $('.uploads').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });


}); 
JS;
$this->registerJs($script);
$this->title = 'Uploads';
?>





<div class="row">

    <div  class="col-lg-12 col-xs-12 col-sm-12">


    <div class="portlet light bordered">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-bubbles font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase"><?= Html::encode($this->title) ?></span>

            </div>
            <div class="actions">
                <div class="btn-group">
        <?= Html::a('Upload Image <i class="fa fa-upload"></i>',FALSE, ['value'=>Url::to(['item/upload','_id'=>$_id,'company'=>$company]),'class' => 'btn blue-steel btn-outline btn-sm uploads','id'=>'','title'=>'Upload Image']) ?>

                </div>
            </div>
            <div class="portlet-body">


							<div class="row">
								<div class="col-lg-12 col-xs-12 col-sm-12">
									<img src="<?php echo Yii::$app->request->baseUrl;?>/offline/<?php echo $company;?>/<?= $model2->thumbs; ?>" class="img-responsive">
									<span>Thumbnails</span>
								</div>
								
							</div>
							<br>

							<div class="row">
								<div class="col-lg-12 col-xs-12 col-sm-12">
									<img src="<?php echo Yii::$app->request->baseUrl;?>/offline/<?php echo $company;?>/<?= $model2->back; ?>" class="img-responsive">
									<span>Back Store Image</span>
								</div>
								

							</div>
							<br>
							<div class="row">
								<div class="col-lg-12 col-xs-12 col-sm-12">
									<img src="<?php echo Yii::$app->request->baseUrl;?>/offline/<?php echo $company;?>/<?= $model2->original; ?>" class="img-responsive">
									<span>Original Image</span>
								</div>


							</div>
							<?= Html::a('Back To List Item', ['index'], ['class' => 'btn btn-primary pull-right']) ?>

            </div>
        </div>
    </div>



    </div>
</div>



<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\LookupCountry;
use app\models\LookupState;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use app\models\LookupTerm;

$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');
$term = ArrayHelper::map(LookupTerm::find()->asArray()->all(), 'term', 'term');

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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


$this->title = 'Direct Purchase';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
    <li class="active"><?= Html::encode($this->title) ?></li>
</ol>
</div>


<div class="row">
	    <div class="col-lg-6">

	    <div class="portlet light bordered">
	        <div class="portlet-title tabbable-line">
	            <div class="caption">
	                <i class="icon-bubbles font-dark hide"></i>
	                <span class="caption-subject font-dark bold uppercase">UPLOAD</span>
	                <span class="caption-helper">Maximum File Size 5mb PDF JPEG/TIFF/PNG/JPG/BMP/...</span>
	            </div>

            <div class="actions">
                <div class="btn-group btn-group-devided" >


                    <?= Html::a('Upload Image <i class="fa fa-upload"></i>',FALSE, ['value'=>Url::to(['offline/upload']),'class' => 'btn blue-steel btn-outline btn-sm uploads','id'=>'','title'=>'Upload Image']) ?>
                	<?= Html::a('Remove <i class="fa fa-trash"></i>', ['project/spot'],['class'=>'btn red-sunglo btn-outline btn-sm','title'=>'Remove Image']) ?>

                </div>
            </div>



	        </div>
	        <div class="portlet-body">

	        <?php if (empty($model2->filename)) { ?>
	        
	        <?php } else { ?>

	        	<img src="<?php echo Yii::$app->request->baseUrl;?>/offline/<?php echo $model2->filename ?>" class="img-responsive" alt="" />

	        <?php } ?>
	   
	        </div>
	    </div>
	    </div>

	    <div class="col-lg-6">

	    <div class="portlet light bordered">
	        <div class="portlet-title tabbable-line">
	            <div class="caption">
	                <i class="icon-bubbles font-dark hide"></i>
	                <span class="caption-subject font-dark bold uppercase">SUPPLIER</span>
	            </div>

	        </div>
	        <div class="portlet-body">


                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_1_1" data-toggle="tab"> Project </a>
                    </li>
                    <li>
                        <a href="#tab_1_2" data-toggle="tab"> Supplier </a>
                    </li>
        
                </ul>
                <?php $form = ActiveForm::begin(); ?>
                <div class="tab-content">
                	
                    <div class="tab-pane fade active in" id="tab_1_1">

					    <?= $form->field($model3, 'title')->textInput(['id'=>'id-title','placeholder'=>'-New Title-']) ?>

					    <?= $form->field($model3, 'description')->textarea(['rows' => 6]) ?>

					    <?= $form->field($model3, 'due_date')->widget(
					        DatePicker::className(), [
					            'clientOptions' => [
					                'autoclose' => true,
					                'format' => 'yyyy-mm-dd'
					            ]
					    ]);?>

				        <?php if (empty($model2->filename)) { ?>
				        
				        <?php } else { ?>

				        	<?= $form->field($model3, 'sellers[quotation]')->hiddenInput(['value'=>$model2->filename])->label(false) ?>
				        <?php } ?>



					    

                    </div>

                    <div class="tab-pane fade" id="tab_1_2">


						    <?= $form->field($model, 'company_name') ?>

						    <?= $form->field($model, 'company_registeration_no') ?>

							<?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

						    <?= $form->field($model, 'zip_code') ?>

						    <?= $form->field($model, 'country')->dropDownList(
						        $country, 
						    [
						        'prompt' => '-Select Country-',
						        'class' => 'form-control',
						        'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/offline/state','id'=>'']).'"+$(this).val(), function(data){$("select#state-id").html(data);})',

						    ]) ?>

						    <?= $form->field($model, 'state')->dropDownList(
						        $state, 
						    [
						        'prompt' => '-Select State-',
						        'class' => 'form-control',
						        'id'=> 'state-id',

						    ]) ?>

						    <?= $form->field($model, 'city') ?>

						    <?= $form->field($model, 'telephone_no') ?>

						    <?= $form->field($model, 'fax_no') ?>

						    <?= $form->field($model, 'email') ?>

						    <?= $form->field($model, 'website') ?>

						    <?= $form->field($model, 'gst') ?>

	                        <?= $form->field($model, 'term')->dropDownList(
	                            $term, 
	                        [
	                            'prompt' => '-Select Term-',
	                            'class' => 'form-control',

	                        ]) ?>


						    <?= Html::submitButton($model->isNewRecord ? '<span class="ladda-label">Submit</span>' : '<span class="ladda-label">Submit</span>', [
						    'class' => $model->isNewRecord ? 'btn btn-primary mt-ladda-btn ladda-button' : 'btn btn-primary mt-ladda-btn ladda-button',
						    'data-style' => 'slide-up'
						    ]) ?>



                    </div>
                    




					    
					        
					    
                    

                </div>
                <?php ActiveForm::end(); ?>
    	





    
	        
	            

	        </div>
	    </div>

	    </div>


    </div>
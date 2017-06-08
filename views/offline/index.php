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
        $('#modalmd').modal('show')
        .find('#modalContentMd')
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


                    <?= Html::a('Upload File <i class="fa fa-upload"></i>',FALSE, ['value'=>Url::to(['offline/upload']),'class' => 'btn blue-steel btn-outline btn-sm uploads','id'=>'','title'=>'Upload Image']) ?>

                </div>
            </div>



	        </div>
	        <div class="portlet-body">

	        <table class="table">
	        <tr>
	        	<th>Filename</th>
	        	<th>Path</th>
	        	<th>Date Create</th>
	        	<th>Action</th>
	        </tr>
	       	<?php foreach ($model2 as $key => $value) { ?>
	       	<tr>
	       		<td><?= $value['filename']; ?></td>
	       		<td><?= $value['path']; ?></td>
	       		<td><?= $value['date_create']; ?></td>
	       		<td>
	       			<div class="margin-bottom-5">

	       			<?= Html::a('View', ['view', 
	       				'id' => $value->id,
	       				'filename' => $value->filename,
	       				], ['class' => 'btn blue btn-sm btn-outline','title'=>'View Upload File']) ?>

	       				
	       			</div>
	       			<div class="margin-bottom-5">
	       			<?= Html::a('Delete', ['delete', 
	       				'id' => $value->id,
	       				'filename' => $value->filename,
	       				'path'=>$value->path,
	       				'company_id' => $value->company_id
	       				], ['class' => 'btn blue btn-sm btn-outline','title'=>'Remove Upload File']) ?>
	       			</div>
	       		</td>
	       	</tr>

	        <?php } ?>
	        </table>




	   
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





				       	<?php foreach ($model2 as $key => $value) { ?>

				       	<?= $form->field($model3, 'document[filename][]')->hiddenInput(['value'=>$value['filename']])->label(false) ?>
				       	<?= $form->field($model3, 'document[path][]')->hiddenInput(['value'=>$value['path']])->label(false) ?>
				       	<?= $form->field($model3, 'document[company_id][]')->hiddenInput(['value'=>$value['company_id']])->label(false) ?>
				       	<?= $form->field($model3, 'document[date_create][]')->hiddenInput(['value'=>$value['date_create']])->label(false) ?>
		

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

						    <?= $form->field($model, 'gst')->textInput(['value'=>'6']) ?>

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
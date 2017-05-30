<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupLeadTime;
use app\models\LookupValidity;

$lead = ArrayHelper::map(LookupLeadTime::find()->asArray()->all(),'id','lead_time'); 
$validity = ArrayHelper::map(LookupValidity::find()->asArray()->all(),'id','validity');

$yesorno = ['Yes'=>'Yes','No'=>'No'];
/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = 'Add Item';

$script = <<< JS
$(document).ready(function(){

    $('#install').on('change', function() {

        var value = $(this).val();
        if(value == 'Yes') {

        	$(".install-div").show();
        	$("#installation_price").prop('disabled', false);

        }

        if(value == 'No') {

        	$(".install-div").hide();
        	$("#installation_price").prop("disabled", true);

        } 

    });

    $('#shipping').on('change', function() {

        var value = $(this).val();
        if(value == 'Yes') {

        	$(".shipping-div").show();
        	$("#shipping_price").prop('disabled', false);

        }

        if(value == 'No') {

        	$(".shipping-div").hide();
        	$("#shipping_price").prop("disabled", true);

        } 

    });



}); 
JS;
$this->registerJs($script);


?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row">

	<?php $form = ActiveForm::begin(); ?>


	<div class="col-lg-6 col-xs-12 col-sm-12">

		    <?= $form->field($model, 'sellers[items][item_name]')->label('Item Name') ?>

		    <?= $form->field($model, 'sellers[items][brand]')->label('Brand') ?>

		    <?= $form->field($model, 'sellers[items][model]')->label('Model') ?>

		    <?= $form->field($model, 'sellers[items][description]')->textarea(['rows' => 6])->label('Description') ?>

		    <?= $form->field($model, 'sellers[items][specification]')->textarea(['rows' => 6])->label('Specification') ?>

    </div>

	<div class="col-lg-6 col-xs-12 col-sm-12">


		    <?= $form->field($model, 'sellers[items][lead_time]')->dropDownList($lead, 
		        [
		            'prompt' => '-Please Choose-',
		            'id' => 'lead-time',

		        ])->label('Lead Time') ?>

		    <?= $form->field($model, 'sellers[items][validity]')->dropDownList($validity, 
		        [
		            'prompt' => '-Please Choose-',
		            'id' => 'validity',

		        ])->label('Validity') ?>

		    <?= $form->field($model, 'sellers[items][cost]')->label('Cost') ?>

		    <?= $form->field($model, 'sellers[items][quantity]')->label('Quantity') ?>

		    <?= $form->field($model, 'sellers[items][install]')->dropDownList($yesorno, 
		        [
		            'prompt' => '-Please Choose-',
		            'id' => 'install',

		        ])->label('Commisioning,Installation & Training Charge') ?>


		     <div class="install-div" style="display: none;">

		     	<?= $form->field($model, 'sellers[items][installation_price]')->textInput(['maxlength' => true,'id'=>'installation_price'])->label('Installation Price') ?>
		     	
		     </div>


		    <?= $form->field($model, 'sellers[items][shipping]')->dropDownList($yesorno, 
		        [
		            'prompt' => '-Please Choose-',
		            'id' => 'shipping',

		        ])->label('Shipping Charge') ?>

		     <div class="shipping-div" style="display: none;">

		     	<?= $form->field($model, 'sellers[items][shipping_price]')->textInput(['maxlength' => true,'id'=>'shipping_price'])->label('Shipping Price') ?>
		     	
		     </div>

			<div class="form-group">
		        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Create', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		    </div>



    </div>


    <?php ActiveForm::end(); ?>

 </div>



		    






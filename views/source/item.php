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


    $('.itemoff').on('click', function () {
        var itemname = $(this).data('itemname');
        var brand = $(this).data('brand');
        var model = $(this).data('model');
        var description = $(this).data('description');
        var specification = $(this).data('specification');
        var leadtime = $(this).data('leadtime');
        var validity = $(this).data('validity');
        var cost = $(this).data('cost');
        var quantity = $(this).data('quantity');

		$('#item_name').val(itemname);
		$('#brand').val(brand);
		$('#models').val(model);
		$('#description').val(description);
		$('#specification').val(specification);
		$('#lead-time').val(leadtime);
		$('#validity').val(validity);
		$('#cost').val(cost);
		$('#quantity').val(quantity);

    });




}); 
JS;
$this->registerJs($script);


?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="row">

	<?php $form = ActiveForm::begin(); ?>


	<div class="col-lg-4 col-xs-12 col-sm-12">

		    <?= $form->field($model, 'sellers[items][item_name]')->textInput(['id'=>'item_name'])->label('Item Name') ?>

		    <?= $form->field($model, 'sellers[items][brand]')->textInput(['id'=>'brand'])->label('Brand') ?>

		    <?= $form->field($model, 'sellers[items][model]')->textInput(['id'=>'models'])->label('Model') ?>

		    <?= $form->field($model, 'sellers[items][description]')->textarea(['rows' => 6,'id'=>'description'])->label('Description') ?>

		    <?= $form->field($model, 'sellers[items][specification]')->textarea(['rows' => 6,'id'=>'specification'])->label('Specification') ?>

    </div>

	<div class="col-lg-4 col-xs-12 col-sm-12">


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

		    <?= $form->field($model, 'sellers[items][cost]')->textInput(['id'=>'cost'])->label('Cost') ?>

		    <?= $form->field($model, 'sellers[items][quantity]')->textInput(['id'=>'quantity'])->label('Quantity') ?>

		    <?= $form->field($model, 'sellers[items][install]')->dropDownList($yesorno, 
		        [
		            'prompt' => '-Please Choose-',
		            'id' => 'install',

		        ])->label('C.I.T') ?>


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

				<?= Html::submitButton($model->isNewRecord ? '<span class="ladda-label">Save</span>' : '<span class="ladda-label">Save</span>', [
			    'class' => $model->isNewRecord ? 'btn btn-primary mt-ladda-btn ladda-button' : 'btn btn-primary mt-ladda-btn ladda-button',
			    'data-style' => 'slide-up'
			    ]) ?>

		    </div>

    </div>

	<div class="col-lg-4 col-xs-12 col-sm-12 " style="height: 560px; overflow-y: auto; overflow-x: auto;   border-left: solid #b7b7b7 1px;">

	<h4>List All Item</h4>

	<ul style="list-style-type: none; ">
	<?php foreach ($data as $key => $value) { ?>
		<li style="cursor: pointer;color: #2d85d2;" class="itemoff" 
		data-itemname = "<?= $value['item_name']; ?>"
		data-brand = "<?= $value['brand']; ?>"
		data-model = "<?= $value['model']; ?>"
		data-description = "<?= $value['description']; ?>"
		data-specification = "<?= $value['specification']; ?>"
		data-leadtime = "<?= $value['lead_time']; ?>"
		data-validity = "<?= $value['validity']; ?>"
		data-cost = "<?= $value['cost']; ?>"
		data-quantity = "<?= $value['quantity']; ?>"


		>
			
				<b>Item Name : </b><?= $value['item_name']; ?>
				<br>
				<b>Brand : </b><?= $value['brand'] ?>
				<br>
				<b>Model : </b><?= $value['model'] ?>
			
			<hr>
		</li>
	<?php } ?>
	</ul>

    </div>




    <?php ActiveForm::end(); ?>

 </div>



		    






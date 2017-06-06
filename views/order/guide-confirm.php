<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
//use app\models\LookupShipping;
/* @var $this yii\web\View */
/* @var $model app\models\Ecommerce */
/* @var $form yii\widgets\ActiveForm */
//$shipping = ArrayHelper::map(LookupShipping::find()->asArray()->all(),'id','delivery_type'); 
$this->title = 'Order Confirmation';

$script = <<< JS
$(document).ready(function(){

    $('.optionsRadios5').click(function(){
    	if($(this).val() == 'Confirm All') {

    		 $('.all').show();
    		 $('.different').hide();
    		 $('.reject').hide();
    		 $("#different_status_estimate_shipping").prop("disabled", true);
    		 $("#different_status_estimate_arrival").prop("disabled", true);
    		 $("#estimate_shipping").prop('disabled', false);
    		 $("#estimate_arrival").prop('disabled', false);

    	} else if ($(this).val() == 'Confirm Partially') {

    		$('.different').show();
    		$('.all').hide();
    		$('.reject').hide();
    		$("#estimate_shipping").prop("disabled", true);
    		$("#estimate_arrival").prop("disabled", true);
    		$("#different_status_estimate_shipping").prop('disabled', false);
    		$("#different_status_estimate_arrival").prop('disabled', false);

    	} else if ($(this).val() == 'Reject') {

    		$('.reject').show();
    		$('.different').hide();

    	}

    });



}); 
JS;
$this->registerJs($script);

?>
<h1><?= Html::encode($this->title) ?></h1>
<div class="ecommerce-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="form-group">
        <div class="mt-radio-inline">
            <label class="mt-radio">
                <input type="radio" name="Project[sellers][0][status_confirm]" id="optionsRadios5" class="optionsRadios5" value="Confirm All"> Confirm All
                <span></span>
            </label>
            <label class="mt-radio">
                <input type="radio" name="Project[sellers][0][status_confirm]" id="optionsRadios5" class="optionsRadios5" value="Confirm Partially"> Confirm Partially
                <span></span>
            </label>
            <label class="mt-radio">
                <input type="radio" name="Project[sellers][0][status_confirm]" id="optionsRadios5" class="optionsRadios5" value="Reject"> Reject
                <span></span>
            </label>
        </div>
    </div>


    <div style="display: none;" class="all">


        <div class="row">

            <div class="col-lg-6">

                <?= $form->field($model, 'sellers[0][estimate_shipping_date]')->widget(
                    DatePicker::className(), [
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

                        ],
                        'options' => ['id' => 'estimate_shipping']
                ])->label('Estimate Shipping Date');?>

            </div>

            <div class="col-lg-6">

                <?= $form->field($model, 'sellers[0][estimate_arrival_date]')->widget(
                    DatePicker::className(), [
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

                        ],
                        'options' => ['id' => 'estimate_arrival']
                ])->label('Estimate Arrival Date');?>

            </div>

        </div>
    </div>

    <div style="display: none;" class="different">

        <div class="row">

                <div class="col-lg-6">

                <?= $form->field($model, 'sellers[0][different_status_estimate_shipping_date]')->widget(
                    DatePicker::className(), [
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

                        ],
                        'options' => ['id' => 'different_status_estimate_shipping']
                ])->label('Estimate Shipping Date');?>



                </div>

                <div class="col-lg-6">

                <?= $form->field($model, 'sellers[0][different_status_estimate_arrival_date]')->widget(
                    DatePicker::className(), [
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd',

                        ],
                        'options' => ['id' => 'different_status_estimate_arrival']
                ])->label('Estimate Arrival Date');?>



                </div>          
        </div>

    </div>

    <div style="display: none;" class="reject">


        <?= $form->field($model, 'sellers[0][remark]')->textarea(['rows' => 6])->label('Remark') ?>

        
    </div>




    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Update' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
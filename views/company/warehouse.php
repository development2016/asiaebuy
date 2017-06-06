<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\LookupCountry;
use app\models\LookupState;
/* @var $this yii\web\View */
/* @var $model app\models\User */
$country = ArrayHelper::map(LookupCountry::find()->asArray()->all(), 'id', 'country');
$state = ArrayHelper::map(LookupState::find()->where(['country_id'=>$model->country])->asArray()->all(), 'id', 'state');

$this->title = 'Add Warehouse';

$script = <<< JS
$(document).ready(function(){

    $('.warehouse').on('click', function () {
        var contact = $(this).data('contact');
        var country = $(this).data('country');
        var state = $(this).data('state');
        var city = $(this).data('city');
        var company_name = $(this).data('company_name');
        var address = $(this).data('address');

        $('#contact').val(contact);
        $('#country').val(country);
        $('#state').val(state);
        $('#city').val(city);
        $('#company_name').val(company_name);
        $('#address').val(address);


    });


}); 
JS;
$this->registerJs($script);



?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="row">

	<?php $form = ActiveForm::begin(); ?>
	<div class="col-lg-4 col-xs-12 col-sm-12">

		<?= $form->field($model, 'warehouses[person_in_charge]')->label('Person In Charge') ?>

		<?= $form->field($model, 'warehouses[contact]')->textInput(['id'=>'contact'])->label('Contact') ?>

		<?= $form->field($model, 'warehouses[country]')->dropDownList(
            $country, 
        [
            'prompt' => '-Select Country-',
            'class' => 'form-control',
            'id'=> 'country',
            'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/company/state','id'=>'']).'"+$(this).val(), function(data){$("select#state").html(data);})',

        ])->label('Country') ?>


        <?= $form->field($model, 'warehouses[state]')->dropDownList(
            $state, 
        [
            'prompt' => '-Select State-',
            'class' => 'form-control',
            'id'=> 'state',

        ])->label('State') ?>




		<?= $form->field($model, 'warehouses[location]')->textInput(['id'=>'city'])->label('City') ?>

	</div>

	<div class="col-lg-4 col-xs-12 col-sm-12">

	<?= $form->field($model, 'warehouses[warehouse_name]')->textInput(['id'=>'company_name'])->label('Warehouse Name') ?>

	<?= $form->field($model, 'warehouses[address]')->textarea(['rows' => 6,'id'=>'address'])->label('Address') ?>

	<?= $form->field($model, 'warehouses[latitude]')->label('Latitude') ?>

	<?= $form->field($model, 'warehouses[longitude]')->label('Longitude') ?>


	    <div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>


	</div>
	<?php ActiveForm::end(); ?>

    <div class="col-lg-4 col-xs-12 col-sm-12" style="height: 560px; overflow-y: auto; overflow-x: auto;   border-left: solid #b7b7b7 1px;">

        <ul style="list-style-type: none; ">
        	Default Address : 
            <li style="cursor: pointer;color: #2d85d2;" class="warehouse" 
                data-contact="<?= $model->telephone_no ?>"  
                data-country="<?= $model->country ?>"  
                data-state="<?= $model->state ?>"
                data-city="<?= $model->city ?>"
                data-company_name="<?= $model->company_name ?>"
                data-address="<?= $model->address ?><?php echo $model->zip_code ?>" 

                >
                <?php echo $model->company_name ?>
                    
            </li>

        

        </ul>

    </div>

</div>
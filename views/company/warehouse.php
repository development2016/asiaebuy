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
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="row">
<?php $form = ActiveForm::begin(); ?>

	<div class="col-lg-6 col-xs-12 col-sm-12">

		<?= $form->field($model, 'warehouses[person_in_charge]')->label('Person In Charge') ?>

		<?= $form->field($model, 'warehouses[contact]')->label('Contact') ?>

		<?= $form->field($model, 'warehouses[country]')->dropDownList(
            $country, 
        [
            'prompt' => '-Select Country-',
            'class' => 'form-control',
            'onchange'=>'$.post("'.Yii::$app->urlManager->createUrl(['/company/state','id'=>'']).'"+$(this).val(), function(data){$("select#state-id").html(data);})',

        ])->label('Country') ?>


        <?= $form->field($model, 'warehouses[state]')->dropDownList(
            $state, 
        [
            'prompt' => '-Select State-',
            'class' => 'form-control',
            'id'=> 'state-id',

        ])->label('State') ?>




		<?= $form->field($model, 'warehouses[location]')->label('City') ?>

	</div>

	<div class="col-lg-6 col-xs-12 col-sm-12">

	<?= $form->field($model, 'warehouses[warehouse_name]')->label('Warehouse Name') ?>

	<?= $form->field($model, 'warehouses[address]')->textarea(['rows' => 6])->label('Address') ?>

	<?= $form->field($model, 'warehouses[latitude]')->label('Latitude') ?>

	<?= $form->field($model, 'warehouses[longitude]')->label('Longitude') ?>


	    <div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Save' : 'Save', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>


	</div>

<?php ActiveForm::end(); ?>
</div>
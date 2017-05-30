<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<?php $form = ActiveForm::begin([
    'action'  => ['search'],
    'method'  => 'get',
    'options' => ['class' => 'form-inline'],
]);?>
	<div class="form-group">

	  <label class="control-label" for="search">Search: </label>


	  <input id="search" name="search" placeholder="Search Here" class="form-control input-md" required value="" type="text">

	</div>

	<div class="form-group">
			<?=Html::submitButton('Search', ['class' => 'btn btn-primary'])?>

	</div>

<?php ActiveForm::end();?>
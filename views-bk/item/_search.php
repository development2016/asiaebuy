<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'image') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'brand_id') ?>

    <?= $form->field($model, 'model_id') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'specification') ?>

    <?php // echo $form->field($model, 'lead_time_id') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'stock') ?>

    <?php // echo $form->field($model, 'quantity') ?>

    <?php // echo $form->field($model, 'publish') ?>

    <?php // echo $form->field($model, 'enter_by') ?>

    <?php // echo $form->field($model, 'update_by') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <?php // echo $form->field($model, 'owner_item') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

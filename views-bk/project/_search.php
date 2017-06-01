<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="project-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, '_id') ?>

    <?= $form->field($model, 'title_id') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'due_date') ?>

    <?= $form->field($model, 'project_no') ?>

    <?php // echo $form->field($model, 'type_of_project') ?>

    <?php // echo $form->field($model, 'buyer_id') ?>

    <?php // echo $form->field($model, 'approval_id') ?>

    <?php // echo $form->field($model, 'approve_date') ?>

    <?php // echo $form->field($model, 'enter_by') ?>

    <?php // echo $form->field($model, 'update_by') ?>

    <?php // echo $form->field($model, 'date_create') ?>

    <?php // echo $form->field($model, 'date_update') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

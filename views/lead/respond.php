<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$status_info = [
	'Responded' => 'I`m '.$seller->account_name.' Interested To Quote',

]

?>
<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sellers[status]')->dropDownList($status_info, ['prompt' => '-Please Choose-']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Respond', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
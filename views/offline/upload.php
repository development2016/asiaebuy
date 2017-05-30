<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Project */

$this->title = 'Upload Image';

?>
<div class="project-create">

    <h1><?= Html::encode($this->title) ?></h1>


	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

	    <?= $form->field($model, 'imageFile')->fileInput() ?>

	    <button class="btn btn-primary">Upload</button>


	<?php ActiveForm::end() ?>

</div>

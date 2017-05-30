<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LookupModel */

$this->title = 'Update Lookup Model: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Lookup Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lookup-model-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

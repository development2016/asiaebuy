<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LookupModel */

$this->title = 'Create Lookup Model';
$this->params['breadcrumbs'][] = ['label' => 'Lookup Models', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lookup-model-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

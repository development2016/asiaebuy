<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="row">

    <?= $this->render('_form', [
        'model' => $model,
        'model2' => $model2,
        'type' => $type,
        'company' => $company,
       

    ]) ?>

</div>

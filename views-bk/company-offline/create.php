<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CompanyOffline */

$this->title = 'Create Company Offline';
$this->params['breadcrumbs'][] = ['label' => 'Company Offlines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-offline-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

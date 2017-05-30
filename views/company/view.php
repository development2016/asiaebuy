<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Company */

$this->title = $model->_id;
$this->params['breadcrumbs'][] = ['label' => 'Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => (string)$model->_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => (string)$model->_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            '_id',
            'asia_ebuy_no',
            'company_name',
            'address',
            'country',
            'state',
            'city',
            'zip_code',
            'type_of_business',
            'bank_account_name',
            'bank_account_no',
            'bank',
            'tax_no',
            'company_registeration_no',
            'keyword',
            'date_create',
            'date_update',
            'enter_by',
            'update_by',
            'type',
            'email',
            'website',
            'admin',
            'telephone_no',
            'fax_no',
            'logo',
            'term',
        ],
    ]) ?>

</div>

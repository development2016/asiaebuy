<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lookup Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lookup-model-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lookup Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'model',
            'brand_id',
            'enter_by',
            'update_by',
            // 'date_create',
            // 'date_update',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

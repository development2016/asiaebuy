<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Offlines';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-offline-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Company Offline', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'company_name',
            'company_registeration_no',
            'address',
            'zip_code',
            // 'country',
            // 'state',
            // 'city',
            // 'telephone_no',
            // 'fax_no',
            // 'email',
            // 'website',
            // 'gst',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

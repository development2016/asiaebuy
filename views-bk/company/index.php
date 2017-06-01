<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserCompanySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Company', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            '_id',
            'asia_ebuy_no',
            'company_name',
            'address',
            'country',
            // 'state',
            // 'city',
            // 'zip_code',
            // 'type_of_business',
            // 'bank_account_name',
            // 'bank_account_no',
            // 'bank',
            // 'tax_no',
            // 'company_registeration_no',
            // 'keyword',
            // 'date_create',
            // 'date_update',
            // 'enter_by',
            // 'update_by',
            // 'type',
            // 'email',
            // 'website',
            // 'admin',
            // 'warehouses',
            // 'telephone_no',
            // 'fax_no',
            // 'logo',
            // 'term',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

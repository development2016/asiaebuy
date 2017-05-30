<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use miloschuman\highcharts\Highcharts;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('#create').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

    $('.role').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });





}); 
JS;
$this->registerJs($script);
$buyer = $approval = $user= $seller = $admin= 0;

?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
    <li class="active">Users</li>
</ol>
</div>

<div class="row">

    <div class="col-lg-8 col-xs-12 col-sm-12">

    <div class="portlet light bordered">
          <div class="portlet-title tabbable-line">
              <div class="caption">
                  <i class="icon-bubbles font-dark hide"></i>
                  <span class="caption-subject font-dark bold uppercase">CHART</span>
              </div>

          </div>
          <div class="portlet-body">



          </div>
      </div>



    
    </div>

    <div class="col-lg-4 col-xs-12 col-sm-12">


      <div class="portlet light bordered">
          <div class="portlet-title tabbable-line">
              <div class="caption">
                  <i class="icon-bubbles font-dark hide"></i>
                  <span class="caption-subject font-dark bold uppercase">CHART</span>
              </div>

          </div>
          <div class="portlet-body">



        <?= Html::a('Add',FALSE, [
          'value'=>Url::to([
            'user/create',
            'company_id'=>(string)$company->_id,
            'type'=>$company->type
          ]),'class' => 'btn btn-success','id'=>'create']) ?>

        <br><br>
         <table class="table table-bordered">
            <thead>
                <tr style="background-color: #ecf6ff;">
                    <th class="uppercase">No</th>
                    <th class="uppercase">Account Name</th>
                    <th class="uppercase">Role</th>
                    <th class="uppercase">Action</th>
                </tr>
            </thead>
            <tbody>

            <?php $i=0; foreach ($userList as $key => $value) { $i++;?>
              <tr>
                <td><?php echo $i; ?></td>
                <td>
                    <?php echo $value['account_name'] ?>
                </td>
                <td>
                         <?php  
                         $connection = \Yii::$app->db;
                         $sql = $connection->createCommand('SELECT lookup_menu.as_a AS as_a,acl.user_id AS id_user,lookup_role.role AS role FROM acl 
                          RIGHT JOIN acl_menu ON acl.acl_menu_id = acl_menu.id
                          RIGHT JOIN lookup_menu ON acl_menu.menu_id = lookup_menu.menu_id
                          RIGHT JOIN lookup_role ON acl_menu.role_id = lookup_role.role_id
                          WHERE acl.user_id = "'.$value['id_user'].'" GROUP BY lookup_role.role');
                                                     $model = $sql->queryAll(); ?>

                          <ul>
                          <?php foreach ($model as $key2 => $value2) { ?>

                              <?php if ($value2['as_a'] == 300) { ?>
                                <li><?= $value2['role'] ?></li>

                              <?php } elseif ($value2['as_a'] == 200) { ?>
                                <li><?= $value2['role'] ?></li>
                              <?php } ?>
                             
                          <?php } ?>
                          </ul>
                        </td>

              </tr>
            <?php } ?>


            </tbody>

        </table>

        </div>
        </div>



    </div>


</div>
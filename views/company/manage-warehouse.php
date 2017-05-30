<?php

use yii\helpers\Html;
use meysampg\gmap\GMapMarker;
use yii\helpers\Url;
use app\models\LookupCountry;
use app\models\LookupState;
/* @var $this yii\web\View */
/* @var $model app\models\CompanyInformation */

$this->title = 'Manage Warehouse';

$script = <<< JS
$(document).ready(function(){

    $('#create').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });

}); 
JS;
$this->registerJs($script);




?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
    <li class="active"><?= Html::encode($this->title) ?></li>
</ol>
</div>


<div class="row">

    <div class="col-lg-12 col-xs-12 col-sm-12">

    <div class="portlet light bordered">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-bubbles font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase"><?= Html::encode($this->title) ?></span>
            </div>
             <?= Html::a('Add Warehouse',FALSE, ['value'=>Url::to(['company/warehouse','company_id'=>(string)$newCompanyid]),'class' => 'btn btn-sm btn-success pull-right','id'=>'create']) ?>
        </div>
        <div class="portlet-body">



        <?php if (empty($process)) { ?>
   
        <?php } else { ?>


        <div class="row">
              <div class="col-lg-7 col-xs-12 col-sm-12">

            <?php $i=0; foreach ($process[0]['warehouses'] as $key => $value) { $i++; 

            $country = LookupCountry::find()->where(['id'=>$value['country']])->one();
            $state = LookupState::find()->where(['id'=>$value['state']])->one();

            ?>

                  <div class="col-md-4">
                        <h4><b><?php echo $country->country; ?> / <?php echo $state->state; ?> / <?php echo $value['location']; ?></b></h4>
                        <?= GMapMarker::widget([
                            'width' => '300px', // Using pure number for 98% of width.
                            'height' => '200px', // Or use number with unit (In this case 400px for height).
                            'marks' => [$value['latitude'], $value['longitude']],
                            'zoom' => 10,
                            'disableDefaultUI' => true
                        ]); ?>
                        <br>
                        <table class="table table-bordered">
                          <tr>
                            <th>PIC</th>
                            <td><?php echo $value['person_in_charge']; ?></td>
                          </tr>
                          <tr>
                            <th>Contact</th>
                            <td><?php echo $value['contact']; ?></td>
                          </tr>
                          <tr>
                            <th>Location</th>
                            <td><?php echo $value['address']; ?>,<?php echo $value['location']; ?>,<?php echo $state->state; ?></td>
                          </tr>

                        </table>

                  </div>

            <?php } ?>


              </div>

              <div class="col-lg-5 col-xs-12 col-sm-12">


                    <table class="table table-bordered">
                        <thead>
                            <tr style="background-color: #ecf6ff;">
                                <th class="uppercase">No</th>
                                <th class="uppercase">Person In Charge</th>
                                <th class="uppercase"">Contact No</th>
                                <th class="uppercase"">Location</th>
                            </tr>
                        </thead>
                        <?php $i=0; foreach ($process[0]['warehouses'] as $key => $value) { $i++; ?>
                        <tbody>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $value['person_in_charge']; ?></td>
                                <td><?php echo $value['contact']; ?></td>
                                <td><?php echo $value['address']; ?> , <?php echo $value['location']; ?> , <?php echo $state->state; ?> , <?php echo $country->country; ?>
                                </td>
                            </tr>
                        </tbody>
                        <?php } ?>

                    </table>


              </div>

        </div>



        <?php } ?>


        </div>
    </div>

    </div>
</div>
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\LookupTitle;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Url;
use app\models\LookupModel;
use app\models\LookupBrand;

$title = ArrayHelper::map(LookupTitle::find()->asArray()->all(),'title','title'); 

$this->title = 'Source';

$script = <<< JS
$(document).ready(function(){


    $('.rfq').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.choose-approval').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

    $('.choose-approval-level').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });



        var table = $('#source');

        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
               // { extend: 'print', className: 'btn default' },
               // { extend: 'pdf', className: 'btn default' },
                //{ extend: 'csv', className: 'btn default' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                details: {
                   
                }
            },

            "order": [
                [0, 'asc']
            ],
            
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });

        var table = $('#history');

        var oTable = table.dataTable({
            // Internationalisation. For more info refer to http://datatables.net/manual/i18n
            "language": {
                "aria": {
                    "sortAscending": ": activate to sort column ascending",
                    "sortDescending": ": activate to sort column descending"
                },
                "emptyTable": "No data available in table",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "No entries found",
                "infoFiltered": "(filtered1 from _MAX_ total entries)",
                "lengthMenu": "_MENU_ entries",
                "search": "Search:",
                "zeroRecords": "No matching records found"
            },

            // Or you can use remote translation file
            //"language": {
            //   url: '//cdn.datatables.net/plug-ins/3cfcc339e89/i18n/Portuguese.json'
            //},

            // setup buttons extentension: http://datatables.net/extensions/buttons/
            buttons: [
               // { extend: 'print', className: 'btn default' },
               // { extend: 'pdf', className: 'btn default' },
                //{ extend: 'csv', className: 'btn default' }
            ],

            // setup responsive extension: http://datatables.net/extensions/responsive/
            responsive: {
                details: {
                   
                }
            },

            "order": [
                [0, 'asc']
            ],
            
            "lengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "pageLength": 10,

            "dom": "<'row' <'col-md-12'B>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // horizobtal scrollable datatable

            // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
            // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js). 
            // So when dropdowns used the scrollable div should be removed. 
            //"dom": "<'row' <'col-md-12'T>><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r>t<'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
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
    <li class="active">Source</li>
</ol>
</div>

                    
<?php if(Yii::$app->session->hasFlash('request')):?>
  <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert"></button>
       <?php echo  Yii::$app->session->getFlash('request'); ?>
  </div>
<?php endif; ?>
<?php if(Yii::$app->session->hasFlash('spot')):?>
  <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert"></button>
       <?php echo  Yii::$app->session->getFlash('spot'); ?>
  </div>
<?php endif; ?>

<div class="row">

    <div  class="col-lg-12 col-xs-12 col-sm-12">


    <div class="portlet light bordered">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-bubbles font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">SOURCE</span>
            </div>

            <div class="actions">
                <div class="btn-group btn-group-devided" >

                    <?= Html::a('Direct Purchase', ['offline/index'],['target' => '_blank','class'=>'btn green-jungle btn-outline btn-sm','title'=>'Direct Purchase']) ?>
                    <?= Html::a('Guide Buying <i class="fa fa-shopping-cart"></i>', ['shop/index'],['target' => '_blank','class'=>'btn red-sunglo btn-outline btn-sm','title'=>'Go Guide Buying']) ?>
                    <?= Html::a('Request Quote <i class="fa fa-book"></i>',FALSE, ['value'=>Url::to(['project/create']),'class' => 'btn blue-steel btn-outline btn-sm rfq','id'=>'','title'=>'Post RFQ']) ?>
                    <?= Html::a('MySpot Buy <i class="fa fa-search"></i>', ['project/spot'],['target' => '_blank','class'=>'btn yellow-casablanca btn-outline btn-sm','title'=>'MySpot Buy']) ?>
                    

                </div>
            </div>




        </div>
        <div class="portlet-body">

            <div class="tabbable-line">
                <ul class="nav nav-tabs ">
                    <li class="active">
                        <a href="#tab_15_1" data-toggle="tab"> Active </a>
                    </li>
                    <li>
                        <a href="#tab_15_2" data-toggle="tab"> History </a>
                    </li>

                </ul>
                <div class="tab-content">
                        <div class="tab-pane active" id="tab_15_1">

                            <table class="table table-striped table-bordered "  id="source" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="uppercase">No</th>
                                        <th class="uppercase">Project No</th>
                                        <th class="none"></th>
                                        <th class="uppercase">Type Of Project</th>
                                        <th class="uppercase">Details</th>
                                        <th class="uppercase">Infomation</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; foreach ($model as $key => $value) { $i++; ?>
                       
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $value['project_no']; ?></td>
                                        <td>
                                            
                                        <?php if ($value['type_of_project'] == 'Guide Buying') { ?>

                                            <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                <?php foreach ($value2['items'] as $key3 => $value3) { ?>
                                                    <table class="table table-striped table-bordered">
                                                        <tr>
                                                            <th style="background-color: #ecf6ff;">Item Name</th>
                                                            <td><?php echo $value3['item_name']; ?></td>
                                                            <th style="background-color: #ecf6ff;">Brand</th>
                                                            <td>
                                                                <?php $brand = LookupBrand::find()->where(['id'=>$value3['brand']])->one();
                                                                echo $brand->brand;
                                                                 ?> 
                                                            </td>
                                                            <th style="background-color: #ecf6ff;">Model</th>
                                                            <td>
                                                                <?php $mdl = LookupModel::find()->where(['id'=>$value3['model']])->one();
                                                                echo $mdl->model;
                                                                 ?>
                                                            </td>
                                                
                                                        </tr>
                                                        <tr>
                                                            <th style="background-color: #ecf6ff;">Specification </th>
                                                            <td><?php echo $value3['specification']; ?></td>
                                                            <th style="background-color: #ecf6ff;">Description </th>
                                                            <td colspan="3"><?php echo $value3['description']; ?></td>


                                                        </tr>
                                                    </table>
                                                    
                                                <?php } ?>

                                            <?php } ?>




                                        <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>
                                            No Item Added

                                        <?php } elseif ($value['type_of_project'] == 'MySpot Buy') { ?>
                                            No Item Added

                                        <?php } ?>


                          
                                        </td>
                                        <td>
                                                <?php if ($value['type_of_project'] == 'MySpot Buy') { ?>
                                                    <span class="label bg-yellow-casablanca"> <?php echo $value['type_of_project']; ?> </span>
                                                <?php } elseif ($value['type_of_project'] == 'Guide Buying') { ?>
                                                    <span class="label bg-red-sunglo"> <?php echo $value['type_of_project']; ?> </span>
                                                <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>
                                                    <span class="label bg-blue-steel"> <?php echo $value['type_of_project']; ?> </span>
                                                <?php } elseif ($value['type_of_project'] == 'Direct Purchase') { ?>
                                                    <span class="label bg-green-jungle"> <?php echo $value['type_of_project']; ?> </span>
                                                <?php } else {} ?>

                                         </td>
                                        <td>
                                                <ul>
                                                    <li><b>Title</b> : <?= $value['title']; ?></li>
                                                    <li><b>Description</b> : <?= $value['description']; ?></li>
                                                    <li><b>Due Date</b> : <?= $value['due_date']; ?></li>
                                                    <li><b>Date Create</b> : <?= $value['date_create']; ?></li>

                                                </ul>

                                                <?php if ($value['type_of_project'] == 'MySpot Buy') { ?>
                                                <ul>
                                                    <li><b>Website</b> : <a href="<?= $value['url_myspot']; ?>" target="_blank"><?= $value['url_myspot']; ?></a> </li>
                                                </ul>     
                                                <?php } else { ?>

                                                <?php } ?>


 

                                        </td>
                                        <td>
                                            <table class="table table-bordered " >
                                                <tr style="background-color: #ecf6ff;">
                                                    <th class="uppercase">Seller Name</th>
                                                    <th class="uppercase">Status</th>
                                                    <th class="uppercase">Action</th>
                                                </tr>
                                                <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                <tr>
                                                    <td><?php echo $value2['seller'] ?></td>
                                                    <td><?php echo $status =  $value2['status'] ?></td>
                                                    <td>
                                                        
                                                        <?php if ($status == "Responded") { ?>

                                                            <div class="margin-bottom-5">
                                                            <?= Html::a('Request Quotation', ['source/request',
                                                                'project'=>(string)$value['_id'],
                                                                'seller'=>(string)$value2['seller'],
                                                                ],['class'=>'btn blue btn-sm btn-outline','title'=>'Request Quotation']) ?>
                                                            </div>

                                                        <?php } elseif ($status == "Revise") { ?>

                                                                <div class="margin-bottom-5">
                                                                <?= Html::a('Message', ['message/message',
                                                                    'project'=>(string)$value['_id'],
                                                                    'to'=>$value2['seller'],
                                                                    ],['class'=>'btn btn-sm blue btn-outline','title'=>'Message']) ?>
                                                                </div>


                                                                    <?php if ($value['type_of_project'] == 'Guide Buying') { ?>

                                                    
                                                                    <div class="margin-bottom-5">

                                                                            <div class="btn-group">
                                                                                <a class="btn btn-sm blue btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu">
                
                                                                                    <?php foreach ($value2['revise'] as $key => $revise) { ?>
                                                                                       <li>
                                                                                            <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                            'revise/guide-quotation-revise',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'quotation_no' => $revise['quotation_no']
                                                                                            ],['target'=>'_blank']) ?>
                                                                                        </li>
                                                                                    <?php } ?>

                                                                                </ul>
                                                                            </div>

                                                                    </div>


                                                                    <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>


                                                                    <div class="margin-bottom-5">

                                                                            <div class="btn-group">
                                                                                <a class="btn btn-sm blue btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu">
                                                             
                                                                                    <?php foreach ($value2['revise'] as $key => $revise) { ?>
                                                                                       <li>
                                                                                            <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                            'revise/sale-quotation-revise',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'quotation_no' => $revise['quotation_no']
                                                                                            ],['target'=>'_blank']) ?>
                                                                                        </li>
                                                                                    <?php } ?>
                                          
                                                                                </ul>
                                                                            </div>

                                                                    </div>

                                                                        
                                                                    <?php } ?>


                                                        <?php } elseif ($status == "Quoted" || $status == "Quotation Uploaded") { ?>

                                                                <div class="margin-bottom-5">
                                                                <?= Html::a('Message', ['message/message',
                                                                    'project'=>(string)$value['_id'],
                                                                    'to'=>$value2['seller'],
                                                                    ],['class'=>'btn btn-sm blue btn-outline','title'=>'Message']) ?>
                                                                </div>

                                                                <?php if ($value['type_of_project'] == 'Guide Buying') { ?>


                                                                    <?php if (empty($value2['approval'])) { ?>

                                                                        <div class="margin-bottom-5">
                                                                            <div class="btn-group">
                                                                                    <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Purchase Requisition
                                                                                        <i class="fa fa-angle-down"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li>
                                                                                            <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                                            'source/choose-approval',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyer'],
                                                                                            'type' => 'guide',
                                                     
                                                                                            ]),'class' => 'choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>

                                                                                        </li>
                                                                                        <li>
                                                                                            <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                            'source/choose-approval-level',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyer'],
                                                                                            'type' => 'guide',
                                                     
                                                                                            ]),'class' => 'choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                                            
                                                                                        </li>
                                                                                    </ul>
                                                                            </div>


                                                                        </div>

                                                                    <?php } else { ?>


                                                                        <div class="margin-bottom-5">
                                                                        <?= Html::a('Purchase Requisition', ['source/guide-purchase-requisition',
                                                                            'project'=>(string)$value['_id'],
                                                                            'seller'=>(string)$value2['seller'],
                                                                            'approver'=>$value2['approver'],
                                                                            'buyer'=>$value['buyer'],
                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                        </div>


                                                                    <?php }  ?>


                                                                <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>


                                                                    <?php if (empty($value2['approval'])) { ?>

                                                                        <div class="margin-bottom-5">
                                                                        <?= Html::a('Purchase Requisition',FALSE, ['value'=>Url::to([
                                                                        'source/choose-approval',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$value['buyer'],
                                                                        'type' => 'sale',
                                 
                                                                        ]),'class' => 'btn blue btn-sm btn-outline choose-approval','id'=>'choose-approval','title'=>'Purchase Requisition']) ?>
                                                                        </div>

                                                                    <?php } else { ?>


                                                                        <div class="margin-bottom-5">
                                                                        <?= Html::a('Purchase Requisition', ['source/sale-purchase-requisition',
                                                                            'project'=>(string)$value['_id'],
                                                                            'seller'=>(string)$value2['seller'],
                                                                            'buyer'=>$value['buyer'],
                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                        </div>


                                                                    <?php }  ?>


                                                                <?php } elseif ($value['type_of_project'] == 'MySpot Buy') { ?>


                                                                    <?php if (empty($value2['approval'])) { ?>

                                                                        <div class="margin-bottom-5">
                                                                        <?= Html::a('Purchase Requisition',FALSE, ['value'=>Url::to([
                                                                        'source/choose-approval',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$value['buyer'],
                                                                        'type' => 'spot',
                                 
                                                                        ]),'class' => 'btn blue btn-sm btn-outline choose-approval','id'=>'choose-approval','title'=>'Purchase Requisition']) ?>
                                                                        </div>

                                                                    <?php } else { ?>


                                                                    <div class="margin-bottom-5">
                                                                    <?= Html::a('Purchase Requisition', ['source/spot-purchase-requisition',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>(string)$value2['seller'],
                                                                        'buyer'=>$value['buyer'],
                                                                        ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                    </div>


                                                                    <?php }  ?>


                                                                <?php } elseif ($value['type_of_project'] == 'Direct Purchase') { ?>


                                                                    <?php if (empty($value2['approval'])) { ?>

                                                                        <div class="margin-bottom-5">
                                                                            <div class="btn-group">
                                                                                    <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Purchase Requisition
                                                                                        <i class="fa fa-angle-down"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li>
                                                                                            <?= Html::a('Choose Approver',FALSE, ['value'=>Url::to([
                                                                                            'source/choose-approval',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyer'],
                                                                                            'type' => 'direct',
                                                     
                                                                                            ]),'class' => 'choose-approval','id'=>'choose-approval','title'=>'Choose Approver']) ?>

                                                                                        </li>
                                                                                        <li>
                                                                                            <?= Html::a('Choose Approver By Level',FALSE, ['value'=>Url::to([
                                                                                            'source/choose-approval-level',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyer'],
                                                                                            'type' => 'direct',
                                                     
                                                                                            ]),'class' => 'choose-approval-level','id'=>'choose-approval-level','title'=>'Choose Approver By Level']) ?>
                                                                                            
                                                                                        </li>
                                                                                    </ul>
                                                                            </div>


                                                                        </div>


                                                                    <?php } else { ?>


                                                                    <div class="margin-bottom-5">
                                                                    <?= Html::a('Purchase Requisition', ['source/direct-purchase-requisition',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>(string)$value2['seller'],
                                                                        'approver'=>$value2['approver'],
                                                                        'buyer'=>$value['buyer'],
                                                                        ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                    </div>

                                                                    <?php }  ?>


                                                                <?php } ?>










                                                                    <?php if ($value['type_of_project'] == 'Guide Buying') { ?>

                                                                    <div class="margin-bottom-5">

                                                                            <div class="btn-group">
                                                                                <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu">
                                                                                    <li>
                                                                                        <?= Html::a('<b>'.$value2['quotation_no'].'</b>', ['html/guide-quotation-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer' => $value['buyer']
                                                                                            ],['target'=>'_blank']) ?>
                                                                                    </li>
                                                                                    <?php if (empty($value2['revise'])) { ?>
                                                                                       
                                                                                    <?php } else { ?>

                                                                                    <li class="dropdown-submenu">
                                                                                        <a href="javascript:;"> REVISE </a>
                                                                                            <ul class="dropdown-menu" style="">
                                                                                        
                                                                                        <?php foreach ($value2['revise'] as $key => $revise) { ?>
                                                                                                <li>
                                                                                                    <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                                    'revise/guide-quotation-revise',
                                                                                                    'project'=>(string)$value['_id'],
                                                                                                    'seller'=>$value2['seller'],
                                                                                                    'quotation_no' => $revise['quotation_no']
                                                                                                    ],['target'=>'_blank']) ?>
                                                                                                </li>
                                                                                
                                                                                        <?php } ?>

                                                                                            </ul>
                                                                                    </li>
                                                                                            
                                                                                    <?php } ?>
                                          
                                                                                </ul>
                                                                            </div>

                                                                    </div>


                                                                    <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>


                                                                    <div class="margin-bottom-5">

                                                                            <div class="btn-group">
                                                                                <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu">
                                   
                                                                                    <li>
                                                                                        <?= Html::a('<b>'.$value2['quotation_no'].'</b>', ['html/sale-quotation-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer' => $value['buyer']
                                                                                            ],['target'=>'_blank']) ?>
                                                                                    </li>
                                                                                    <?php if (empty($value2['revise'])) { ?>
                                                                                       
                                                                                    <?php } else { ?>

                                                                                    <li class="dropdown-submenu">
                                                                                        <a href="javascript:;"> REVISE </a>
                                                                                            <ul class="dropdown-menu" style="">
                                                                                        
                                                                                        <?php foreach ($value2['revise'] as $key => $revise) { ?>
                                                                                                <li>
                                                                                                    <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                                    'revise/sale-quotation-revise',
                                                                                                    'project'=>(string)$value['_id'],
                                                                                                    'seller'=>$value2['seller'],
                                                                                                    'quotation_no' => $revise['quotation_no']
                                                                                                    ],['target'=>'_blank']) ?>
                                                                                                </li>
                                                                                
                                                                                        <?php } ?>

                                                                                            </ul>
                                                                                    </li>
                                                                                            
                                                                                    <?php } ?>

                                          
                                                                                </ul>
                                                                            </div>

                                                                    </div>

                                                                    <?php } elseif ($value['type_of_project'] == 'MySpot Buy') { ?>

                                                                    <div class="margin-bottom-5">

                                                                            <div class="btn-group">
                                                                                <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu">
                                   
                                                                                    <li>
                                                                                        <?= Html::a('<b>'.$value2['quotation_no'].'</b>', ['html/spot-quotation-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer' => $value['buyer']
                                                                                            ],['target'=>'_blank']) ?>
                                                                                    </li>
                                                                                    <?php if (empty($value2['revise'])) { ?>
                                                                                       
                                                                                    <?php } else { ?>

                                                                                    <li class="dropdown-submenu">
                                                                                        <a href="javascript:;"> REVISE </a>
                                                                                            <ul class="dropdown-menu" style="">
                                                                                        
                                                                                        <?php foreach ($value2['revise'] as $key => $revise) { ?>
                                                                                                <li>
                                                                                                    <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                                    'revise/sale-quotation-revise',
                                                                                                    'project'=>(string)$value['_id'],
                                                                                                    'seller'=>$value2['seller'],
                                                                                                    'quotation_no' => $revise['quotation_no']
                                                                                                    ],['target'=>'_blank']) ?>
                                                                                                </li>
                                                                                
                                                                                        <?php } ?>

                                                                                            </ul>
                                                                                    </li>
                                                                                            
                                                                                    <?php } ?>

                                          
                                                                                </ul>
                                                                            </div>

                                                                    </div>

                                                                        
                                                                    <?php } ?>



                                                        <?php } ?>

                                                    </td>
                                                </tr>
                                                <?php } ?>
                   
                                            </table>
                                            
                                        </td>
                                    </tr>
                                
                                   <?php } ?>
                                   </tbody>
                            </table>



                        </div>
                        <div class="tab-pane" id="tab_15_2">



                            <table class="table table-striped table-bordered "  id="history" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th class="uppercase">No</th>
                                        <th class="uppercase">Project No</th>
                                        <th class="none"></th>
                                        <th class="uppercase">Type Of Project</th>
                                        <th class="uppercase">Details</th>
                                        <th class="uppercase">Infomation</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=0; foreach ($history as $key => $value_history) { $i++; ?>
                       
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $value_history['project_no']; ?></td>
                                        <td>
                                            
                                        <?php if ($value_history['type_of_project'] == 'Guide Buying') { ?>

                                            <?php foreach ($value_history['sellers'] as $key => $value2_history) { ?>
                                                <?php foreach ($value2_history['items'] as $key3 => $value3_history) { ?>
                                                    <table class="table table-striped table-bordered">
                                                        <tr>
                                                            <th style="background-color: #ecf6ff;">Item Name</th>
                                                            <td><?php echo $value3_history['item_name']; ?></td>
                                                            <th style="background-color: #ecf6ff;">Brand</th>
                                                            <td>
                                                                <?php $brand = LookupBrand::find()->where(['id'=>$value3_history['brand']])->one();
                                                                echo $brand->brand;
                                                                 ?> 
                                                            </td>
                                                            <th style="background-color: #ecf6ff;">Model</th>
                                                            <td>
                                                                <?php $mdl = LookupModel::find()->where(['id'=>$value3_history['model']])->one();
                                                                echo $mdl->model;
                                                                 ?>
                                                            </td>
                                                
                                                        </tr>
                                                        <tr>
                                                            <th style="background-color: #ecf6ff;">Specification </th>
                                                            <td><?php echo $value3_history['specification']; ?></td>
                                                            <th style="background-color: #ecf6ff;">Description </th>
                                                            <td colspan="3"><?php echo $value3_history['description']; ?></td>


                                                        </tr>
                                                    </table>
                                                    
                                                <?php } ?>

                                            <?php } ?>




                                        <?php } elseif ($value_history['type_of_project'] == 'Sale Lead') { ?>
                                            No Item Added

                                        <?php } elseif ($value_history['type_of_project'] == 'MySpot Buy') { ?>
                                            No Item Added

                                        <?php } ?>


                          
                                        </td>
                                        <td>
                                                <?php if ($value_history['type_of_project'] == 'MySpot Buy') { ?>
                                                    <span class="label bg-yellow-casablanca"> <?php echo $value_history['type_of_project']; ?> </span>
                                                <?php } elseif ($value_history['type_of_project'] == 'Guide Buying') { ?>
                                                    <span class="label bg-red-sunglo"> <?php echo $value_history['type_of_project']; ?> </span>
                                                <?php } elseif ($value_history['type_of_project'] == 'Sale Lead') { ?>
                                                    <span class="label bg-blue-steel"> <?php echo $value_history['type_of_project']; ?> </span>
                                                <?php } elseif ($value_history['type_of_project'] == 'Direct Purchase') { ?>
                                                    <span class="label bg-green-jungle"> <?php echo $value_history['type_of_project']; ?> </span>
                                                <?php } else {} ?>

                                         </td>
                                        <td>
                                                <ul>
                                                    <li><b>Title</b> : <?= $value_history['title']; ?></li>
                                                    <li><b>Description</b> : <?= $value_history['description']; ?></li>
                                                    <li><b>Due Date</b> : <?= $value_history['due_date']; ?></li>
                                                    <li><b>Date Create</b> : <?= $value_history['date_create']; ?></li>

                                                </ul>

                                                <?php if ($value_history['type_of_project'] == 'MySpot Buy') { ?>
                                                <ul>
                                                    <li><b>Website</b> : <a href="<?= $value_history['url_myspot']; ?>" target="_blank"><?= $value_history['url_myspot']; ?></a> </li>
                                                </ul>     
                                                <?php } else { ?>

                                                <?php } ?>


 

                                        </td>
                                        <td>
                                            <table class="table table-bordered " >
                                                <tr style="background-color: #ecf6ff;">
                                                    <th class="uppercase">Seller Name</th>
                                                    <th class="uppercase">Action</th>
                                                </tr>
                                                <?php foreach ($value_history['sellers'] as $key => $value2_history) { ?>
                                                <tr>
                                                    <td><?php echo $value2_history['seller'] ?></td>
                                                    <td>
                                                        <?php foreach ($value2_history['history'] as $key => $value3_history) { ?>

                                                            <?php if ($value_history['type_of_project'] == 'Guide Buying') { ?>


                                                                <div class="margin-bottom-5">

                                                                        <div class="btn-group">
                                                                            <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                <i class="fa fa-angle-down"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu">
                                                                                <li>
                                                                                    <?= Html::a('<b>'.$value3_history['quotation_no'].'</b>', ['html/guide-quotation-html',
                                                                                        'project'=>(string)$value_history['_id'],
                                                                                        'seller'=>$value2_history['seller'],
                                                                                        'buyer' => $value_history['buyer']
                                                                                        ],['target'=>'_blank']) ?>
                                                                                </li>

                                      
                                                                            </ul>
                                                                        </div>

                                                                </div>




                                                            <?php } ?>

                                                        <?php } ?>
                                                    </td>

                                                </tr>
                                                <?php } ?>
                   
                                            </table>
                                            
                                        </td>
                                    </tr>
                                
                                   <?php } ?>
                                   </tbody>
                            </table>








                        </div>
                </div>
            </div>
                          

    	 </div>

         
        </div>


    </div>




</div>
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


$this->title = 'Request';

$script = <<< JS
$(document).ready(function(){

        var table = $('#request');

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


 $(".div-request-pulsate").pulsate({color:"#bf1c56"});



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

    <div  class="col-lg-12 col-xs-12 col-sm-12">


    <div class="portlet light bordered">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-bubbles font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase"><?= Html::encode($this->title) ?></span>
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

                            <table class="table table-striped table-bordered "  id="request" cellspacing="0">
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
                                                    <th class="uppercase">Request From</th>
                                                    <th class="uppercase">Status</th>
                                                    <th class="uppercase">Action</th>
                                                </tr>
                                                <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                                <tr>
                                                    <td><?php echo $value2['seller'] ?></td>
                                                    <td><?php echo $value['buyers'][0]['buyer']; ?></td>



                                                    <?php if ($value2['status'] == 'Request Approval') { ?>
                                                                <!-- START  LEVEL -->
                                                                <?php if ($value2['approver'] == 'level') { ?>

                                                                        <td>
                                                                                <ul>
                                                                                    <?php foreach ($value2['approval'] as $key => $app) { ?>
                                                                                            <li>
                                                                                            <?= $app['approval']; ?>

                                                                                            <?php if ($app['status'] == 'Waiting Approval') { ?>

                                                                                                : <label class="div-request-pulsate">Waiting To Approve</label>

                                                                                            <?php } elseif ($app['status'] == 'Approve') { ?>

                                                                                                : <label class="label bg-green-jungle font-dark"><?= $app['status']; ?></label>
                                                                                                
                                                                                            <?php } else { ?>

                                                                                            <?php } ?>



                                                                                            </li>
                                                                                            <br>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                        </td>




                                                                <?php } else { ?>

                                                                        <td>
                                                                            <?php if ($value2['status'] == 'Approve') { ?>
                                                                            
                                                                                <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                            <?php } else { ?>

                                                                                 <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                       </td>

                                                                <?php } ?>

                                                     <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>
                                                                <!-- START  NEXT -->
                                                                <?php if ($value2['approver_next'] == 'level') { ?>

                                                                        <td>
                                                                                <ul>
                                                                                    <?php foreach ($value2['approval_next'] as $key_next => $app_next) { ?>
                                                                                            <li>
                                                                                            <?= $app_next['approval_next']; ?>

                                                                                            <?php if ($app_next['status_next'] == 'Waiting Approval') { ?>

                                                                                                : <label class="div-request-pulsate">Waiting To Approve</label>

                                                                                            <?php } elseif ($app_next['status_next'] == 'Approve') { ?>

                                                                                                : <label class="label bg-green-jungle font-dark"><?= $app_next['status_next']; ?></label>
                                                                                                
                                                                                            <?php } else { ?>

                                                                                            <?php } ?>



                                                                                            </li>
                                                                                            <br>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                        </td>




                                                                <?php } else { ?>

                                                                        <td>
                                                                            <?php if ($value2['status'] == 'Approve') { ?>
                                                                            
                                                                                <label class="label bg-green-jungle font-dark"><?php echo $value2['status']; ?></label>

                                                                            <?php } else { ?>

                                                                                 <?php echo $value2['status']; ?>

                                                                            <?php } ?>

                                                                       </td>

                                                                <?php } ?>






                                                     <?php } ?>


                                                    <td>
                                                        <!-- START PR -->

                                                        <?php if ($value2['status'] == 'Approve') { ?>

                                                                <?php if ($value['type_of_project'] == 'Guide Buying') { ?>

                                                                    <div class="margin-bottom-5">

                                                                            <div class="btn-group">
                                                                                <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Purchase Requisition
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                </a>
                                                                                <ul class="dropdown-menu">
                                                                      
                                                                                    <li>
                                                                                        <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/guide-purchase-requisition-html',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            ],['target'=>'_blank']) ?>
                                                                                    </li>

                                          
                                                                                </ul>
                                                                            </div>

                                                                    </div>


                                                                <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>

                                                                <?php } elseif ($value['type_of_project'] == 'MySpot Buy') { ?>

                                                                <?php } elseif ($value['type_of_project'] == 'Direct Purchase') { ?>


                                                                    <div class="margin-bottom-5">

                                                                        <div class="btn-group">
                                                                            <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Purchase Requisition
                                                                                <i class="fa fa-angle-down"></i>
                                                                            </a>
                                                                            <ul class="dropdown-menu">
                                                                  
                                                                                <li>
                                                                                    <?= Html::a('<b>'.$value2['purchase_requisition_no'].'</b>', ['html/direct-purchase-requisition-html',
                                                                                        'project'=>(string)$value['_id'],
                                                                                        'seller'=>$value2['seller'],
                                                                                        'buyer'=>$value['buyers'][0]['buyer'],
                                                                                        ],['target'=>'_blank']) ?>
                                                                                </li>

                                      
                                                                            </ul>
                                                                        </div>

                                                                    </div>


                                                                <?php } ?>


                                                        <?php } else { ?>

                                                                <?php if ($value['type_of_project'] == 'Guide Buying') { ?>

                                                                        <?php if ($value2['approver'] == 'level') { ?>

                                                                                <?php if ($user->account_name == $value2['approver_level']) { ?>
                                                                                    
                                                                                        <div class="margin-bottom-5">
                                                                                            <?= Html::a('Purchase Requisition', ['request/guide-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                                        </div>

                                                                                <?php } else { ?>

                                                                                <?php } ?>

                                                                        <?php } else { ?>


                                                                                    <div class="margin-bottom-5">
                                                                                        <?= Html::a('Purchase Requisition', ['request/guide-purchase-requisition-approve',
                                                                                        'project'=>(string)$value['_id'],
                                                                                        'seller'=>$value2['seller'],
                                                                                        'buyer'=>$value['buyers'][0]['buyer'],
                                                                                        'approver' => $value2['approver'],
                                                                                        ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                                    </div>

                                                                        <?php } ?>





                                                                <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>

                                                                <div class="margin-bottom-5">
                                                                    <?= Html::a('Purchase Requisition', ['request/sale-purchase-requisition-approve',
                                                                    'project'=>(string)$value['_id'],
                                                                    'seller'=>$value2['seller'],
                                                                    'buyer'=>$value['buyers'][0]['buyer'],
                                                                    ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                </div>


                                                                <?php } elseif ($value['type_of_project'] == 'MySpot Buy') { ?>

                                                                    <div class="margin-bottom-5">
                                                                        <?= Html::a('Purchase Requisition', ['request/spot-purchase-requisition-approve',
                                                                        'project'=>(string)$value['_id'],
                                                                        'seller'=>$value2['seller'],
                                                                        'buyer'=>$value['buyers'][0]['buyer'],
                                                                        ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                    </div>


                                                                <?php } elseif ($value['type_of_project'] == 'Direct Purchase') { ?>


                                                                    <?php if ($value2['status'] == 'Request Approval') { ?>


                                                                            <?php if ($value2['approver'] == 'level') { ?>

                                                                                    <?php if ($user->account_name == $value2['approver_level']) { ?>
                                                                                        
                                                                                        <div class="margin-bottom-5">
                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                                        </div>

                                                                                    <?php } else { ?>

                                                                                    <?php } ?>

                                                                            <?php } else { ?>


                                                                                        <div class="margin-bottom-5">
                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver' => $value2['approver'],
                                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                                        </div>

                                                                            <?php } ?>


                                                                    <?php } elseif ($value2['status'] == 'Request Approval Next') { ?>

                                                                            <?php if ($value2['approver_next'] == 'level') { ?>

                                                                                    <?php if ($user->account_name == $value2['approver_level_next']) { ?>
                                                                                        
                                                                                        <div class="margin-bottom-5">
                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver_next' => $value2['approver_next'],
                                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                                        </div>

                                                                                    <?php } else { ?>

                                                                                    <?php } ?>





                                                                            <?php } else { ?>


                                                                                        <div class="margin-bottom-5">
                                                                                            <?= Html::a('Purchase Requisition', ['request/direct-purchase-requisition-approve-next',
                                                                                            'project'=>(string)$value['_id'],
                                                                                            'seller'=>$value2['seller'],
                                                                                            'buyer'=>$value['buyers'][0]['buyer'],
                                                                                            'approver_next' => $value2['approver_next'],
                                                                                            ],['class'=>'btn blue btn-sm btn-outline','title'=>'Purchase Requisition']) ?>
                                                                                        </div>



                                                                            <?php } ?>




                                                                      
                                                                    <?php } ?>








                                                                <?php } ?>

                                                        <?php } ?>

                                                        <!-- END BUTTON PR -->


                                                        <!-- BUTTON QUOTATION  -->

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
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                ],['target'=>'_blank']) ?>
                                                                        </li>

                              
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
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                ],['target'=>'_blank']) ?>
                                                                        </li>

                              
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
                                                                                'buyer'=>$value['buyers'][0]['buyer'],
                                                                                ],['target'=>'_blank']) ?>
                                                                        </li>

                              
                                                                    </ul>
                                                                </div>

                                                            </div>

                                                        <?php } elseif ($value['type_of_project'] == 'Direct Purchase') { ?>

                                                            <div class="margin-bottom-5">

                                                                <div class="btn-group">
                                                                    <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                        <i class="fa fa-angle-down"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                  
                              
                                                                    </ul>
                                                                </div>

                                                            </div>

                                                        <?php } ?>

                                                        <!-- END BUTTON QUOTATION -->

                                                    </td>
                                                </tr>
                                                <?php } ?>
                   
                                            </table>
                                            
                                        </td>
                                    </tr>
                                
                                   <?php } ?>
                                   </tbody>
                            </table>




                            </table>

                        </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>


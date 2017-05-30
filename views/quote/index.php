<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quote';
$this->params['breadcrumbs'][] = $this->title;

$script = <<< JS
$(document).ready(function(){

    $('.modal_revise').click(function(){
        $('#modalmd').modal('show')
        .find('#modalContentMd')
        .load($(this).attr('value'));

    });

        var table = $('#quote');

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
     /*  var project = $(this).attr("value");
        $.ajax({
               url: 'pdfg',
               data: {project: project},

        }); */

?>
<div class="breadcrumbs">
    <h1><?= Html::encode($this->title) ?></h1>
<ol class="breadcrumb">
    <li>
        <a href="#">Dashboard</a>
    </li>
    <li class="active">Quote</li>
</ol>
</div>

<div class="row">

    <div id="table-quote" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">QUOTE</span>
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
        

                                <table class="table table-striped table-bordered "  id="quote" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th class="uppercase">No</th>
                                            <th class="uppercase">Project No</th>
                                            <th class="uppercase">Type Of Project</th>
                                            <th class="uppercase">Details</th>
                                            <th class="uppercase">Infomation</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach ($process as $key => $value5) { $i++;?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?= $value5['project_no']; ?></td>
                                                <td>
                                                        <?php if ($value5['type_of_project'] == 'MySpot Buy') { ?>
                                                            <span class="label bg-yellow-casablanca"> <?php echo $value5['type_of_project']; ?> </span>
                                                        <?php } elseif ($value5['type_of_project'] == 'Guide Buying') { ?>
                                                            <span class="label bg-red-sunglo"> <?php echo $value5['type_of_project']; ?> </span>
                                                        <?php } elseif ($value5['type_of_project'] == 'Sale Lead') { ?>
                                                            <span class="label bg-blue-steel"> <?php echo $value5['type_of_project']; ?> </span>
                                                        <?php } else {

                                                            } ?>

                                                 </td>

                                                
                                                <td>
                                                    <ul>
                                                        <li><b>Title</b> : <?= $value5['title']; ?></li>
                                                        <li><b>Description</b> : <?= $value5['description']; ?></li>
                                                        <li><b>Due Date</b> : <?= $value5['due_date']; ?></li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <table class="table table-bordered" >
                                                        <tr style="background-color: #ecf6ff;">
                                                            <th class="uppercase">Buyer Name</th>
                                                            <th class="uppercase">Status</th>
                                                            <th class="uppercase">Action</th>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <?php echo $buyer = $value5['buyer']; ?>
                                                            </td>
                                                            <?php foreach ($value5['sellers'] as $key => $value6) { ?>
                                                                <td><?php echo $status = $value6['status']; ?></td>
                                                            <?php } ?>
                                                            <td>
               
                                                                    <?php if ($value5['type_of_project'] == 'Guide Buying') { ?>

                                                                        <?php if ($value6['status'] == 'Revise') { ?>


                                                                        <?php } else { ?>

                                                                            <div class="margin-bottom-5">

                                                                                    <div class="btn-group">
                                                                                        <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                            <i class="fa fa-angle-down"></i>
                                                                                        </a>
                                                                                        <ul class="dropdown-menu">

                                            
                                                                                            <li>
                                                                                                <?= Html::a('<b>'.$value6['quotation_no'].'</b>', [
                                                                                                'html/guide-quotation-html',
                                                                                                'project'=>(string)$value5['_id'],
                                                                                                'seller'=>$value6['seller'],
                                                                                                'buyer'=>$value5['buyer'],
                                                                                                ],['target'=>'_blank']) ?>
                                                                                            </li>
                                                                                            <?php if (empty($value6['revise'])) { ?>
                                                                                               
                                                                                            <?php } else { ?>

                                                                                            <li class="dropdown-submenu">
                                                                                                <a href="javascript:;"> REVISE </a>
                                                                                                    <ul class="dropdown-menu" style="">
                                                                                                
                                                                                                <?php foreach ($value6['revise'] as $key => $revise) { ?>
                                                                                                        <li>
                                                                                                            <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                                            'revise/guide-quotation-revise',
                                                                                                            'project'=>(string)$value5['_id'],
                                                                                                            'seller'=>$value6['seller'],
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

                                                                        <div class="margin-bottom-5">

                                                                            <?php if ($value6['status'] == 'Revise') { ?>

                                                                                <?= Html::a($value6['quotation_no'],['quote/guide-revise',
                                                                                    'project'=>(string)$value5['_id'],
                                                                                    'seller'=>$value6['seller'],
                                                                                    'buyer'=>$value5['buyer'],
                                                                                    ],['class' => 'btn grey-mint btn']) ?>


                                                                               
                                                                            <?php } else { ?>

                                                                                    <?= Html::a('Revise',FALSE, ['value'=>Url::to([
                                                                                    'quote/guide-modal-revise',
                                                                                    'project'=>(string)$value5['_id'],
                                                                                    'seller'=>$value6['seller'],
                                                                                    'buyer'=>$value5['buyer'],
                                                                                    ]),'class' => 'btn blue btn-sm btn-outline modal_revise','id'=>'revise']) ?>

                                                                            <?php } ?>


                                                                        

                                                                        </div>


                                                                    <?php } elseif ($value5['type_of_project'] == 'Sale Lead') { ?>

                                                                        <?php if ($value6['status'] == 'Revise') { ?>


                                                                        <?php } else { ?>


                                                                        <div class="margin-bottom-5">

                                                                                <div class="btn-group">
                                                                                    <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                        <i class="fa fa-angle-down"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu">

                                                                                            <li>
                                                                                                <?= Html::a('<b>'.$value6['quotation_no'].'</b>', [
                                                                                                'html/sale-quotation-html',
                                                                                                'project'=>(string)$value5['_id'],
                                                                                                'seller'=>$value6['seller'],
                                                                                                'buyer'=>$value5['buyer'],
                                                                                                ],['target'=>'_blank']) ?>
                                                                                            </li>
                                                                                            <?php if (empty($value6['revise'])) { ?>
                                                                                               
                                                                                            <?php } else { ?>

                                                                                            <li class="dropdown-submenu">
                                                                                                <a href="javascript:;"> REVISE </a>
                                                                                                    <ul class="dropdown-menu" style="">
                                                                                                
                                                                                                <?php foreach ($value6['revise'] as $key => $revise) { ?>
                                                                                                        <li>
                                                                                                            <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                                            'revise/sale-quotation-revise',
                                                                                                            'project'=>(string)$value5['_id'],
                                                                                                            'seller'=>$value6['seller'],
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

                                                                        <div class="margin-bottom-5">

                                                                            <?php if ($value6['status'] == 'Revise') { ?>

                                                                                <?= Html::a($value6['quotation_no'],['quote/sale-revise',
                                                                                    'project'=>(string)$value5['_id'],
                                                                                    'seller'=>$value6['seller']
                                                                                    ],['class' => 'btn grey-mint btn']) ?>

                                                                               
                                                                            <?php } else { ?>

                                                                                     <?= Html::a('Revise',FALSE, ['value'=>Url::to([
                                                                                     'quote/sale-modal-revise',
                                                                                     'project'=>(string)$value5['_id'],
                                                                                     'seller'=>$value6['seller'],
                                                                                     'buyer'=>$value5['buyer'],
                                                                                     ]),'class' => 'btn blue btn-sm btn-outline modal_revise','id'=>'revise']) ?>
                                                    


                                                                            <?php } ?>

                                                                        </div>


                                                                    <?php } elseif ($value5['type_of_project'] == 'MySpot Buy') { ?>

                                                                        <?php if ($value6['status'] == 'Revise') { ?>


                                                                        <?php } else { ?>


                                                                        <div class="margin-bottom-5">

                                                                                <div class="btn-group">
                                                                                    <a class="btn blue btn-sm btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                                        <i class="fa fa-angle-down"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu">

                                                                                            <li>
                                                                                                <?= Html::a('<b>'.$value6['quotation_no'].'</b>', [
                                                                                                'html/spot-quotation-html',
                                                                                                'project'=>(string)$value5['_id'],
                                                                                                'seller'=>$value6['seller'],
                                                                                                'buyer'=>$value5['buyer'],
                                                                                                ],['target'=>'_blank']) ?>
                                                                                            </li>
                                                                                            <?php if (empty($value6['revise'])) { ?>
                                                                                               
                                                                                            <?php } else { ?>

                                                                                            <li class="dropdown-submenu">
                                                                                                <a href="javascript:;"> REVISE </a>
                                                                                                    <ul class="dropdown-menu" style="">
                                                                                                
                                                                                                <?php foreach ($value6['revise'] as $key => $revise) { ?>
                                                                                                        <li>
                                                                                                            <?= Html::a('<b>'.$revise['quotation_no'].'</b>', [
                                                                                                            'revise/spot-quotation-revise',
                                                                                                            'project'=>(string)$value5['_id'],
                                                                                                            'seller'=>$value6['seller'],
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

                                                                        <div class="margin-bottom-5">

                                                                            <?php if ($value6['status'] == 'Revise') { ?>

                                                                                <?= Html::a($value6['quotation_no'],['quote/spot-revise',
                                                                                    'project'=>(string)$value5['_id'],
                                                                                    'seller'=>$value6['seller']
                                                                                    ],['class' => 'btn grey-mint btn']) ?>

                                                                               
                                                                            <?php } else { ?>

                                                                                     <?= Html::a('Revise',FALSE, ['value'=>Url::to([
                                                                                     'quote/spot-modal-revise',
                                                                                     'project'=>(string)$value5['_id'],
                                                                                     'seller'=>$value6['seller'],
                                                                                     'buyer'=>$value5['buyer'],
                                                                                     ]),'class' => 'btn blue btn-sm btn-outline modal_revise','id'=>'revise']) ?>
                                                    


                                                                            <?php } ?>

                                                                        </div>

                                                                    <?php } ?>












                                                                    <div class="margin-bottom-5">
                                                                    <?= Html::a('Message', ['message/message',
                                                                    'project'=>(string)$value5['_id'],
                                                                    'to'=>$value5['buyer']
                                                                    ],['class'=>'btn blue btn-sm btn-outline']) ?>
                                                                    </div>

                                                            </td>

                                                        </tr>
                                                    </table>
                                                </td>


                            
                                               
                                            </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        <div class="tab-pane" id="tab_15_2">


                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr style="background-color: #ecf6ff;">
                                <th class="uppercase">No</th>
                                <th class="uppercase">Project No</th>
                                <th class="uppercase">Details</th>
                                <th class="uppercase">Information</th>
      
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i=0; foreach ($history as $key => $value) { $i++; ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $value['project_no']; ?></td>
                                <td>
                                    <ul>
                                        <li><b>Title</b> : <?= $value['title']; ?></li>
                                        <li><b>Description</b> : <?= $value['description']; ?></li>
                                        <li><b>Due Date</b> : <?= $value['due_date']; ?></li>
                                    </ul>      
                                </td>
                                <td>
                                    <table class="table table-bordered table-hover" >
                                        <tr style="background-color: #ecf6ff;">
                                            <th class="uppercase">Seller Name</th>
                                            <th class="uppercase">Action</th>
                                        </tr>
                                        <?php foreach ($value['sellers'] as $key => $value2) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $value2['seller']; ?>
                                            </td>
                                            <td>
                                                
                                                <?php foreach ($value2['history'] as $key => $value3) { ?>

                                                    <?php if ($value['type_of_project'] == 'Guide Buying') { ?>

                                                        <?php if (!empty($value3['quotation_no'])) { ?>

                                                            <div class="margin-bottom-5">

                                                                    <div class="btn-group">
                                                                        <a class="btn blue btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <?= Html::a('PDF', ['/pdf/'.$value['project_no'].'/'.$value['quotation_file']],['target'=>'_blank']) ?>
                                     
                                                                            </li>
                                                                            <li>
                                                                                <?= Html::a('<b>'.$value2['quotation_no'].'</b>', ['html/guide-quotation-html',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller']],
                                                                                ['target'=>'_blank']) ?>
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


                                                        <?php }  ?>

                                                    <?php } elseif ($value['type_of_project'] == 'Sale Lead') { ?>

                                                        <?php if (!empty($value3['quotation_no'])) { ?>

                                                            <div class="margin-bottom-5">

                                                                    <div class="btn-group">
                                                                        <a class="btn blue btn-outline dropdown-toggle" data-toggle="dropdown" href="javascript:;" aria-expanded="false"> Quotation
                                                                            <i class="fa fa-angle-down"></i>
                                                                        </a>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <?= Html::a('PDF', ['/pdf/'.$value['project_no'].'/'.$value['quotation_file']],['target'=>'_blank']) ?>
                                     
                                                                            </li>
                                                                            <li>
                                                                                <?= Html::a('<b>'.$value2['quotation_no'].'</b>', ['html/sale-quotation-html',
                                                                                'project'=>(string)$value['_id'],
                                                                                'seller'=>$value2['seller']],
                                                                                ['target'=>'_blank']) ?>
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


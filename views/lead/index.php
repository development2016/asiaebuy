<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProjectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lead';
$this->params['breadcrumbs'][] = $this->title;


$script = <<< JS
$(document).ready(function(){

    $('.respond').click(function(){
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));

    });




        var table = $('#guide-buying');

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
              //  { extend: 'print', className: 'btn default' },
              //  { extend: 'pdf', className: 'btn default' },
               // { extend: 'csv', className: 'btn default' }
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



        var table = $('#progress');

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
            //    { extend: 'print', className: 'btn default' },
            //    { extend: 'pdf', className: 'btn default' },
            //    { extend: 'csv', className: 'btn default' }
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


        var table = $('#sale-lead');

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
          //      { extend: 'print', className: 'btn default' },
          //      { extend: 'pdf', className: 'btn default' },
           //     { extend: 'csv', className: 'btn default' }
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

        var table = $('#spot');

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
          //      { extend: 'print', className: 'btn default' },
          //      { extend: 'pdf', className: 'btn default' },
           //     { extend: 'csv', className: 'btn default' }
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
    <li class="active">Lead</li>
</ol>
</div>

<?php if(Yii::$app->session->hasFlash('respond')):?>
  <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert"></button>
       <?php echo  Yii::$app->session->getFlash('respond'); ?>
  </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">

    <div class="portlet light bordered">
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-bubbles font-dark hide"></i>
                <span class="caption-subject font-dark bold uppercase">PROGRESS</span>
            </div>

        </div>
        <div class="portlet-body">
            
            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="progress" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="uppercase">No</th>
                        <th class="uppercase">Project No</th>
                        <th class="none"></th>
                        <th class="uppercase">Details</th>
                        <th class="uppercase">Buyer</th>
                        <th class="uppercase">Status</th>
                        <th class="uppercase">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach ($process as $key => $value5) { $i++;?>
                        <tr>
                            <td><?= $i; ?></td>
                            <td><b><?= $value5['project_no']; ?></b></td>
                            <td>
                                <?php if ($value5['type_of_project'] == 'Guide Buying') { ?>

                                    <?php foreach ($value5['sellers'] as $keyseller => $sellers) { ?>
                                        <?php foreach ($sellers['items'] as $keyitem => $items) { ?>
                                            <table class="table table-striped table-bordered">
                                                <tr>
                                                    <th style="background-color: #ecf6ff;">Item Name</th>
                                                    <td><?php echo $items['item_name']; ?></td>
                                                    <th style="background-color: #ecf6ff;">Brand</th>
                                                    <td><?php echo $items['brand']; ?></td>
                                                    <th style="background-color: #ecf6ff;">Model</th>
                                                    <td><?php echo $items['model']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="background-color: #ecf6ff;">Specification </th>
                                                    <td><?php echo $items['specification']; ?></td>
                                                    <th style="background-color: #ecf6ff;">Description </th>
                                                    <td colspan="3"><?php echo $items['description']; ?></td>
                                                </tr>
                                            </table>
                                            
                                        <?php } ?>

                                    <?php } ?>


                                <?php } elseif ($value5['type_of_project'] == 'Sale Lead') { ?>
                                    No Item Added
                                <?php } elseif ($value5['type_of_project'] == 'MySpot Buy') { ?>
                                    No Item Added
                                <?php } ?>

                            </td>
                            <td>
                                <ul>
                                    <li><b>Title</b> : <?= $value5['title']; ?></li>
                                    <li><b>Description</b> : <?= $value5['description']; ?></li>
                                    <li><b>Due Date</b> : <?= $value5['due_date']; ?></li>
                                </ul>
                            </td>
                            <td><?= $value5['buyer']; ?></td>
                            <?php foreach ($value5['sellers'] as $key => $value6) { ?>
                                <td><?php echo $status = $value6['status']; ?></td>
                            <?php } ?>
                            <td>
    
                                    <?php if ($status == "Waiting Quotation") { ?>

                                        <?php if ($value5['type_of_project'] == 'Guide Buying') { ?>

                                            <div class="margin-bottom-5">
                                            <?= Html::a('Quote', ['lead/guide-quotation',
                                                'project'=>(string)$value5['_id'],
                                                'seller'=>(string)$value6['seller'],
                                                'buyer'=>(string)$value5['buyer'],
                                                ],['class'=>'btn btn-sm blue btn-outline','title'=>'Quote']) ?>
                                            </div>

                                        <?php } elseif ($value5['type_of_project'] == 'Sale Lead') { ?>

                                            <div class="margin-bottom-5">
                                            <?= Html::a('Quote', ['lead/sale-quotation',
                                                'project'=>(string)$value5['_id'],
                                                'seller'=>(string)$value6['seller'],
                                                'buyer'=>(string)$value5['buyer'],
                                                ],['class'=>'btn btn-sm blue btn-outline','title'=>'Quote']) ?>
                                            </div>

                                        <?php } elseif ($value5['type_of_project'] == 'MySpot Buy') { ?>

                                            <div class="margin-bottom-5">
                                            <?= Html::a('Quote', ['lead/spot-quotation',
                                                'project'=>(string)$value5['_id'],
                                                'seller'=>(string)$value6['seller'],
                                                'buyer'=>(string)$value5['buyer'],
                                                ],['class'=>'btn btn-sm blue btn-outline','title'=>'Quote']) ?>
                                            </div>

                                        <?php } ?>

                                    <?php } ?>


                            </td>
                            
                           
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
            


        </div>
        </div>
    </div>

    <div class="col-md-6">

        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">GUIDE BUYING</span>
                </div>

            </div>
            <div class="portlet-body">
           
                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="guide-buying" cellspacing="0" width="100%">
                    <thead>
                            <tr>
                                <th class="uppercase">No</th>
                                <th class="uppercase">Project No</th>
                                <th class="none"></th>
                                <th class="uppercase">Details</th>
                                <th class="uppercase">Buyer</th>
                                <th class="uppercase">Status</th>
                                <th class="uppercase">Action</th>

                            </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach ($guide as $key => $value2) { $i++; ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><b><?= $value2['project_no']; ?></b></td>
                                <td>
                                    
                                    <?php foreach ($value2['sellers'] as $keyseller => $sellers) { ?>
                                        <?php foreach ($sellers['items'] as $keyitem => $items) { ?>
                                            <table class="table table-striped table-bordered">
                                                <tr>
                                                    <th style="background-color: #ecf6ff;">Item Name</th>
                                                    <td><?php echo $items['item_name']; ?></td>
                                                    <th style="background-color: #ecf6ff;">Brand</th>
                                                    <td><?php echo $items['brand']; ?></td>
                                                    <th style="background-color: #ecf6ff;">Model</th>
                                                    <td><?php echo $items['model']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="background-color: #ecf6ff;">Specification </th>
                                                    <td><?php echo $items['specification']; ?></td>
                                                    <th style="background-color: #ecf6ff;">Description </th>
                                                    <td colspan="3"><?php echo $items['description']; ?></td>


                                                </tr>
                                            </table>
                                            
                                        <?php } ?>

                                    <?php } ?>
                  
                                </td>
                                <td>
                                    <ul>
                                        <li><b>Title</b> : <?= $value2['title']; ?></li>
                                        <li><b>Description</b> : <?= $value2['description']; ?></li>
                                        <li><b>Due Date</b> : <?= $value2['due_date']; ?></li>
                                    </ul>
                                </td>
                                <td><?= $value2['buyer']; ?></td>
                                <?php foreach ($value2['sellers'] as $key => $value4) { ?>
                                    <td><?php echo $value4['status']; ?></td>
                                <?php } ?>
                                <td>
                                    <div class="margin-bottom-5">
                                    <?= Html::a('Quote', ['lead/guide-quotation',
                                        'project'=>(string)$value2['_id'],
                                        'seller'=>(string)$value4['seller'],
                                        'buyer'=>(string)$value2['buyer'],
                                        ],['class'=>'btn btn-sm blue btn-outline','title'=>'Quote']) ?>
                                    </div>
                                    <div class="margin-bottom-5">
                                        <a href="#" class="btn btn-sm blue btn-outline">Decline</a></li>
                                    
                                    </div>
                                </td>
                               
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
                
            </div>
        </div>

        
        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">SALES LEAD</span>
                </div>

            </div>
            <div class="portlet-body">
            
                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="sale-lead" cellspacing="0" width="100%">
                    <thead>
                            <tr>
                                <th class="uppercase">No</th>
                                <th class="uppercase">Project No</th>
                                <th class="uppercase">Details</th>
                                <th class="uppercase">Buyer</th>
                                <th class="uppercase">Action</th>

                            </tr>
                    </thead>
                    <tbody>
                        <?php $i =0; foreach ($sales as $key => $value3) { $i++;?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><b><?= $value3['project_no']; ?></b></td>
                                <td>
                                    <ul>
                                        <li><b>Title</b> : <?= $value3['title']; ?></li>
                                        <li><b>Description</b> : <?= $value3['description']; ?></li>
                                        <li><b>Due Date</b> : <?= $value3['due_date']; ?></li>
                                    </ul>
                                </td>
                                <td><?= $value3['buyer']; ?></td>
                                <td>

                                    <div class="margin-bottom-5">
                                    <?= Html::a('Respond',FALSE, ['value'=>Url::to(['lead/respond','project'=>(string)$value3['_id']]),'class' => 'btn blue btn-outline btn-sm respond','title' => 'Respond',]) ?>

                                    </div>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
               
        VIEW ALL & MATCHING ITEM

        </div>

    </div>


        <div class="portlet light bordered">
            <div class="portlet-title tabbable-line">
                <div class="caption">
                    <i class="icon-bubbles font-dark hide"></i>
                    <span class="caption-subject font-dark bold uppercase">SPOT BUY</span>
                </div>

            </div>
            <div class="portlet-body">
            
                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="spot" cellspacing="0" width="100%">
                    <thead>
                            <tr>
                                <th class="uppercase">No</th>
                                <th class="uppercase">Project No</th>
                                <th class="uppercase">Details</th>
                                <th class="uppercase">Buyer</th>
                                <th class="uppercase">Action</th>

                            </tr>
                    </thead>
                    <tbody>
                        <?php $i =0; foreach ($spot as $key_spot => $value_spot) { $i++;?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><b><?= $value_spot['project_no']; ?></b></td>
                                <td>
                                    <ul>
                                        <li><b>Title</b> : <?= $value_spot['title']; ?></li>
                                        <li><b>Description</b> : <?= $value_spot['description']; ?></li>
                                        <li><b>Due Date</b> : <?= $value_spot['due_date']; ?></li>
                                    </ul>
                                </td>
                                <td><?= $value_spot['buyer']; ?></td>
                                <td>

                                    <div class="margin-bottom-5">
                                    <?= Html::a('Respond',FALSE, ['value'=>Url::to(['lead/respond','project'=>(string)$value_spot['_id']]),'class' => 'btn blue btn-outline btn-sm respond','title' => 'Respond',]) ?>

                                    </div>
                                </td>
                            </tr>

                        <?php } ?>
                    </tbody>
                </table>
            

        </div>

    </div>






    </div>


</div>
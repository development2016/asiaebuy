<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Oswald:400,300,700',
        'https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'metronic/assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'metronic/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'metronic/assets/global/plugins/bootstrap/css/bootstrap.min.css',
        'metronic/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
        'metronic/assets/global/plugins/morris/morris.css',
        'metronic/assets/global/plugins/fullcalendar/fullcalendar.min.css',
        'metronic/assets/global/plugins/jqvmap/jqvmap/jqvmap.css',
        'metronic/assets/global/css/components.min.css',
        'metronic/assets/global/css/plugins.min.css',
        'metronic/assets/pages/css/faq.min.css',
        'metronic/assets/layouts/layout5/css/layout.min.css',
        'metronic/assets/layouts/layout5/css/custom.min.css',
        'metronic/assets/pages/css/search.min.css',
        'metronic/assets/pages/css/about.min.css',
        'metronic/assets/pages/css/invoice-2.min.css',
        'css/main.css',
        'metronic/assets/global/plugins/datatables/datatables.min.css',
        'metronic/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css',
        'metronic/assets/global/plugins/ladda/ladda-themeless.min.css'



    ];
    public $js = [
        'metronic/assets/global/plugins/bootstrap/js/bootstrap.min.js',
        'metronic/assets/global/plugins/js.cookie.min.js',
        'metronic/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'metronic/assets/global/plugins/jquery.blockui.min.js',
        'metronic/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'metronic/assets/global/plugins/moment.min.js',
        'metronic/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        'metronic/assets/global/plugins/morris/morris.min.js',
        'metronic/assets/global/plugins/morris/raphael-min.js',
        'metronic/assets/global/plugins/counterup/jquery.waypoints.min.js',
        'metronic/assets/global/plugins/counterup/jquery.counterup.min.js',
        'metronic/assets/global/plugins/amcharts/amcharts/amcharts.js',
        'metronic/assets/global/plugins/amcharts/amcharts/serial.js',
        'metronic/assets/global/plugins/amcharts/amcharts/pie.js',
        'metronic/assets/global/plugins/amcharts/amcharts/radar.js',
        'metronic/assets/global/plugins/amcharts/amcharts/themes/light.js',
        'metronic/assets/global/plugins/amcharts/amcharts/themes/patterns.js',
        'metronic/assets/global/plugins/amcharts/amcharts/themes/chalk.js',
        'metronic/assets/global/plugins/amcharts/ammap/ammap.js',
        'metronic/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js',
        'metronic/assets/global/plugins/amcharts/amstockcharts/amstock.js',
        'metronic/assets/global/plugins/fullcalendar/fullcalendar.min.js',
        'metronic/assets/global/plugins/horizontal-timeline/horizontal-timeline.js',
        'metronic/assets/global/plugins/flot/jquery.flot.min.js',
        'metronic/assets/global/plugins/flot/jquery.flot.resize.min.js',
        'metronic/assets/global/plugins/flot/jquery.flot.categories.min.js',
        'metronic/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
        'metronic/assets/global/plugins/jquery.sparkline.min.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js',
        'metronic/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js',
        'metronic/assets/global/plugins/bootstrap-selectsplitter/bootstrap-selectsplitter.min.js',
        'metronic/assets/global/scripts/datatable.js',
        'metronic/assets/global/plugins/datatables/datatables.min.js',
        'metronic/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js',
        'metronic/assets/global/scripts/app.js',
        'metronic/assets/pages/scripts/dashboard.js',
        'metronic/assets/layouts/layout5/scripts/layout.js',
        'metronic/assets/layouts/global/scripts/quick-sidebar.min.js',
        'metronic/assets/layouts/global/scripts/quick-nav.min.js',
        'metronic/assets/global/plugins/bootbox/bootbox.min.js',
        'metronic/assets/pages/scripts/ui-bootbox.min.js',
        'metronic/assets/pages/scripts/components-bootstrap-select-splitter.min.js',
        'metronic/assets/global/plugins/jquery.pulsate.min.js',
        'metronic/assets/global/plugins/jquery-bootpag/jquery.bootpag.min.js',
        'metronic/assets/global/plugins/holder.js',
        'metronic/assets/pages/scripts/ui-general.min.js',
        'http://cdn.embedly.com/widgets/platform.js',
        'metronic/assets/global/plugins/ladda/spin.min.js',
        'metronic/assets/global/plugins/ladda/ladda.min.js',
        'metronic/assets/pages/scripts/ui-buttons.min.js',


    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}

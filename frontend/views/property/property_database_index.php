<?php

use app\components\helpers\MyHelper;
use app\models\PropertyArticles;
use frontend\components\MyComponent;
use kartik\date\DatePicker;
use kartik\file\FileInput;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PropertySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Property Database';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar' => [
            [
                'content' => "
                    <div class='row'>
                        <div class='col-sm-3'>
                        <label for='reporting_period'> Reporting Period</label>
                        " .
                    DatePicker::widget([
                        'id' => 'export_reporting_period',
                        'name' => 'export_reporting_period',
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'autoclose' => true,
                            'minViewMode' => 'months'
                        ]
                    ])
                    . "</div>
                        <div class='col-sm-3'>   
                            <button id='export' type='button' class='btn btn-success' style='margin:1rem;margin-top:25px'><i class='glyphicon glyphicon-export'></i>Export</button>
                        </div>
                </div>",
                'options' => [
                    'class' => 'btn-group mr-2', 'style' => 'margin-right:20px'
                ]
            ]
        ],
        'panel' => [
            'type' => GRIDVIEW::TYPE_PRIMARY,
            'heading' => 'Property Database',
        ],

        'export' => false,
        'columns' => [

            'pc_num',
            'ptr_number',
            'ptr_date',
            'type',
            'derecognition_num',
            'derecognition_date',
            'property_number',
            'date_acquired',
            'serial_number',
            'article',
            'description',
            'acquisition_amount',
            'unit_of_measure',
            'useful_life',
            'strt_mnth',
            'lst_mth',
            'new_last_month',
            'sec_lst_mth',
            'par_number',
            'par_date',
            'rcv_by',
            'act_usr',
            'isd_by',
            'office_name',
            'division',
            'location',
            'isCrntUsr',
            'isUnserviceable',
            'is_current_user',
            'uacs',
            'general_ledger',
            'depreciation_account_title',
            'depreciation_object_code',
        ],
    ]); ?>


</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 2rem;
    }
</style>
<script>
    $(document).ready(() => {
        $("#book").change(() => {
            $('#tojev').attr('href', $('#tojev').attr('href') + '&bookId=' + $("#book").val())
        })

        $('#export').click(function(e) {
            e.preventDefault();
            $.ajax({
                container: "#employee",
                type: 'POST',
                url: window.location.pathname + '?r=report/export-property-database',
                data: {
                    reporting_period: $('#export_reporting_period').val()
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    window.open(res)
                    // $.ajax({
                    //     type: 'POST',
                    //     url: window.location.pathname + '?r=site/clear-exports',
                    //     success: function(response) {
                    //         console.log(response)
                    //     },
                    //     error: function(jqXHR, textStatus, errorThrown) {
                    //         console.log('Error deleting file: ' + errorThrown);
                    //     }
                    // });
                }

            })
        })
    })
</script>
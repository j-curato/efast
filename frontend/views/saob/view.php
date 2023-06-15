<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "SAOB";
$this->params['breadcrumbs'][] = $this->title;

$from_reporting_period = '';
$to_reporting_period = '';
$book_id = '';
$mfo_id = '';
$document_recieve_id = '';

if (!empty($model->id)) {
    $from_reporting_period = $model->from_reporting_period;
    $to_reporting_period = $model->to_reporting_period;
    $book_id = $model->book_id;
    $mfo_id = $model->mfo_pap_code_id;
    $document_recieve_id = $model->document_recieve_id;
}

?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">





    <div id='con'>
        <table id="summary_table">
            <thead>
                <tr class="danger">
                    <th colspan='10' style="background-color:  #80ccff;">Consolidated</th>
                </tr>
                <tr>

                    <th rowspan="2"> MFO/PAP </th>
                    <th rowspan="2"> Document Receive</th>
                    <th rowspan="2">Prev. Allotment</th>
                    <th rowspan="2">Current Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>

                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody>

            </tbody>

        </table>
        <table id="summary_per_major_account" style="margin-top:5rem ;">
            <thead>
                <tr class="danger">
                    <th colspan='9' style="background-color:  #80ccff;">Summary Per Allotment Class</th>
                </tr>
                <tr>

                    <th rowspan="2"> Allotment Class </th>
                    <th rowspan="2"> Document Recieve</th>
                    <th rowspan="2">Prev. Allotment</th>
                    <th rowspan="2">Current Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>

                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody></tbody>
        </table>

        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <tr class="danger">
                    <th colspan='10' style="background-color:  #80ccff;">Detailed</th>
                </tr>
                <tr>

                    <th rowspan="2">Project / Program</th>
                    <th rowspan="2">Prev. Allotment</th>
                    <th rowspan="2">Current Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>
                    <th rowspan="2"> MFO/PAP </th>
                    <th rowspan="2"> Document Recieve</th>
                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    #summary_table {
        margin-top: 30px;
    }

    /* #con {
        display: none;
    } */

    .amount {
        text-align: right;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/saobJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        const res = JSON.parse('<?php echo $json_data ?>');
        // var detailed = res.detailed
        addData(res.result, res.allotments)
        addToSummaryTable(res.conso_saob)
        summaryPerMajorAccount(res.conso_per_major)

    })
</script>
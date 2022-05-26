<?php

use app\models\AdvancesEntries;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "DV Time Monitoring Summary";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index">
<div class="container">

    <div class="row">
        <form id="filter">
            <div class="col-sm-3">
                <label for="from_reporting_period">From Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'year',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy',
                        'minViewMode' => 'years'
                    ]
                ]);

                ?>
            </div>

            <div class="col-sm-3">
                <button class="btn btn-success" type="submit">Generate</button>
            </div>
        </form>
    </div>
    <table id="data_table">
        <thead>
            <tr>
                <th colspan="4">
                    Process Cycle Time Monitoring Report Summary for Processing of Claims
                </th>
            </tr>
            <tr>
                <th>Month</th>
                <th>Total Claims Received/Processed</th>
                <th>Total Claims Processed within Timeline of 3 working days</th>
                <th>% Accomplishment</th>
            </tr>
        </thead>
        <tbody>


        </tbody>
    </table>
    </div>


</div>
<style>
    .bold{
        font-weight: bold;
    }
    .container{
        background-color: white;
        padding: 3rem;
    }
    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
    }

    table {
        margin-top: 3rem;
        width: 100%;
    }

    .btn {
        margin-top: 25px;
    }

    .amount {
        text-align: right;
    }

    @media print {
        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }

        th,
        td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        .select2-container--default .select2-selection--single {
            border: none !important;
            text-decoration: underline;
        }
    }
</style>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<script>
    function signatory() {
        $('.asignatory').select2({
            data: asignatory,
            placeholder: "Select ",

        })
    }
    $(document).ready(() => {

        $("#filter").submit(function(e) {
            e.preventDefault()
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: $('#filter').serialize(),
                success: function(data) {
                    const res = JSON.parse(data)
                    console.log(res)
                    displayData(res)
                }

            })
        })
    });

    function displayData(data) {
        $('#data_table tbody').html('')
        let grand_total_dv = 0;
        let grand_total_dv_within = 0;
        $.each(data, function(key, val) {
            const reporting_period = val.reporting_period
            const total_dv = parseInt(val.total_dv)
            const total_dv_within = parseInt(val.dv_within)
            grand_total_dv += total_dv
            grand_total_dv_within += total_dv_within
            const average = (total_dv_within / total_dv) * 100
            const data_row = `
            <tr>
                <td>${reporting_period}</td>
                <td>${total_dv}</td>
                <td>${total_dv_within}</td>
                <td >${average.toFixed(2)}%</td>
            </tr>
            `
            $('#data_table tbody').append(data_row)
        })
        const grand_average = (grand_total_dv_within / grand_total_dv) * 100
        const total_row = `<tr>
        <td class='bold'>Total</td>
        <td class='bold'>${grand_total_dv}</td>
        <td class='bold'>${grand_total_dv_within}</td>
        <td class='bold'>${grand_average.toFixed(2)}%</td>
        </tr>`
        $('#data_table  tbody').append(total_row)
    }
</script>
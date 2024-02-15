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

$this->title = "DV Time Monitoring";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;padding:2rem;">
    <div class="row">
        <div class="col-sm-3">
            <label for="from_reporting_period">From Reporting Period</label>
            <?php

            echo DatePicker::widget([
                'id' => 'year',
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
            <button class="btn btn-success" type="submit" id="generate">Generate</button>
        </div>
    </div>
    <table id="data_table">
        <thead>
            <!-- <tr>
                <th rowspan="3">ReportingPeriod</th>
            </tr>
            <tr>
                <td colspan="4">ADN</td>
            </tr>
            <tr>
                <td>total</td>
                <td>q</td>
                <td>w</td>
                <td>e</td>

            </tr> -->
        </thead>
        <tbody>


        </tbody>
    </table>


    <!-- <input type="text" id="d1" value="2022-05-23"><br>
    <input type="text" id="d2" value="2022-05-31">

    <p>Working days count: <span id="dif"></span></p>
    <button id="calc">Calc</button>

    <p>
        Now it shows 5 days, but I need for example add holidays
        3 and 5 May (2016-05-03 and 2016-05-05) so the result will be 3 working days
    </p> -->

</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
    }

    table {
        margin-top: 3rem;
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

$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/select2.min.css", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<script>
    let head_row = '<tr>'
    let head_row_items = '<tr>'
    let data_length = 0

    async function displayPeriodRows(reporting_periods) {

        console.log(reporting_periods)
        await $.each(reporting_periods, function(key, val) {
            let period_row = `<tr><td>${val}</td>`
            for (let i = 0; i < data_length * 4; i++) {
                period_row += `<td></td>`
            }
            period_row += '</tr>'
            $('#data_table tbody').append(period_row)
        })
    }
    async function displayData(result) {
        const data = result.data
        $('#data_table thead').empty()
        $('#data_table tbody').empty()
        $("#data_table thead:not(:first)").remove();
        $('#data_table thead').append(` <tr><th rowspan="3">ReportingPeriod</th></tr>`)
        let reporting_periods = []
        $.each(result.reporting_periods, function(key, val) {
            reporting_periods.push(val.reporting_period)
        })
        data_length = Object.keys(data).length
        await displayPeriodRows(reporting_periods)
        // console.log(data)

        let col_number = 1
        $.each(data, function(key, val) {
            // console.log(col_number)
            head_row += `<td colspan='4'>${key.toUpperCase()}</td>`
            head_row_items += `
                        <td>Total</td>
                        <td>at PO</td>
                        <td>at RO</td>
                        <td>at Coa</td>`
            $.each(val, function(key2, val2) {
                const row_number = reporting_periods.indexOf(val2.reporting_period)
                const total = ''
                const at_po = val2.dv_at_po_count
                const at_ro = val2.dv_at_ro_count
                const at_coa = val2.dv_at_coa_count
                const total_col = (col_number * 4) - 3 + 1
                const at_po_col = (col_number * 4) - 2 + 1
                const at_ro_col = (col_number * 4) - 1 + 1
                const at_coa_col = (col_number * 4) + 1
                $("#data_table tbody").find(`td:nth-child(${total_col})`).eq(row_number).text(total)
                $("#data_table tbody").find(`td:nth-child(${at_po_col})`).eq(row_number).text(at_po)
                $("#data_table tbody").find(`td:nth-child(${at_ro_col})`).eq(row_number).text(at_ro)
                $("#data_table tbody").find(`td:nth-child(${at_coa_col})`).eq(row_number).text(at_coa)
            })
            col_number++

        })

        head_row += '</tr>'
        head_row_items += '</tr>'

        $('#data_table thead').append(head_row)
        $('#data_table thead').append(head_row_items)
    }
    $(document).ready(function() {
        $('#generate').on('click', function(e) {
            $('#data_table').empty()
            $('#data_table').append('<thead></thead>')
            $('#data_table').append('<tbody></tbody>')
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: {
                    year: $("#year").val()
                },
                success: function(data) {

                    const res = JSON.parse(data)
                    // console.log(res)
                    displayData(res)
                }
            })
        })


    })
</script>
<?php

use app\models\Books;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\helpers\Html;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Annex A";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">
    <form id='filter'>
        <div class="row">

            <div class="col-sm-2">
                <label for="to_reporting_period">To Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'to_reporting_period',
                    'name' => 'to_reporting_period',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'startView' => 'months',
                        'minViewMode' => 'months',
                        'format' => 'yyyy-mm'
                    ]
                ])
                ?>
            </div>

            <div class="col-sm-2">
                <button class="btn btn-success" id="generate" style="margin-top:23px">Generate</button>
            </div>
        </div>
    </form>


    <table id="annex_table">
        <thead>
            <tr>

                <th rowspan="3">Account Used</th>
                <th rowspan="3">Name of Accountable Officer</th>
                <th rowspan="3">Purpose</th>
                <th rowspan="3">Date Granted</th>
                <th rowspan="3">Unliquidated Amount</th>
                <th rowspan="3">Due Date for Liquidation</th>
                <th rowspan="3">Status of AO / Employee</th>
                <th rowspan="3">Age of Cash Advances</th>
            </tr>
            <tr>

                <th colspan="4">Availability of Documents</th>
            </tr>
            <tr>
                <th>with</th>
                <th>without</th>
                <th>Agency Officials</th>
                <th> Auditor</th>
            </tr>

        </thead>
        <tbody>

        </tbody>

    </table>
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);

?>
<style>
    .amount {
        text-align: right;
        padding: 8px;
    }

    table {
        margin-top: 20px
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    .header {
        border: none;
        font-weight: bold;

    }

    .t_head {
        text-align: center;
        font-weight: bold;
    }

    @media print {

        td,
        th {
            font-size: 10px;
            padding: 2px;
        }

        .amount {
            padding: 5px;
        }

        #filter {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>

<script>
    var target_date = ''
    const province_data = {
        'adn': 'Rosie R. Vellesco',
        'ads': 'Maria Prescylin C. Lademora',
        'sdn': 'Ferdinand R. Inres',
        'sds': 'Sarah P. Estrada',
        'pdi': 'Venus A. Custodio',
        'ro': 'RO',
    }
    $('#generate').click((e) => {
        e.preventDefault()
        $('#annex_table').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + "?r=report/annex-a",
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                target_date = res.target_date
                displayData(res.res)
                $('#dots5').hide()
                $('#annex_table').show()
                console.log(province_data['adn'])

            }
        })
    })

    function displayData(data) {
        $('#annex_table tbody').html('')
        var advance_type_keys = Object.keys(data)
        var grand_total_unliquidated = 0
        for (var advance_type_loop = 0; advance_type_loop < advance_type_keys.length; advance_type_loop++) {
            var advance_name = advance_type_keys[advance_type_loop]
            // var row = `<tr class='data_row'>
            //     <td colspan='12' style='font-weight:bold' >` + advance_name + `</td>

            //     </tr>`
            // $('#annex_table tbody').append(row)
            var total_amount = 0
            var total_liquidation = 0
            var total_unliquidated = 0
            var uacs_keys = Object.keys(data[advance_name])
            console.log(uacs_keys)
            for (var uacs_loop = 0; uacs_loop < uacs_keys.length; uacs_loop++) {
                var uacs = uacs_keys[uacs_loop]
                var q = uacs + '_total_amount'
                var row = `<tr class='data_row'>
                <td colspan='4' style='font-weight:bold;text-align:center;' >` + uacs + ' - ' + data[advance_name][uacs][0]['account_title'] + `</td>
                <td id='${q}' class='amount'></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>`
                $('#annex_table tbody').append(row)
                var uacs_total_amount = 0
                var uacs_total_liquidation = 0
                var uacs_total_unliquidated = 0
                for (var i = 0; i < data[advance_name][uacs].length; i++) {

                    var account_name = data[advance_name][uacs][i]['account_title']
                    var object_code = data[advance_name][uacs][i]['object_code']
                    var check_number = data[advance_name][uacs][i]['check_number']
                    var check_date = data[advance_name][uacs][i]['check_date']
                    var particular = data[advance_name][uacs][i]['particular']
                    var advances_amount = data[advance_name][uacs][i]['advances_amount']
                    var total_liquidation_this_date = data[advance_name][uacs][i]['total_liquidation']
                    var unliquidated = parseFloat(data[advance_name][uacs][i]['unliquidated'])
                    var province = data[advance_name][uacs][i]['province']
                    var name = province_data[province.toLowerCase()]
                    var date1 = new Date(target_date);
                    var date2 = new Date(check_date);
                    var Difference_In_Time = date1.getTime() - date2.getTime();
                    var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
                    var age_of_advances = ''

                    if (Difference_In_Days > 0) {
                        age_of_advances = Difference_In_Days
                        if (Difference_In_Days < 30) {
                            age_of_advances = 'Less Than 30 Days'
                        } else if (Difference_In_Days > 30 && Difference_In_Days <= 60) {
                            age_of_advances = '31 to 60 Days'
                        } else if (Difference_In_Days > 60 && Difference_In_Days <= 365) {
                            age_of_advances = '61 to 365 Days'
                        } else if (Difference_In_Days > 365) {
                            age_of_advances = 'Over 1 Year'
                        }
                    }
                    var row = `<tr class='data_row'>
                        <td  >` + object_code + `</td>
                        <td  >` + name + `</td>
                            <td  >` + particular + `</td>
                            <td  >` + check_date + `</td>
                            <td class='amount' >` + thousands_separators(unliquidated) + `</td>
                            <td  >upon completion of the project</td>
                            <td  ></td>
                            <td  >` + age_of_advances + `</td>
                            <td  ></td>
                            <td  ></td>
                            <td  ></td>
                            <td  ></td>
                            </tr>`
                    $('#annex_table tbody').append(row)
                    total_amount += parseFloat(advances_amount)
                    total_liquidation += parseFloat(total_liquidation_this_date)
                    total_unliquidated += parseFloat(unliquidated)
                    uacs_total_amount += parseFloat(advances_amount)
                    uacs_total_liquidation += parseFloat(total_liquidation_this_date)
                    uacs_total_unliquidated += parseFloat(unliquidated)
                    grand_total_unliquidated += parseFloat(unliquidated)
                }
                $(`#${q}`).text(thousands_separators(uacs_total_unliquidated));
                // console.log(`${uacs}_total_amount`, uacs_total_amount)
                // var row = `<tr class='data_row'>
                //     <td  style='font-weight:bold;text-align:center;' colspan='3'>Total </td>
                //     <td  ></td>
                //     <td class='amount' >` + thousands_separators(uacs_total_unliquidated) + `</td>
                //     <td class='amount' ></td>
                //     <td class='amount' ></td>
                //     <td  ></td>
                //     <td  ></td>
                //     <td  ></td>
                //     <td  ></td>
                //     <td  ></td>

                //     </tr>`
                // $('#annex_table tbody').append(row)
            }


        }
        var row = `<tr class='data_row'>
                    <td  style='font-weight:bold;text-align:center;' colspan='3'>Total ` + advance_name + `</td>
                    <td  ></td>
                    <td class='amount' >` + thousands_separators(grand_total_unliquidated) + `</td>
                    <td class='amount' ></td>
                    <td class='amount' ></td>
                    <td  ></td>
                    <td  ></td>
                    <td  ></td>
                    <td  ></td>
                    <td  ></td>
             
                    </tr>`
        $('#annex_table tbody').append(row)


    }
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS

JS;
$this->registerJs($script);
?>
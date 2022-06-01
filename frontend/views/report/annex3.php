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

$this->title = "Annex 3";
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
            <div class="col-sm-3">
<label for="book">Book</label>
            <?php 
            echo Select2::widget([
                'name'=>'book',
                'data'=>ArrayHelper::map(Books::find()->asArray()->all(),'id','name'),
                'pluginOptions'=>[
                    'placeholder'=>'Select Book'
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
                <th class="noBorder" style='border:0;text-align:center' colspan="11">
                    <span>Annex 3 - Report of Aginf of Cash Advances</span><br>
                    <span>Schedule of Advances to Officers and Employees</span><br>
                    <span>Fund Cluster 01 - RA</span><br>
                    <span>As of </span>
                    <span id="r_period"></span>
                </th>

            </tr>
            <tr >
                <th class="noBorder" colspan='2' >Agency Name: </th>
                <th class="noBorder" colspan="4">Department of Trade and Industry - Region XIII CARAGA</th>
                <th class="noBorder">Book No:</th>
                <th class="noBorder" colspan="4"></th>
            </tr>
            <tr>
                <th class="noBorder" colspan='2'>Agency Code: </th>
                <th class="noBorder" colspan="4">220010300016</th>
                <th class="noBorder">Account Title:</th>
                <th class="noBorder" colspan="4"></th>
            </tr>
            <tr>
                <th class="noBorder" colspan="6"></th>
                <th class="noBorder">Account Code: </th>
                <th class="noBorder" colspan="4"></th>

            </tr>
            <th>Name</th>
            <th>Date CA Granted</th>
            <th>Particulars</th>
            <th>Reference (Check/ADA NO.)</th>
            <th>Total Amount</th>
            <th>Liquidation for the Month</th>
            <th>Unliquidation as of to date</th>
            <th>Less than 30 days</th>
            <th>31-60 days</th>
            <th>61-365 days</th>
            <th>Over 1 year</th>
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

.foot{
    border:0;
    text-align: left;
    font-weight:bold;
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

 .noBorder {
                 border: 0;
                padding:3px;
                padding-left:12px
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
        'sds': 'Fritzie N. Usares',
        'pdi': 'Venus A. Custodio',
        'ro': 'RO',
    }
    $('#generate').click((e) => {
        e.preventDefault()
        $('#annex_table').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + "?r=report/annex3",
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                target_date = res.reporting_period
                displaData(res.result)
                $('#dots5').hide()
                $('#annex_table').show()
                console.log(province_data['adn'])
                $('#r_period').text(target_date)

            }
        })
    })

    function displaData(data) {
        $('#annex_table tbody').html('')
        var advance_type_keys = Object.keys(data)
        var grand_total_amount = 0
        var grand_total_liquidation = 0
        var grand_total_unliquidated = 0
        for (var advance_type_loop = 0; advance_type_loop < advance_type_keys.length; advance_type_loop++) {
            var advance_name = advance_type_keys[advance_type_loop]
            var row = `<tr class='data_row'>
                <td colspan='11' style='font-weight:bold' >` + advance_name + `</td>

                </tr>`
            $('#annex_table tbody').append(row)
            var total_amount = 0
            var total_liquidation = 0
            var total_unliquidated = 0
            for (var i = 0; i < data[advance_name].length; i++) {

                var account_name = data[advance_name][i]['account_name']
                var check_number = data[advance_name][i]['check_number']
                var check_date = data[advance_name][i]['check_date']
                var particular = data[advance_name][i]['particular']
                var advances_amount = data[advance_name][i]['advances_amount']
                var total_liquidation_this_date = data[advance_name][i]['total_liquidation']
                var unliquidated = parseFloat(data[advance_name][i]['unliquidated'])
                var province = data[advance_name][i]['province']
                var name = province_data[province.toLowerCase()]
                var date1 = new Date(target_date);
                var date2 = new Date(check_date);
                var Difference_In_Time = date1.getTime() - date2.getTime();
                var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);
                var less_30 = ''
                var between_31_to_60 = ''
                var between_61_to_365 = ''
                var over_year = ''
                if (Difference_In_Days <= 30 && unliquidated != 0) {
                    less_30 = thousands_separators(unliquidated)
                } else if (Difference_In_Days >= 31 && Difference_In_Days <= 61 && unliquidated != 0) {
                    between_31_to_60 = thousands_separators(unliquidated)
                } else if (Difference_In_Days >= 61 && Difference_In_Days <= 365 && unliquidated != 0) {
                    between_61_to_365 = thousands_separators(unliquidated)
                } else if (Difference_In_Days > 365 && unliquidated != 0) {
                    var y = 0
                    var m = 0
                    var d = parseInt(Difference_In_Days)

                    y = d / 365
                    d = d % 365
                    m = d / 30
                    d = d % 30
                    over_year = thousands_separators(unliquidated)
                }

                var row = `<tr class='data_row'>
                    <td  >` + name + `</td>
                    <td  >` + check_date + `</td>
                    <td  >` + particular + `</td>
                    <td  >` + check_number + `</td>
                    <td class='amount' >` + thousands_separators(advances_amount) + `</td>
                    <td class='amount' >` + thousands_separators(total_liquidation_this_date) + `</td>
                    <td class='amount' >` + thousands_separators(unliquidated) + `</td>
                    <td  >` + less_30 + `</td>
                    <td>` + between_31_to_60 + `</td>
                    <td class='amount' >` + between_61_to_365 + `</td>
                    <td class='amount' >` + over_year + `</td>
            
                    </tr>`
                $('#annex_table tbody').append(row)
                total_amount += parseFloat(advances_amount)
                total_liquidation += parseFloat(total_liquidation_this_date)
                total_unliquidated += parseFloat(unliquidated)
                grand_total_amount += parseFloat(advances_amount)
                grand_total_liquidation += parseFloat(total_liquidation_this_date)
                grand_total_unliquidated += parseFloat(unliquidated)
            }
            var row = `<tr class='data_row'>
                    <td  style='font-weight:bold;text-align:center;' colspan='3'>Total ` + advance_name + `</td>
                    <td  ></td>
                    <td class='amount' >` + thousands_separators(total_amount) + `</td>
                    <td class='amount' >` + thousands_separators(total_liquidation) + `</td>
                    <td class='amount' >` + thousands_separators(total_unliquidated) + `</td>
                    <td  ></td>
                    <td></td>
                    <td class='amount' ></td>
                    <td class='amount' ></td>
                    </tr>`
            $('#annex_table tbody').append(row)
        }
        var row = `<tr class='data_row'>
                    <td  style='font-weight:bold;text-align:center;' colspan='3'>Grand Total</td>
                    <td  ></td>
                    <td class='amount' >` + thousands_separators(grand_total_amount) + `</td>
                    <td class='amount' >` + thousands_separators(grand_total_liquidation) + `</td>
                    <td class='amount' >` + thousands_separators(grand_total_unliquidated) + `</td>
                    <td  ></td>
                    <td></td>
                    <td class='amount' ></td>
                    <td class='amount' ></td>
                    </tr>`
        $('#annex_table tbody').append(row)
        var row = `  <tr>
                <td  class='foot'></td>
                <td colspan="2" class='foot'>Certified Correct</td>
                <td colspan="3" class='foot'>Aprroved By:</td>

                <td colspan="3" class='foot'>Recieved By:</td>
                <td style="border:0;text-align: center;"></td>
            </tr>
            <tr>
            <td  class='foot'></td>

                <td class='foot' colspan="2"  >
                    <span>JOHN VOLTAIRE ANCLA</span><br>
                    <span>Accountant III</span>
                </td>
                <td colspan="3" class='foot' >
                    <span>GAY A. TIDALGO</span><br>
                    <span>Regional Director</span>
                </td>

                <td colspan="3" class='foot' >
                    <span>MARION T. MONROID</span><br>
                    <span>State Auditor III / Audit Team Leader</span> <br>
                    <span>Comission On Audit - Region XIII</span>
                </td>
                <td style="border:0;text-align: center;"></td>
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
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

$this->title = "CDR";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index" style="background-color: white;">


    <form id='filter'>
        <div class="row">
            <div class="col-sm-2">
                <label for="reporting_period">Reporting Period</label>
                <?php

                echo DatePicker::widget([
                    'id' => 'reporting_period',
                    'name' => 'reporting_period',
                    'value' => !empty($model->reporting_period) ? $model->reporting_period : '',
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
                <label for="province">Province</label>
                <?php
                echo Select2::widget([
                    'name' => 'province',
                    'id' => 'province',
                    'value' => !empty($model->province) ? $model->province : '',
                    'data' => [
                        'adn' => 'ADN',
                        'ads' => 'ADS',
                        'sdn' => 'SDN',
                        'sds' => 'SDS',
                        'pdi' => 'PDI',
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'placeholder' => 'Select Province'
                    ]
                ])

                ?>
            </div>
            <div class="col-sm-3">
                <label for="book">Book</label>
                <?php
                echo Select2::widget([
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'name', 'name'),
                    'id' => 'book',
                    'value' => !empty($model->book_name) ? $model->book_name : '',
                    'name' => 'book',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-3">
                <label for="report_type">Advance Type</label>
                <?php

                echo Select2::widget([
                    'data' => [
                        'Advances for Operating Expenses' => 'OPEX',
                        'Advances to Special Disbursing Officer' => 'SDO'
                    ],
                    'value' => !empty($model->report_type) ? $model->report_type : '',
                    'name' => 'report_type',
                    'id' => 'report_type',
                    'pluginOptions' => [
                        'placeholder' => 'Select Advance Type'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-success" id="generate" style="margin-top:23px">Generate</button>
            </div>
        </div>
    </form>
    <div class="row">
        <?php


        ?>


        <?php
        if (empty($model->id)) {
            echo "<button class='btn btn-success' id='save'>Save</button>";
            // echo "<input type='text' value='$model->is_final'>";


        } else {
            if ($model->is_final === 1) {
                echo "  <button class='btn btn-success' id='cdr_jev'>jev</button>";
                echo "  <input type='text' id='cdr_id' value='$model->id'>";
            }
        }

        ?>
    </div>

    <?php Pjax::begin(['id' => 'cibr', 'clientOptions' => ['method' => 'POST']]) ?>
    <?php
    $prov = [];
    $municipality = '';
    $officer = '';
    $location = '';

    if (!empty($province)) {
        $prov = Yii::$app->memem->cibrCdrHeader($province);
        $municipality = $prov['province'];
        $officer = $prov['officer'];
        $location = $prov['location'];
    }



    ?>
    <table>

        <tr>
            <td colspan="12" class="header" style="text-align: center;border:1px solid white">CASH DISBURSEMENT REGISTER</td>
        </tr>
        <tr>
            <td colspan="12" class="header" style="text-align: center;border:1px solid white">

                <span>
                    For the month of <?php
                                        if (!empty($reporting_period)) {
                                            echo date('F, Y', strtotime($reporting_period));
                                        };
                                        ?>
                </span>
            </td>
        </tr>
        <tr style="border:1px solid white">
            <td colspan="9" class="header">
                <span> Entity Name:Department of Trade and Industry</span>
            </td>
            <td colspan="3" class="header">
                <span>
                    Sheet No. :___________________
                </span>
            </td>
        </tr>
        <tr style="border:1px solid white">
            <td colspan="9" class="header">
                <span> Sub-Office/District/Division: Provincial Office</span>
            </td>
            <td colspan="3" class="header">
                <span>
                    Name of Disbursing Officer: <?= $officer ?>
                </span>
            </td>
        </tr>
        <tr style="border:1px solid white">
            <td colspan="9" class="header">
                <span> Municipality/City/Province: <?php echo $municipality; ?></span>
            </td>
            <td colspan="3" class="header">
                <span>
                    Station: Surigao del Norte
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="9" rowspan="2" style="border-left:1px solid white;border-right:1px solid white;" class="header">
                <span> Fund Cluster : <?= !empty($book) ? $book : '' ?></span>
            </td>
            <td colspan="3" class="header" style="border-left:1px solid white;border-right:1px solid white;">
                <span>
                    Bank : Landbank of the Philippines
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="border-left:1px solid white;border-right:1px solid white;" class="header">
                <span>
                    Location: <?= $location ?>
                </span>
            </td>

        </tr>
        <tr>

            <td rowspan="6" class="t_head">Date</td>
            <td rowspan="6" class="t_head">Check No.</td>
            <td rowspan="6" class="t_head">Particular</td>
            <td rowspan="3" class="t_head" colspan="3">CASH IN BANK</td>
        </tr>

        <tr>
            <td colspan="6" class="t_head">BREAKDOWN </td>
        </tr>
        <tr>
            <td rowspan="3" class="t_head">Salaries and Wages - Regular</td>
            <td rowspan="3" class="t_head">Salaries and Wages -Casual/ Contractual</td>
            <td rowspan="3" class="t_head"> Office Supplies Expenses </td>
            <td colspan="3" class="t_head" rowspan="2">OTHERS</td>
        </tr>


        <tr>
            <td rowspan="3" class="t_head">Deposits</td>
            <td rowspan="3" class="t_head">Withdrawals/ Payments</td>
            <td rowspan="3" class="t_head"> Balances</td>
        </tr>
        <tr>

            <td rowspan="2" class="t_head"> Account Description</td>
            <td rowspan="2" class="t_head">UACS Code</td>
            <td rowspan="2" class="t_head">Amount</td>
        </tr>
        <tr>
            <td rowspan="1" class="t_head">(50101010)</td>
            <td rowspan="1" class="t_head">(50101020)</td>
            <td rowspan="1" class="t_head"> (50203010)</td>
        </tr>
        <tbody>

            <?php
            $total_cash_advance = 0;
            $total_payments = 0;
            $x = 0;
            $balance = 0;
            $amount = 0;
            $withdrawals = 0;
            if (!empty($dataProvider)) {
                foreach ($dataProvider as $data) {
                    $amount =  (float) $data['amount'];
                    $withdrawals = (float) $data['withdrawals'];
                    $balance += $amount  - $withdrawals;
                    if ($data['reporting_period'] === $reporting_period) {
                        if ($x === 0) {
                            echo "<tr>
                            <td></td>
                            <td ></td>
                            <td></td>
                            <td class='amount'></td>
                            <td></td>
                            <td class='amount'>" . number_format($balance - $amount + $withdrawals, 2) . "</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class='amount'></td>
                          </tr>";
                            $x++;
                        }
                        echo "<tr>
                        <td>" . $data['reporting_period'] . "</td>
                        <td >" . $data['check_number'] . "</td>
                        <td>" . $data['particular'] . "</td>
                        <td class='amount'>" . $data['amount'] . "</td>
                        <td class='amount'>" . $data['withdrawals'] . "</td>
                        <td class='amount'>" . number_format($balance, 2) . "</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>" . $data['gl_account_title'] . "</td>
                        <td>" . $data['gl_object_code'] . "</td>
                        <td class='amount'>" . $data['withdrawals'] . "</td>
                       </tr>";
                        $total_cash_advance += (float)$data['amount'];
                        $total_payments += (float)$data['withdrawals'];
                    }
                }
                //     echo "<pre>";
                //         var_dump($dataProvider);
                //    echo" </pre>";
            }


            ?>
            <tr>

                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan="" style="text-align: center;font-weight:bold">Total</td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_cash_advance, 2) ?></td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_payments, 2) ?></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td colspan=""></td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_payments, 2) ?></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-weight: bold;text-align:center;"> Due to BIR - VAT/NonVAT</td>
                <td style="font-weight: bold;text-align:center;"> Due To Bir Expanded</td>
                <td style="font-weight: bold;text-align:center;">Gross Expense</td>
                <td style="font-weight: bold;text-align:center;">Account Description</td>
                <td style="font-weight: bold;text-align:center;">UACS Object Code</td>
                <td style="font-weight: bold;text-align:center;">Amount</td>
            </tr>
            <?php
            $total_conso = 0;
            $total_vat = 0;
            $total_expanded = 0;
            $total_gross = 0;
            if (!empty($consolidated)) {
                foreach ($consolidated as $conso) {
                    $amnt = $conso['total'] != 0 ? number_format($conso['total'], 2) : '-';
                    $vat = $conso['vat_nonvat'] != 0 ? number_format($conso['vat_nonvat'], 2) : '-';
                    $expanded = $conso['expanded_tax'] != 0 ? number_format($conso['expanded_tax'], 2) : '-';
                    $gross = $conso['gross_amount'] != 0 ? number_format($conso['gross_amount'], 2) : '-';

                    echo "<tr>
                        <td></td>
                        <td ></td>
                        <td></td>
                        <td class='amount'></td>
                        <td class='amount'></td>
                        <td></td>
                       
                        <td class='amount'>" . $vat . "</td>
                        <td class='amount'>" . $expanded . "</td>
                        <td class='amount'>" . $gross . "</td>
                        <td>" . $conso['account_title'] . "</td>
                        <td>" . $conso['object_code'] . "</td>
                        <td class='amount' >" . $amnt  . "</td>
                    </tr>";
                    $total_conso += (float)$conso['total'];
                    $total_vat += (float)$conso['vat_nonvat'];
                    $total_expanded += (float)$conso['expanded_tax'];
                    $total_gross += (float)$conso['gross_amount'];
                }
            }
            ?>
            <tr>

                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class='amount'><?= number_format($total_vat, 2) ?></td>
                <td class='amount'><?= number_format($total_expanded, 2) ?></td>
                <td class='amount'><?= number_format($total_gross, 2) ?></td>
                <td style="text-align: center;font-weight:bold">Total</td>
                <td></td>
                <td class='amount' style="font-weight: bold;"><?= number_format($total_conso, 2) ?></td>
            </tr>

            <tr>
                <td colspan="6">

                </td>

                <td colspan="6">
                    <span>
                        The total of the ‘Advances for Operating Expenses – Payments’ column must always be equal to the sum of the totals of the ‘Breakdown of Payments’ columns.

                    </span>
                </td>

            </tr>
            <tr>
                <td colspan="2" style="border-right: none;"></td>
                <td colspan="4" style="text-align: center;border-left:none">
                    <div style="margin-left:-25rem;margin-top:1rem;margin-bottom:2rem"><span>CERTIFIED CORRECT:</span></div>
                    <div><span style="font-weight: bold;"><?= $officer ?></span></div>
                    <div><span>Signature Over Printed Name</span></div>
                    <div style="margin-left:-15rem;"><span>Date</span></div>
                </td>
                <td colspan="6" style="text-align: center;">
                    <div style="margin-left:-25rem;margin-top:1rem;margin-bottom:2rem"><span>RECEIVED BY:</span></div>
                    <div><span style="font-weight: bold;">MARION T. MONROID</span></div>
                    <div><span>Signature Over Printed Name</span></div>
                    <div style="margin-left:-15rem;"><span>Date</span></div>
                </td>

            </tr>


        </tbody>

    </table>

    <?php Pjax::end() ?>
</div>
<style>
    .amount {
        text-align: right;
        padding: 12px;
    }

    table,
    th,
    td {
        border: 1px solid black;
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

<script src="/dti-afms-2/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="/dti-afms-2/frontend/web/js/select2.min.js" />
<link href="/dti-afms-2/frontend/web/css/select2.min.css" rel="stylesheet" />
<link href="/dti-afms-2/frontend/web/js/jquery.dataTables.js" />
<link href="/dti-afms-2/frontend/web/css/jquery.dataTables.css" rel="stylesheet" />


<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
        function generatData(){
            $.pjax({
                container:'#cibr',
                type:'POST',
                url:window.location.pathname +"?r=cdr/cdr",
                data:$("#filter").serialize()
            })
        }
        $(document).ready(function(){
            if ($("#cdr_id").val()>0){
                generatData()
            }
        })
        $('#generate').click(function(e){
            e.preventDefault();
        })
        
        // $("#cibr").on("pjax:success", function(data) {
        //     //     var res= JSON.parse(data)
        //     console.log(data)
            
        // });
        $("#save").click(function(e){
            e.preventDefault();
            $.ajax({
                type:'POST',
                url:window.location.pathname +"?r=report/insert-cdr",
                data:$("#filter").serialize(),
                success:function(data){
                    var res = JSON.parse(data)
                    console.log(res)
                }
            })
        })
        $("#cdr_jev").click(function(e){
            // e.preventDefault();
            
            window.location.href = window.location.pathname + '?r=jev-preparation/cdr-jev&id=' + $('#cdr_id').val()
            // window.location.href = window.location.pathname + '?r=jev-preparation/cdr-jev&reporting_period=' + $('#reporting_period').val()
            // $.ajax({
            //     type:'POST',
            //     url:window.location.pathname +"?r=jev-preparation/insert-cdr",
            //     data:$("#filter").serialize(),
            // })
        
        })

        

        JS;
$this->registerJs($script);
?>
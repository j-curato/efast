<?php

use app\models\Books;
use app\models\DvAucs;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
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
                    'name' => 'report_type',
                    'id' => 'report_type',
                    'pluginOptions' => [
                        'placeholder' => 'Select Advance Type'
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>
        </div>
    </form>

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
            if (!empty($dataProvider)) {
                foreach ($dataProvider as $data) {
                    echo "<tr>
                        <td>" . $data['check_date'] . "</td>
                        <td >" . $data['check_number'] . "</td>
                        <td>" . $data['particular'] . "</td>
                        <td class='amount'>" . $data['amount'] . "</td>
                        <td class='amount'>" . $data['withdrawals'] . "</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>" . $data['gl_account_title'] . "</td>
                        <td>" . $data['gl_object_code'] . "</td>
                        <td class='amount'>" . $data['withdrawals'] . "</td>
                </tr>";
                    $total_cash_advance += (int)$data['amount'];
                    $total_payments += (int)$data['withdrawals'];
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
            <?php
            $total_conso = 0;
            if (!empty($consolidated)) {
                foreach ($consolidated as $conso) {
                    echo "<tr>
                    <td></td>
                    <td ></td>
                    <td></td>
                    <td class='amount'></td>
                    <td class='amount'></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>" . $conso['account_title'] . "</td>
                    <td>" . $conso['object_code'] . "</td>
                    <td class='amount' >" .  number_format($conso['total'], 2) . "</td>
            </tr>";
                    $total_conso += (int)$conso['total'];
                }
            }
            ?>
            <tr>

                <td></td>
                <td></td>
                <td></td>
                <td class='amount'></td>
                <td class='amount'></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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

        $('#generate').click(function(e){
            e.preventDefault();
            
            $.pjax({
                container:'#cibr',
                type:'POST',
                url:window.location.pathname +"?r=report/cdr",
                data:$("#filter").serialize()
            })
        })
JS;
$this->registerJs($script);
?>
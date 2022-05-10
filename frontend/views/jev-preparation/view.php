<?php

use app\models\FundClusterCode;
use app\models\JevReportingPeriod;
use app\models\SubAccounts1;
use app\models\SubAccounts2;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = $model->jev_number;
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jev-preparation-view" style="box-shadow: none;border:none">

    <!-- <h1><?= Html::encode($this->title) ?></h1> -->
    <?php $t = yii::$app->request->baseUrl . '/index.php?r=jev-preparation/delete&id=' . $model->id; ?>
    <p class="actions" style="margin-left:50px;">
        <?= Html::a('Update', ['update', 'id' => $model->id, 'duplicate' => false], ['class' => 'btn btn-primary']) ?>
        <?php
        //  Html::a('Delete', ['delete', 'id' => $model->id], [
        //     'class' => 'btn btn-danger delete',

        // 'data' => [
        //     'confirm' => 'Are you sure you want to delete this item?',
        //     'method' => 'post',
        // ],
        // ])
        $q = JevReportingPeriod::find()->all();
        ?>

        <?= Html::button('Print', ['onclick' => 'window.print()', 'class' => 'btn btn-success print']) ?>
        <?php
        if (!empty($model->cash_disbursement_id)) {

            $q = (new \yii\db\Query())
                ->select('dv_aucs.id')
                ->from('dv_aucs')
                ->where('dv_aucs.dv_number =:dv_number', ['dv_number' => $model->dv_number])
                ->one();
            if (!empty($q)) {
                $dv_link = yii::$app->request->baseUrl . '/index.php?r=dv-aucs/view&id=' . $q['id'];
                echo "<a type='button' href='$dv_link' class='btn btn-success'>DV</a>";
            }
        }
        if (!empty($model->cash_disbursement_id)) {
            $cash_link = yii::$app->request->baseUrl . '/index.php?r=cash-disbursement/view&id=' . $model->cash_disbursement_id;
            echo "<a type='button' href='$cash_link' class='btn btn-warning'>Cash</a>";
        }

        ?>

    </p>

    <?php
    //  DetailView::widget([

    //     'model' => $model,
    //     'attributes' => [
    //         'id', [
    //             'attribute' => 'responsibility_center_id',
    //             'style'=>'border:2px solid red'
    //         ],

    //         'fund_cluster_code_id',
    //         'reporting_period',
    //         'date',
    //         'jev_number',
    //         'dv_number',
    //         'lddap_number',
    //         'entity_name',
    //         'explaination',
    //     ],
    // ]) 
    $total_debit = 0;
    $total_credit = 0;
    ?>





    <div class="container ">

        <table>

            <thead>
                <tr>
                    <th rowspan="2" colspan="2">
                        <div style="text-align:center; ">
                            <h5>
                                JOURNAL ENTRY VOUCHER
                            </h5>
                        </div>
                        <div style="padding:5px ;align-items:center;">

                            <span>
                                <?php

                                if (!empty($model->cash_disbursement_id)) {
                                    echo "<span>Payee :</span>";
                                    echo "<span>{$model->cashDisbursement->dvAucs->payee->account_name}</span>";
                                } else {
                                    if (!empty($model->payee_id)) {
                                        echo "<span>Payee :</span>";
                                        echo "<span>{$model->payee->account_name}</span>";
                                    } else {
                                        echo   "<span>Entity Name :</span>";
                                        echo   "<span>Department of Trade and Industry</span>";
                                    }
                                }

                                ?>
                            </span>
                        </div>
                        <div style="padding:5px ;align-items:center;">
                            <span>
                                Fund Cluster :
                            </span>
                            <span>
                                <?php echo ($model->books) ? $model->books->name : "";

                                ?>
                            </span>
                        </div>
                    </th>

                    <th rowspan="1" colspan="3">
                        <span>
                            JEV #:
                        </span>
                        <span>
                            <?php echo $model->jev_number ?>

                        </span>
                    </th>

                </tr>
                <tr>
                    <th rowspan="1" colspan="3">
                        <div>
                            <span>
                                Date:
                            </span>
                            <span>
                                <?php echo $model->date ?>
                            </span>
                        </div>
                        <div>
                            <span>
                                Reporting Period:
                            </span>
                            <span>
                                <?php echo $model->reporting_period ?>
                            </span>
                        </div>
                    </th>

                </tr>
                <tr>
                    <th rowspan="3" style="text-align: center;">
                        Responsibility Center
                    </th>
                    <th rowspan="1" colspan="4" style="text-align: center;">
                        ACCOUNTING ENTRIES
                    </th>
                </tr>
                <tr>
                    <th rowspan="2" style="text-align: center;">
                        Accounts and Explanation
                    </th>
                    <th rowspan="2">
                        UACS Object Code
                    </th>
                    <th colspan="2">
                        Amount
                    </th>

                </tr>
                <tr>
                    <th>
                        Debit
                    </th>
                    <th>
                        Credit
                    </th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php
                        // echo ($model->responsibilityCenter) ? $model->responsibilityCenter->name : '';
                        ?>
                    </td>
                    <td style="word-wrap:break-word;width:400px"><?php echo $model->explaination ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <?php
                //   $model->jevAccountingEntries->orderBy('debit DESC');
                $arr = [];
                ?>
                <?php
                $xx = $model->jevAccountingEntries;
                foreach ($model->jevAccountingEntries as $key => $value) : ?>
                    <?php
                    $general_ledger = '';
                    $object_code = '';
                    // if ($value->lvl === 1) {
                    //     $general_ledger = $value->chartOfAccount->general_ledger;
                    //     $object_code = $value->chartOfAccount->uacs;
                    // } else if ($value->lvl === 2) {
                    //     $q = SubAccounts1::find()->where("object_code =:object_code", ['object_code' => $value->object_code])->one();
                    //     $general_ledger = $q->name;
                    //     $object_code = $q->object_code;
                    // } else if ($value->lvl === 3) {
                    //     $q = SubAccounts2::find()->where("object_code =:object_code", ['object_code' => $value->object_code])->one();
                    //     $general_ledger = $q->name;
                    //     $object_code = $q->object_code;
                    // }
                    $q = Yii::$app->db->createCommand('SELECT * FROM accounting_codes where object_code =:object_code')
                        ->bindValue(":object_code", $value->object_code)
                        ->queryOne();
                    if (!empty($q)) {

                        $general_ledger = $q['account_title'];
                        $object_code = $q['object_code'];
                    }

                    $arr[] = [
                        'general_ledger' => $general_ledger,
                        'object_code' => $object_code,
                        'debit' => $value->debit,
                        'credit' => $value->credit,
                        'lvl' => $value->lvl,

                    ];
                    $total_credit += $value->credit;

                    $total_debit += $value->debit;
                    ?>
                <?php endforeach; ?>
                <?php
                ArrayHelper::multisort($arr, ['credit', [SORT_ASC]]);
                foreach ($arr as $val) {
                    $debit = $val['debit'] != 0 ? number_format($val['debit'], 2) : '';
                    $credit = $val['credit'] != 0 ? number_format($val['credit'], 2) : '';
                    echo "<tr>
                            <td></td>
                            <td>{$val['general_ledger']}</td>
                            <td>{$val['object_code']}</td>
                            <td style='text-align:right'>$debit </td>
                            <td style='text-align:right'> $credit</td>         
                        </tr>";
                }
                ?>

                <tr>
                    <td>DV# </td>
                    <td><?php echo $model->dv_number ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <?php
                    if (strtolower($model->check_ada) == 'ada') {
                        echo "<td>LDDAP#</td>";
                        // echo "<td>{$model->lddap_number}</td>";
                    } else if (strtolower($model->check_ada) == 'check') {
                        echo "<td>CHECK#</td>";
                    } else {
                        echo "<td style='padding:12px'></td>";
                        // echo "<td></td>";
                    }
                    echo "<td>{$model->check_ada_number}</td>";
                    ?>

                    <td></td>
                    <td></td>
                    <td></td>
                </tr>



                <tr>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td class="amount"><?php echo number_format($total_debit, 2); ?></td>
                    <td class="amount"><?php echo number_format($total_credit, 2); ?></td>
                </tr>
                <tr>
                    <td colspan="2">

                        <div>
                            <h6>
                                Prepared By:
                            </h6>
                        </div>
                        <div style="text-align: center;">
                            <div style="width: 70px;height:50px;margin-left:auto;margin-right:auto">
                                <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/charles_sign.png', [
                                    'alt' => 'some', 'class' => 'pull-left img-responsive',
                                    'style' => 'width: 80px;height:50px;margin-left:auto'
                                ]); ?>
                            </div>
                            <h5>
                                CHARLIE C. DECHOS, CPA
                            </h5>

                            <h6>
                                Accountant II
                            </h6>
                        </div>
                    </td>
                    <td colspan="3">

                        <div>
                            <h6>
                                Certified Correct:
                            </h6>
                        </div>
                        <div style="text-align: center;">
                            <h5>
                                JOHN VOLTAIRE S. ANCLA, CPA
                            </h5>
                            <h6>
                                Accountant III
                            </h6>
                        </div>
                    </td>

                </tr>
            </tbody>
        </table>



    </div>
    <style>
        .container {
            /* border: 1px solid black; */
            height: auto;
            background-color: white;
            /* box-shadow: 12px; */
            border-radius: 5px;
            padding: 20px;
        }

        .amount {
            text-align: right;
        }

        th,
        td {
            border: 1px solid black;
            padding: 7px;

        }

        .row-2 {
            text-align: center;
            height: auto;
            border: none;
        }

        .date {
            display: flex;
            /* grid-template-columns: 1fr 1fr; */
            /* grid-row: 1fr; */
            position: relative;
            height: 50%;
            padding: 0;
            margin: 0;

        }

        .date>div {
            width: 100%;
            height: 100%;
            padding: 2px;
            text-align: center;
            margin-top: auto;
            margin-bottom: auto;

            ;
        }

        .date>div>span {
            margin: 2px;
        }

        .h-debit-credit {
            display: grid;
            grid-template-columns: 1fr 1fr;
            text-align: center;
            height: 100%;

        }



        .acc-exp-row {
            display: flex;
            width: 100%;
            height: 100%;

        }

        .acc-exp-row>div {
            width: 100px;
            /* border: 1px solid black; */
        }

        .row-2 {
            display: flex;
            width: 100%;

        }

        .row-2>div {
            border: 1px solid black;

            width: 20%;
        }

        .row-1 {
            display: grid;
            height: 100px;
            width: 100%;
            padding: 0;
            margin: 0;
            grid-template-columns: 1fr 1fr;
        }

        .row-1>div {
            width: 100%;
            padding: 0;
            margin: 0;
            border: 1px solid black;
            font-weight: bold;
        }

        h5 {
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        @media print {
            .actions {
                display: none;
            }

            h5 {
                font-size: 10px;
            }

            td,
            th {
                font-size: 10px;
            }

            h6 {
                font-size: 10px;
            }

            span {
                font-size: 10px;
            }

            /* 
            .form-wrapper {
                margin-top: 20px;
                background-color: red;
            } */

            .print {
                display: none;
            }

            /* @page {
                margin-top: 20cm;
                margin-bottom: 5cm;
            } */

            .main-footer {
                display: none;
            }

        }
    </style>
</div>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
    

            $(".delete").click(function(e){
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover thi data!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Delete',
                    cancelButtonText: "Cancel",
                    closeOnConfirm: true,
                    closeOnCancel: true,
                    timer: 2000,
                },
                function(isConfirm){

                if (isConfirm){
                    swal("Shortlisted!", "Candidates are successfully shortlisted!", "success");
                    
                    var x= $('.delete').val()
                    $.ajax({
                        type: "POST",
                        url: x,
                        // data: data,
                        // success: success,
                        // dataType: dataType
                    });
                    } 
                else {
                    // swal("Cancelled", "Your imaginary file is safe :)", "error");
                        // e.preventDefault();
                    }
                });
            })
JS;
$this->registerJs($script);
?>
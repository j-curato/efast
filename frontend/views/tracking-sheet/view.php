<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrackingSheet */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tracking Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tracking-sheet-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'id' => 'update']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php
    $ors_number = !empty($model->process_ors_id) ? $model->processOrs->serial_number : '';
    $date = date('M d, Y', strtotime($model->created_at));
    $time = date('h:i A', strtotime($model->created_at));
    $ors_date = '';
    $ors_time = '';
    $transaction_date = $date;
    $transaction_time = $time;
    if (strtolower($model->transaction_type) === 'single') {
        $ors_date =  date('M d, Y', strtotime($model->processOrs->created_at));
        // echo $model->processOrs->transaction->created_at;

        $ors_time =  date('h:i A', strtotime($model->processOrs->created_at));
    }

    if (strtolower($model->transaction_type) === 'single') {
        $transaction_date =  date('M d, Y', strtotime($model->processOrs->transaction->created_at));
        $transaction_time =  date('h:i A', strtotime($model->processOrs->transaction->created_at));
    }
    $acc_2_date = '';
    $acc_2_in_time = '';
    $acc_2_out_time = '';
    if (!empty($model->dvAucs->transaction_begin_time)) {
        $acc_2_date = date('F d, Y', strtotime($model->dvAucs->transaction_begin_time));
        $acc_2_in_time = date('h:i A', strtotime($model->dvAucs->transaction_begin_time));
        $acc_2_out_time = date('h:i A', strtotime($model->dvAucs->created_at));
    }
    $acc_3_date = '';
    $acc_3_in_time = '';
    $acc_3_out_time = '';
    if (!empty($model->dvAucs->out_timestamp)) {
        $acc_3_out_time = date('h:i A', strtotime($model->dvAucs->out_timestamp));
    }
    if (!empty($model->dvAucs->accept_timestamp)) {
        $acc_3_date = date('F d, Y', strtotime($model->dvAucs->accept_timestamp));
        $acc_3_in_time = date('h:i A', strtotime($model->dvAucs->accept_timestamp));
    }

    ?>
    <div class="container">
        <table id="page">
            <tbody>
                <tr>
                    <td colspan="5" class="header">
                        <span style="float:right;margin-right:5px">
                            <?php
                            echo $model->tracking_number;
                            ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: left; " class="header">
                        <span>

                            Payee:
                        </span>
                        <span>

                            <?php echo $model->payee->account_name; ?> </span>
                    </td>
                    <td colspan="1" rowspan="2" class="header"> <?= Html::img(
                                                                    Yii::$app->request->baseUrl . '/frontend/web/dti3.png',
                                                                    ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 50px;height:50px;margin-left:auto']
                                                                ); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="header">
                        <span>
                            Gross Amount:

                        </span>
                        <span><?php echo number_format($model->gross_amount, 2) ?></span>

                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="header">
                        <span>

                            Net Amount:
                        </span>
                        <span>


                    </td>
                    </span>
                    <td class="header" style="padding-top: 10px;"><span>Particular</span></td>
                </tr>

                <tr>
                    <td colspan="3" class="header">
                        <span>

                            DV No. :
                        </span>
                        <span>
                            <?php
                            if (!empty($model->dvAucs->dv_number)) {
                                echo $model->dvAucs->dv_number;
                                // var_dump($model->dvAucs);
                            }
                            ?>

                        </span>
                    </td>
                    <td colspan="2" rowspan="2">
                        <span>
                            <?php
                            echo $model->particular;

                            ?>
                        </span>

                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="header">
                        <span>

                            ORS NO.:
                        </span>
                        <span>
                            <?php echo $ors_number ?>
                        </span>
                    </td>
                </tr>

                <tr>

                    <td></td>
                    <td style="width:80px;" class="bold">DATE</td>
                    <td style="width:80px;" class="bold">TIME-IN</td>
                    <td style="width:80px;" class="bold">TIME-OUT</td>
                    <td class="bold">REMARKS</td>
                </tr>
                <tr>
                    <td style="width: 230px;" class="bold">Accounting Staff <br>
                        <span class="note">
                            (Date and Time for Acknowledgin Reciept of DV's with complete documents)
                        </span>
                    <td>
                        <?php

                        echo $transaction_date;


                        ?>
                    </td>
                    <td><?php echo $transaction_time ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold">Budget Officer <span></span>

                    </td>

                    <td><?php
                        echo $ors_date

                        ?></td>
                    <td><?php echo $ors_time ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold">Accountant II
                        <br>
                        <span class="note">
                            Time in when the DV's were acknowledged to be complete and consistend or upon compliance of lacking documents, whichever is later.

                        </span><br>
                        <span class="note">Time out left blank unless if Accountant II acts as OIC Chief Accountant</span>
                    </td>
                    <td><?php

                        echo $acc_2_date;
                        ?></td>
                    <td><?php echo $acc_2_in_time; ?></td>
                    <td><?php echo $acc_2_out_time ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold" style="margin:0">Chief Accountant <br>
                        <span class="note" s>TIme in left blank unless if Accountant II is on leave or upon compliance of lacking documents, if any</span><br>
                        <span class="note">Time out when the DV's were acknowledged to be complete,correct and consistent or upon compliance of lacking documents, whichever is later</span>
                    </td>
                    <td><?php echo $acc_3_date; ?></td>
                    <td><?php echo $acc_3_in_time ?></td>
                    <td><?php echo $acc_3_out_time ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="bold"><span>Note: Accounting Staff,then encodes to the web-based system the time-in and time-out from Accountants II and II, before Forwarding the DV's for RD's Signature. The "Process DV" module Shall be used for this.</span></td>

                </tr>
                <tr>
                    <td colspan="5" style="text-align: center;font-weight:bold" class="bold"> Voucher at Cash Unit</td>

                </tr>
                <tr>

                    <td class="bold">
                        <span>
                            Cashier
                        </span>
                        <br>
                        <span class="note">(Date and Time for Acknowledging Reciept of Approved DV from RD)</span>
                    </td>
                    <td><?php

                        if (!empty($model->dvAucs->cashDisbursement->issuance_date)) {
                            echo 'date';
                        }
                        ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>

                    <td>
                        <span class="bold">
                            Cashier
                        </span>
                        <br>
                        <span class="note">
                            (Date and TIme For Check Issuance)
                        </span>

                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" style="font-weight: bold;">Note: Cash unit staff,then, encodes to the web-based system the time-in and time-out from Cash Unit, upon check issuances. The "Cash Disbursement" module Shall be used for this</td>
                </tr>
                <tr>
                    <td colspan="5">
                        <span style="float:right" class="bold">

                            TURN AROUND TIME:_____________________________
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>


</div>
<style>
    #page tr {
        padding: 0;
        margin: 0;
    }

    table {
        padding: 20px;
    }

    .note {
        font-size: 9px;
        padding: 0;
        margin: 0;
        display: inline-block;
    }

    .bold {
        font-weight: bold;
    }

    table,
    td,
    th {
        padding: 20px;
        border: 1px solid black;
    }

    .header {
        text-align: left;
        border: none;
        padding: 0;
        padding-left: 15px;

    }

    .container {
        background-color: white;
        margin-bottom: 20px;
    }

    @media print {

        table,
        td,
        th {
            padding: 5px;
            font-size: 12px;
        }

        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }

        table {
            margin-bottom: 10px;
        }
    }

    tr {
        padding: 0;
    }
</style>
<?php
$script = <<< JS

        $('#update').click(function(e){
            e.preventDefault();
            
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
JS;
$this->registerJs($script);
?>
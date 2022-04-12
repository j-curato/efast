<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TrackingSheet */

$this->title = $model->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Tracking Sheets', 'url' => ['tracking-index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tracking-sheet-view">



    <?php
    // $ors_number = !empty($model->process_ors_id) ? $model->processOrs->serial_number : '';
    $date = date('M d, Y', strtotime($model->created_at));
    $time = date('h:i A', strtotime($model->created_at));
    $ors_date = '';
    $ors_time = '';
    $transaction_date = '';
    $transaction_time = '';


    if (!empty($model->recieved_at) &&  DateTime::createFromFormat('Y-m-d H:i:s', $model->recieved_at)->format('Y') >= 1) {
        $recieve_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $model->recieved_at);

        $transaction_date = $recieve_timestamp->format('F d, y');
        $transaction_time = $recieve_timestamp->format('h:i A');
    }


    $budget_time_in  = '';
    $budget_date = '';
    $budget_remarks = '';


    if ($model->transaction_type !== 'Single' && $model->transaction_type !== 'Payroll') {
        $budget_remarks = 'Not Applicable ORS is ' . $model->transaction_type;
    } else {
        $ors_created_at = Yii::$app->db->createCommand("SELECT 
        process_ors.created_at
        FROM dv_aucs_entries
        LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
        WHERE dv_aucs_entries.dv_aucs_id= :id
        LIMIT 1")
            ->bindValue(':id', $model->id)
            ->queryScalar();

        $budget = DateTime::createFromFormat('Y-m-d H:i:s', $ors_created_at);
        $budget_date = $budget->format('F d,Y');
        $budget_time_in =  $budget->format('h:i A');
    }
    $gross_amount = Yii::$app->db->createCommand("SELECT 
    IFNULL(SUM(dv_aucs_entries.amount_disbursed),0)+
    IFNULL(SUM(dv_aucs_entries.vat_nonvat),0)+
    IFNULL(SUM(dv_aucs_entries.ewt_goods_services),0)+
    IFNULL(SUM(dv_aucs_entries.compensation),0)+
    IFNULL(SUM(dv_aucs_entries.other_trust_liabilities),0)
     as gross_amount
    FROM dv_aucs_entries
    WHERE dv_aucs_entries.dv_aucs_id = :id")
        ->bindValue(':id', $model->id)
        ->queryScalar();
    $net_amount = Yii::$app->db->createCommand("SELECT 
    SUM(dv_aucs_entries.amount_disbursed)
     as net_amount
    FROM dv_aucs_entries
    WHERE dv_aucs_entries.dv_aucs_id = :id")
        ->bindValue(':id', $model->id)
        ->queryScalar();
    $acc_2_date = '';
    $acc_2_in_time = '';
    $acc_2_out_time = '';
    $cashTimeOut = '';
    $cashTimeIn = '';
    if (!empty($model->transaction_begin_time)) {
        $acc_2_date = date('F d, Y', strtotime($model->transaction_begin_time));
        $acc_2_in_time = date('h:i A', strtotime($model->transaction_begin_time));
        $acc_2_out_time = date('h:i A', strtotime($model->created_at));
    }
    $acc_3_date = '';
    $acc_3_in_time = '';
    $acc_3_out_time = '';
    if (!empty($model->out_timestamp)) {
        $acc_3_out_time = date('h:i A', strtotime($model->out_timestamp));
    }
    if (!empty($model->accept_timestamp)) {
        $acc_3_date = date('F d, Y', strtotime($model->accept_timestamp));
        $acc_3_in_time = date('h:i A', strtotime($model->accept_timestamp));
    }

    ?>
    <div class="container">

        <p>
            <?= Html::a('Update', ['tracking-update', 'id' => $model->id], ['class' => 'btn btn-primary', 'id' => 'update']) ?>

        </p>
        <table id="page">

            <tbody>
                <tr>
                    <td colspan="5" class="header">
                        <span style="float:right;margin-right:5px">
                            <?php
                            echo $model->dv_number;
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
                        <span><?php
                                echo number_format($gross_amount, 2)
                                ?></span>

                    </td>
                </tr>

                <tr>
                    <td colspan="4" class="header">
                        <span>
                            Net Amount:
                        </span>
                        <span>
                            <?= number_format($net_amount, 2) ?>
                        </span>


                    </td>
                    <td class="header" style="padding-top: 10px;"><span>Particular</span></td>
                </tr>

                <tr>
                    <td colspan="3" class="header">
                        <span>

                            DV No. :
                        </span>
                        <span>
                            <?php
                            if (!empty($model->dv_number)) {
                                echo $model->dv_number;
                                // var_dump($model);
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
                            <?php
                            $ors_numbers = Yii::$app->db->createCommand("SELECT 
                            process_ors.serial_number
                            FROM dv_aucs_entries
                            LEFT JOIN process_ors ON dv_aucs_entries.process_ors_id = process_ors.id
                            WHERE dv_aucs_entries.dv_aucs_id = :id")
                                ->bindValue(':id', $model->id)
                                ->queryAll();
                            $ors_length = count($ors_numbers);
                            foreach ($ors_numbers as $index => $val) {
                                echo $val['serial_number'];
                                if ($index + 1 != $ors_length) {
                                    echo ',';
                                }
                            }

                            // echo $ors_number 
                            ?>
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
                            (Date and Time for Acknowledgin Receipt of DV's with complete documents)
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
                        echo $budget_date

                        ?></td>
                    <td><?php echo $budget_time_in ?></td>
                    <td></td>
                    <td><?= $budget_remarks ?></td>
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
                        <span class="note">Time in left blank unless if Accountant II is on leave or upon compliance of lacking documents, if any</span><br>
                        <span class="note">Time out when the DV's were acknowledged to be complete,correct and consistent or upon compliance of lacking documents, whichever is later</span>
                    </td>
                    <td><?php echo $acc_3_date; ?></td>
                    <td><?php echo $acc_3_in_time ?></td>
                    <td><?php echo $acc_3_out_time ?></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="bold"><span>Note: Accounting Staff,then encodes to the web-based system the time-in and time-out from Accountants II and III, before Forwarding the DV's for RD's Signature. The "Process DV" module Shall be used for this.</span></td>

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
                        <span class="note">(Date and Time for Acknowledging Receipt of Approved DV from RD)</span>
                    </td>
                    <td><?php

                        if (!empty($model->cashDisbursement->issuance_date)) {
                            // echo 'date';
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
                    <td><?php

                        if (!empty($model->cashDisbursement->issuance_date)) {
                            // echo $model->cashDisbursement->issuance_date;
                            $cashTimeIn = DateTime::createFromFormat('H:i:s', $model->cashDisbursement->begin_time)->format('h:i A');
                            $cashTimeOut = DateTime::createFromFormat('H:i:s', $model->cashDisbursement->out_time)->format('h:i A');
                            echo DateTime::createFromFormat('Y-m-d', $model->cashDisbursement->issuance_date)->format('F d, Y');
                            // $cash_date = date('F d, Y', strtotime($model->cashDisbursement->issuance_date));
                            // return $cash_date;
                            // echo ($cashTimeOut);
                        }
                        ?></td>
                    <td>
                        <?= $cashTimeIn ?>

                    </td>


                    <td> <?= $cashTimeOut ?></td>
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
        <table id="check_list">


            <tbody>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>PR</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>RIC/ICS</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Payroll</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Summary of Exp.</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Canvas</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Delivery Receipt</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>DTR</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>OBR</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Abstract</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>SOA/Billing</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Letter/Memo</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Trip/Ticket</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>PO</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Pre/Post Repair Inspection</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Contract</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Request Slip</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>JO </span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Report of Wast Material</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>TO</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Remittance List</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>IAR</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Property Acknowledgement Receipt</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>IT</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Others</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>OR/RER</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Proposal</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>CTC</span>
                    </td>
                    <td>

                        <span>__________________</span>
                    </td>
                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Charge Invoice</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Attendance</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>CA</span>
                    </td>
                    <td>
                        <span>__________________</span>
                    </td>

                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Sales/Cash Invoice</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Terminal/Post-Activity Report/Minutes</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Ticket</span>
                    </td>
                    <td>

                        <span>__________________</span>
                    </td>

                </tr>
                <tr>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>RCA</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Authorization</span>
                    </td>
                    <td>

                        <i class="glyphicon glyphicon-unchecked"></i>
                        <span>Certification</span>
                    </td>
                    <td>

                        <span>__________________</span>
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

    #check_list td {
        padding: 5px;
        border: 0
    }

    #check_list {
        margin-top: 12px;
    }

    .container {
        padding: 1rem;
    }

    @media print {
        .container {
            margin: 0;
            padding: 0;
        }

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

        // $('#update').click(function(e){
        //     e.preventDefault();
            
        //     $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        // });
JS;
$this->registerJs($script);
?>
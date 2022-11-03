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

    $dv_timestamps = Yii::$app->db->createCommand("SELECT 

    IFNULL(dv_aucs.in_timestamp,'') as accountant_in, 
    IFNULL(dv_aucs.out_timestamp,'') as accountant_out,
    IFNULL(dv_aucs.created_at,'') as dv_created_at,
    IFNULL(dv_aucs.recieved_at,'') as dv_recieved_at,
    IFNULL(cash_disbursement.issuance_date,'') as check_date,
    IFNULL(cash_disbursement.begin_time,'') as cash_in,
    IFNULL(cash_disbursement.out_time,'') as cash_out,
    IFNULL(cash_disbursement.is_cancelled,'')
    FROM 
    dv_aucs
    LEFT  JOIN cash_disbursement ON dv_aucs.id = cash_disbursement.dv_aucs_id
    
    WHERE
    dv_aucs.is_cancelled !=1
    AND (cash_disbursement.is_cancelled  = 0 OR cash_disbursement.id IS NULL)
    AND dv_aucs.id = :id
    ")->bindValue(':id', $model->id)
        ->queryOne();
    // $ors_number = !empty($model->process_ors_id) ? $model->processOrs->serial_number : '';
    $date = date('M d, Y', strtotime($model->created_at));
    $time = date('h:i A', strtotime($model->created_at));


    $chief_accountant_time_in = '';
    $chief_accountant_time_out = '';
    $chief_accountant_date_in = '';
    $chief_accountant_date_out = '';

    $cashier_time_in = '';
    $cashier_time_out = '';
    $cashier_date = '';

    if (!empty($dv_timestamps['accountant_in'])) {
        $timestamp = new Datetime($dv_timestamps['accountant_in']);
        $chief_accountant_date_in = $timestamp->format('F d, Y');
        $chief_accountant_time_in = $timestamp->format('h:i A');
    }
    if (!empty($dv_timestamps['accountant_out'])) {
        $out_timestamp = new Datetime($dv_timestamps['accountant_out']);

        $chief_accountant_date_out = $out_timestamp->format('F d, Y');
        $chief_accountant_time_out = $out_timestamp->format('h:i A');
    }
    if (!empty($dv_timestamps['check_date'])) {
        $cashier_date = DateTime::createFromFormat('Y-m-d', $dv_timestamps['check_date'])->format('F d, Y');
    }
    if (!empty($dv_timestamps['cash_in'])) {
        $time_in = DateTime::createFromFormat('H:i:s', $dv_timestamps['cash_in']);
        $cashier_time_in = $time_in->format('h:i A');
    }
    if (!empty($dv_timestamps['cash_out'])) {
        $time_out = DateTime::createFromFormat('H:i:s', $dv_timestamps['cash_out']);
        $cashier_time_out = $time_out->format('h:i A');
    }

    $cash_check_issuance_date = '';
    $cash_time_id = '';
    $cash_time_out = '';




    $ors_date = '';
    $ors_time = '';
    $transaction_date = '';
    $transaction_time = '';


    if (!empty($model->recieved_at) &&  DateTime::createFromFormat('Y-m-d H:i:s', $model->recieved_at)->format('Y') >= 1) {
        $recieve_timestamp = DateTime::createFromFormat('Y-m-d H:i:s', $model->recieved_at);
        $transaction_date = $recieve_timestamp->format('F d, Y');
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

        if (!empty($ors_created_at)) {

            $budget = DateTime::createFromFormat('Y-m-d H:i:s', $ors_created_at);
            $budget_date = $budget->format('F d,Y');
            $budget_time_in =  $budget->format('h:i A');
        }
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
                    <th colspan="6" class='no-border'>
                        DEPARTMENT OF TRADE AND INDUSTRY
                    </th>
                </tr>
                <tr>
                    <th colspan="6" class='no-border'>REGION</th>
                </tr>
                <tr>
                    <th colspan="4" class='no-border'>
                        <span>Payee: </span>
                        <span style="font-size:12px;text-decoration:underline"><?php echo $model->payee->account_name ?></span>
                        <br>
                        <span>Particulars:</span>
                        <span style="font-size: 12px;text-decoration:underline;"><?php echo $model->particular ?></span>
                    </th>

                    <th colspan="2" class='no-border'>
                        <span>DV No.: </span>
                        <span style="text-decoration: underline;">
                            <?php
                            echo $model->dv_number;
                            ?></span>
                        <br>
                        <span>DV Amount: </span>
                        <span style="text-decoration: underline;"> <?= !empty($net_amount) ? number_format($net_amount, 2) : '' ?></span>
                        <br>
                        <span>ORS No.</span>
                        <span style="text-decoration:underline ;">
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


                            ?>
                        </span>
                        <br>
                        <span>Gross Amount: </span>
                        <span style="text-decoration: underline;"><?php echo !empty($gross_amount) ? number_format($gross_amount, 2) : '' ?></span>
                    </th>
                </tr>
                <tr>
                    <th colspan="6" class='no-border'>Part 1: Routing Slip</th>
                </tr>
                <?php
                // Html::img(Yii::$app->request->baseUrl . '/frontend/web/dti3.png',
                //                                                         ['alt' => 'some', 'class' => 'pull-left img-responsive', 'style' => 'width: 50px;height:50px;margin-left:auto']
                ?>

                <tr>

                    <td>Personnel Action</td>
                    <td style="width:auto;" class="bold">DATE-IN</td>
                    <td style="width:auto;" class="bold">TIME-IN</td>
                    <td style="width:auto;" class="bold">DATE-OUT</td>
                    <td style="width:auto;" class="bold">TIME-OUT</td>
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
                    <td>
                        <?php

                        echo $acc_2_date;
                        ?></td>
                    <td><?php echo $acc_2_in_time; ?></td>
                    <td><?php echo $acc_2_out_time ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="bold" style="margin:0">Chief Accountant <br>
                        <span class="note">Time in left blank unless if Accountant II is on leave or upon compliance of lacking documents, if any</span><br>
                        <span class="note">Time out when the DV's were acknowledged to be complete,correct and consistent or upon compliance of lacking documents, whichever is later</span>
                    </td>
                    <td><?php echo $chief_accountant_date_in ?></td>
                    <td><?php echo $chief_accountant_time_in; ?></td>
                    <td><?php echo $chief_accountant_date_out ?></td>
                    <td><?php echo $chief_accountant_time_out ?></td>
                    <td></td>
                </tr>
                <tr>

                    <td class="bold">
                        <span>
                            Cashier
                        </span>
                        <br>
                        <span class="note">(Date and Time for Acknowledging Receipt of Approved DV from RD)</span>
                    </td>
                    <td><?= $cashier_date ?></td>
                    <td><?= $cashier_time_in ?></td>
                    <td><?= $cashier_date ?></td>
                    <td><?= $cashier_time_out ?></td>
                    <td></td>
                </tr>


                <tr>
                    <th colspan="6" class="no-border">

                        <span>Part 2: Checklist of Attachments/Supporting documents (for FAD use) </span>
                    </th>
                </tr>
                <!--
                <tr>
                    <th class='no-border'><span>
                            Transaction:
                        </span></th>
                    <th colspan="6" class='no-border'><span></span></th>
                </tr>
                <tr>
                    <th colspan="6" class='no-border'>
                        <span>__________________</span>
                        <span>Certificate of travel Completed</span>
                        <br>
                        <span>__________________</span>
                        <span>Aprroved Travel Order</span>
                        <br>
                        <span>__________________</span>
                        <span>Aprroved Itinerary Order</span>
                        <br>
                        <span>__________________</span>
                        <span>Paper/Electronic Plane, Boat or bus Tickets, boarding pass.terminal fee and other reciepts</span>
                        <br>
                        <span>__________________</span>
                        <span>Certificate of Expense not Requiring Receipts</span>
                        <br>
                        <span>__________________</span>
                        <span>Certificat of Appearance</span>
                        <br>
                        <span>__________________</span>
                        <span>Others</span>

                    </th>
                </tr> -->
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

        <table>
            <thead>

            </thead>
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

    table {
        width: 100%;
    }

    td,
    th {
        padding: 20px;
        border: 1px solid black;
    }

    #check_list {
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

    .no-border {
        border: none;
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
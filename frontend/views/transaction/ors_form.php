<?php


use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use aryelds\sweetalert\SweetAlertAsset;
use app\components\helpers\SweetAlertHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$title = $model->tracking_number;

$this->title =  $title;
// $this->params['breadcrumbs'][] = $this->title;

$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $title;
$iars =  implode(',', ArrayHelper::getColumn($model->getIarItemsA(), 'iar_number'));


$certifiedBy  = !empty($model->fk_certified_by) ? $model->certifiedBy->getEmployeeDetails() : [];
$certifiedBudgetBy  = !empty($model->fk_certified_budget_by) ? $model->certifiedBudgetBy->getEmployeeDetails() : [];
$certifiedCashBy  = !empty($model->fk_certified_cash_by) ? $model->certifiedCashBy->getEmployeeDetails() : [];
$approvedBy = !empty($model->fk_approved_by) ? $model->approvedBy->getEmployeeDetails() : [];

?>
<div class="jev-preparation-index" id='doc'>

    <?php

    $fund = Yii::$app->db->createCommand("SELECT fund_cluster_code.id,fund_cluster_code.name FROM fund_cluster_code")->queryAll();
    $books = Yii::$app->db->createCommand("SELECT books.id,books.name FROM books")->queryAll();
    $division = strtolower($model->responsibilityCenter->name);
    ?>
    <!-- FORM 1 -->
    <div class="card p-2 container">
        <span>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= !$model->is_cancelled ?
                Html::a('Cancel', ['cancel', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'onclick' => SweetAlertHelper::getCancelConfirmation(Url::to(['cancel', 'id' => $model->id]))
                ])
                : '' ?>
            <button class="btn btn-success" type="button" id="print">Print</button>
        </span>
    </div>
    <div class="container card">

        <?php
        //  Pjax::begin(['id' => 'journal', 'clientOptions' => ['method' => 'POST']])
        ?>
        <div style="float: right;">
            <h6>
                <?= $model->tracking_number ?>
            </h6>
        </div>

        <table id="ors_form">
            <tbody>
                <tr>

                    <th colspan="5" class="text-center">
                        <h5 class="font-weight-bold">OBLIGATION REQUEST AND STATUS</h5>
                        <h5 class="font-weight-bold">Department of Trade and Industry - Caraga</h5>
                        <h5 class="font-weight-bold">ENTITY NAME</h5>

                    </th>
                    <td colspan="3">
                        <div class="serial">
                            <span><b>Serial No.:</b></span>
                            <span class="float-right"> _______________</span>
                        </div>
                        <div class="serial">
                            <span><b>Date:</b></span>
                            <span class="float-right">_______________</span>
                        </div>
                        <div class="serial">
                            <span><b>Fund Cluster:</b></span>
                            <span class="float-right">_______________</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">Payee</th>
                    <td colspan="6"><?= $model->payee->registered_name ?></td>
                </tr>
                <tr>
                    <th colspan="2">Office</th>
                    <td colspan="6"></td>
                </tr>
                <tr class="header">
                    <th colspan="2">Address</th>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <th colspan="1" class="text-center">Responsibility Center</th>
                    <th colspan="2" class="text-center">Particulars</th>
                    <th colspan="2" class="text-center" style="min-width: 150px;">MFO/PAP</th>
                    <th colspan="2" class="text-center" style="min-width: 150px;">UACS Object Code</th>
                    <th colspan="1" class="text-center" style="width: 30px">Amount</th>
                </tr>
                <tr>
                    <?php
                    $row_cnt = count($items) + 1;
                    ?>
                    <td colspan='1' rowspan="<?= $row_cnt ?>" style="vertical-align: top;" class="text-center">
                        <?= !empty($model->responsibilityCenter->name) ? $model->responsibilityCenter->name : '' ?>
                    </td>
                    <td colspan='2' rowspan="<?= $row_cnt ?>" style="padding-bottom: 10rem;max-width:250px">
                        <?php
                        echo $model->particular . ' ';
                        echo !empty($iars) ? 'IAR#: ' : '';
                        echo $iars;
                        ?>
                    </td>
                </tr>

                <?php
                $lst_row =  count($items) - 1;
                $bdr = 'border-bottom:0;border-top:0;';
                $total = 0;
                foreach ($items as $k => $item) {
                    $amount  = number_format($item['amount'], 2);
                    if ($k === $lst_row) {
                        $bdr = 'border-top:0';
                    }
                    echo " <tr><td colspan='2' style='vertical-align: top;min-width: 150px;{$bdr}'>
                                {$item['mfo_name']}
                            </td>
                            <td colspan='2' style='min-width: 150px;{$bdr}'>
                            </td>
                            <td colspan='1' style='vertical-align: top;text-align: right;padding-right:10px;{$bdr}'>
                               {$amount}
                            </td></tr>";
                    $total += floatval($item['amount']);
                }


                ?>
                <tr>
                    <th colspan="7" class="text-center">Total</th>
                    <th class="text-right"><?= number_format($total, 2) ?></th>
                </tr>
                <tr>
                    <td class="p-3 border-bottom-0" colspan="3">A. Certified: Charges to appropriation/allotment
                        are necessary, lawful and under my direct
                        supervision;and supporting documents valid, proper and legal
                    </td>
                    <td colspan="5" class="p-3 border-bottom-0">
                        B. Certified: Allotment available and obligated for the
                        purpose/adjustment necessary as indicated above
                    </td>
                </tr>
                <tr>
                    <td class="border-0" style="vertical-align:top;">Signature</td>
                    <td colspan="2" class="border-top-0 border-left-0 border-bottom-0 text-center pt-4">______________________________________</td>
                    <td colspan="1" class=" border-0">Signature</td>
                    <td colspan="4" class="text-center border-0 pt-4">_______________________________</td>
                </tr>
                <tr>
                    <td style="width: 130px;" class="border-0" style="vertical-align:top;padding:0">Printed Name</td>
                    <td colspan="2" class=" text-center border-bottom-0 border-top-0 border-left-0">
                        <u class="font-weight-bold text-uppercase"> <?= $certifiedBy['fullName'] ?? '' ?></u> <br>
                    </td>
                    <td style="width: 130px;" class="border-0">Printed Name</td>
                    <td colspan="4" class="text-center border-0">
                        <u class="font-weight-bold text-uppercase"> <?= $certifiedBudgetBy['fullName'] ?? '' ?></u> <br>
                    </td>
                </tr>
                <tr>
                    <td class="border-right-0 border-0">Position:</td>
                    <td colspan="2" class="text-center border-bottom-0 border-top-0 border-left-0">
                        <span> <?= $certifiedBy['position'] ?? ''  ?></span>
                    </td>
                    <td class="border-0">Position:</td>
                    <td colspan="4" class="border-0 text-center">
                        <span> <?= $certifiedBudgetBy['position'] ?? '' ?></span>
                    </td>
                </tr>
                <tr>
                    <!-- style="border-top:1px solid white;border-right:1px solid white;" -->
                    <td class="border-0">Date:</td>
                    <td colspan="2" class="text-center border-left-0 border-top-0">
                        ______________________________________

                    </td>
                    <td class="border-0">Date:</td>
                    <td colspan="4" class="text-center border-0">_______________________________</td>
                </tr>
                <tr>
                    <th colspan="8" class="text-center ">STATUS OF OBLIGATION</th>
                </tr>
                <tr>
                    <th class="text-center" colspan="4">REFERENCE</th>
                    <th colspan="4" class="text-center">AMOUNT</th>
                </tr>
                <tr>
                    <th rowspan="2">Date</th>
                    <th rowspan="2">Particular</th>
                    <th rowspan="2">ORS/JEV/Check/ADA/TRA No.</th>
                    <th rowspan="2">Obligation</th>
                    <th rowspan="2">Payable</th>
                    <th rowspan="2">Payment</th>
                    <th colspan="2" class="center">Balance</th>
                </tr>
                <tr>
                    <th>Not Yet Due</th>
                    <th>Due and Demandable</th>
                </tr>

                <?php
                $x = 0;
                foreach (range(1, 6) as $number) {
                    echo "
                    <tr>
                        <td class='p-3'></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
                }
                ?>



            </tbody>
        </table>
        <?php
        // Pjax::end() 
        ?>

    </div>


    <!-- FORM 1 END-->
    <p style='page-break-after:always;'></p>
    <!-- FORM 2-->
    <div class="container card">
        <div style="float:right"><?= $model->tracking_number ?></div>
        <table id="dv_form">
            <tbody>
                <tr>

                    <th colspan="5" class="text-center">
                        <h5 class='font-weight-bold'>Department of Trade and Industry - Caraga</h5>
                        <h5 class='font-weight-bold'>ENTITY NAME</h5>
                        <h5 class='font-weight-bold'>DISBURSEMENT VOUCHER</h5>

                    </th>
                    <th class="text-left border-0">
                        <span>Fund Cluster:</span><br>
                        <span>Date:</span><br>
                        <span>DV No.:</span><br>
                    </th>
                    <th colspan="1" class="border-0">
                        <span class="float-right"> _________________</span>
                        <span class="float-right">_________________</span>
                        <span class="float-right">_________________</span>
                    </th>
                </tr>
                <tr>
                    <th>Mode of Payment</th>
                    <td colspan="6">
                        <div style="display: flex;width:100%;justify-content:space-evenly">
                            <div class="p-0 m-0">
                                <span class='check_box'></span>
                                <span>MDS Check</span>
                            </div>
                            <div class="p-0 m-0">
                                <span class='check_box'></span>
                                <span>Commercial Check</span>
                            </div>
                            <div class="p-0 m-0">
                                <span class='check_box'></span>
                                <span>ADA</span>
                            </div>
                            <div class="p-0 m-0">
                                <span class='check_box'></span>
                                <span>Others (Please specify)</span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th rowspan="2">Payee</th>
                    <th colspan="4" rowspan="2"><?= $model->payee->account_name ?></th>
                    <th>TIN/Employee No.</th>
                    <th>ORS/BURS No.</th>
                </tr>
                <tr>
                    <td class="p-3"></td>
                    <td></td>
                </tr>

                <tr>
                    <th>Address</th>
                    <td colspan="6"></td>
                </tr>
                <tr>

                    <th colspan="3" class="text-center">Particulars</th>
                    <th colspan="2" class="text-center">MFO/PAP</th>
                    <th class="text-center">Responsibility center</th>
                    <th class="text-center">Amount</th>
                </tr>
                <tr>

                    <td colspan='3' rowspan="<?= $row_cnt ?>" style='padding-bottom:10rem'>
                        <?php echo $model->particular . ' ';
                        echo !empty($iars) ? 'IAR#: ' : '';
                        echo $iars;
                        ?>
                    </td>

                </tr>
                <?php
                $r_center = !empty($model->responsibilityCenter->name) ? $model->responsibilityCenter->name : '';
                $r = $row_cnt - 1;
                $bdr = "border-top:0;border-bottom:0;";
                foreach ($items as $k => $item) {
                    $amount  = number_format($item['amount'], 2);
                    if ($k === $lst_row) {
                        $bdr = 'border-top:0';
                    }
                    if ($k === 0) {
                        echo "<tr>
                        <td colspan='2' style='vertical-align: top;min-width: 150px;$bdr'>
                            {$item['mfo_name']}
                        </td>
                        <td style='vertical-align: top; text-align: center;' rowspan=' $r '>
                             $r_center
                        </td>
                        <td colspan='' style='vertical-align: top;min-width: 150px;text-align:right;{$bdr}'>
                            {$amount}
                        </td>
                   </tr>";
                    } else {
                        echo " <tr>
                        <td colspan='3' style='vertical-align: top;min-width: 150px;{$bdr}'>
                            {$item['mfo_name']}
                        </td>
                        <td style='vertical-align: top;min-width: 150px;text-align:right;{$bdr}' >
                            {$amount}
                        </td>

                   </tr>";
                    }
                }
                ?>



                <tr>
                    <th class="text-center" colspan="6">
                        Amount Due
                    </th>
                    <th class="text-right"> <?= number_format($total, 2) ?></th>
                </tr>
                <tr>
                    <td colspan="7" class="border-0">
                        <h6 class="mt-2">A: Certified: Expenses/Cash Advance necessary, lawful and incurred under my direct supervision.</h6>

                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="border-0 text-center pt-5 pb-4">
                        <u class="font-weight-bold text-uppercase"> <?= $certifiedBy['fullName'] ?? '' ?></u> <br>
                        <span> <?= $certifiedBy['position'] ?? '' ?></span>
                    </td>
                </tr>
                <tr>
                    <th colspan="7" class="text-center">B. Accounting Entry</th>
                </tr>
                <tr>
                    <th class="text-center" colspan='4'> Account Title</th>
                    <th class="text-center" colspan="1">UACS Code</th>
                    <th class="text-center">Debit</th>
                    <th class="text-center">Credit</th>
                </tr>
                <?php
                $y = 0;
                while ($y < 4) {

                    echo "
                    <tr>
                        <td class='p-3' colspan='4'></td>
                        <td colspan='1'></td>
                        <td></td>
                        <td></td>
                    </tr>
                    ";
                    $y++;
                }

                ?>
                <tr>
                    <th colspan="4" class="text-left border-bottom-0"> C. Certified</th>
                    <th colspan="3" class="text-left border-0">D. Approved for Payment</th>
                </tr>
                <tr>
                    <td colspan="4" class="pl-3 border-bottom-0 border-top-0">

                        <div>
                            <span class='check_box'></span>
                            <span>
                                Cash Available
                            </span>
                        </div>

                        <div>
                            <span class='check_box'></span>
                            <span>
                                Subject to Authority to Debit Account (when applicable)
                            </span>
                        </div>
                        <div>
                            <span class='check_box'></span>
                            <span>
                                Supporting documents complete and amount claimed
                            </span>
                        </div>



                    </td>
                    <td colspan="3" class="border-0"></td>
                </tr>
                <tr>

                    <td>Signature</td>
                    <td colspan="3"></td>
                    <td style="width: 150px;">Signature</td>
                    <td colspan="3"></td>
                </tr>
                <tr>

                    <td>Printed Name</td>
                    <td colspan="3" class="text-center">
                        <u class="font-weight-bold text-uppercase"> <?= $certifiedCashBy['fullName'] ?? '' ?></u> <br>
                    </td>
                    <td>Printed Name</td>
                    <td colspan="3" class="text-center">
                        <u class="font-weight-bold text-uppercase"> <?= $approvedBy['fullName'] ?? '' ?></u> <br>
                    </td>
                </tr>
                <tr>
                    <td>Position</td>
                    <td colspan="3" class="text-center"><span> <?= $certifiedCashBy['position'] ?? '' ?></span></td>
                    <td>Position</td>
                    <td colspan="3" class="text-center"> <span> <?= $approvedBy['position'] ?? '' ?></span></td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td colspan="3"></td>
                    <td>Date</td>
                    <td colspan='3'></td>
                </tr>
                <!-- LETTER E -->
                <tr>
                    <th colspan="6">E. Receipt Payment</th>
                    <th rowspan="2" class="text-left" style="width: 100px;vertical-align:top">JEV No.</th>
                </tr>
                <tr>

                    <td>Check/ADA No.:</td>
                    <td style="width:170px"></td>
                    <td>Date:</td>
                    <td style="width:120px"></td>
                    <td>Bank Name & Account Number:</td>
                    <td></td>

                </tr>
                <tr>
                    <td>Signature :</td>
                    <td></td>
                    <td>Date:</td>
                    <td style="width:70px"></td>
                    <td>Printed Name:</td>
                    <td></td>
                    <td rowspan="2" style="vertical-align:top">Date:</td>
                </tr>
                <tr>
                    <td colspan="6">Official Receipt No. & Date/Other Documents</td>
                </tr>

            </tbody>
        </table>

    </div>
    <div class="card container allotmentTable" style="padding:2rem">
        <table class=" " id="allotmentTable">
            <thead>
                <tr class="table-info">
                    <th colspan="6" class="text-center">
                        <h4>Allotments</h4>
                    </th>
                </tr>
                <tr>

                    <th>Allotment No.</th>
                    <th>Book</th>
                    <th>Mfo Name</th>
                    <th>Fund Source</th>
                    <th> General Ledger</th>
                    <th>Gross Amount</th>


                </tr>
            </thead>
            <tbody>
                <?php
                $allotmentTotal = 0;
                foreach ($items as $item) {
                    echo "<tr>
                    <td>{$item['allotmentNumber']}</td>
                    <td>{$item['book']}</td>
           
                    <td>{$item['mfo_code']}-{$item['mfo_name']}</td>
                    <td>{$item['fund_source_name']}</td>
                    <td>{$item['account_title']}</td>
                    <td class='text-right'>" . number_format($item['amount'], 2) . "</td>
                    </tr>";
                    $allotmentTotal += floatval($item['amount']);
                }

                ?>
                <tr>
                    <th colspan="5" class="text-center">
                        Total
                    </th>
                    <th class="text-right">
                        <?= number_format($allotmentTotal, 2) ?>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
    if (Yii::$app->user->can('ro_accounting_admin') || Yii::$app->user->can('ro_budget_admin')) {


    ?>
        <div class="container card links">

            <table class=" " id='ors_links'>
                <tr class="table-info">
                    <th colspan="3" class="text-center">
                        <h4>Obligation Links</h4>
                    </th>
                </tr>
                <tr>
                    <th>ORS Number</th>
                    <th>Good/Cancelled</th>
                    <th>Link</th>
                </tr>
                <tbody>

                    <?php
                    $ors = YIi::$app->db->createCommand("SELECT 
                        process_ors.serial_number,
                        process_ors.id,
                        process_ors.is_cancelled
                        FROM 
                        process_ors
                        WHERE
                        process_ors.transaction_id = :id")
                        ->bindValue(':id', $model->id)
                        ->queryAll();
                    foreach ($ors as $val) {
                        $is_cancelled = $val['is_cancelled'] ? 'Cancelled' : 'Good';
                        echo "<tr>
                            <td>{$val['serial_number']}</td>
                            <td>$is_cancelled</td>
                            <td>" . Html::a('ORS Link', ['process-ors/view', 'id' => $val['id']], ['class' => 'btn btn-link ']) . "</td>
                        </tr>";
                    }


                    ?>
                </tbody>
            </table>
        </div>
    <?php  } ?>
    <!-- FORM 2 END-->


</div>

<style>
    .check_box {
        height: 4px;
        border: 1px solid black;
        padding-left: 15px;
        margin: 4px
    }


    .container {
        padding: 12px;
    }


    table,
    td,
    th {
        border: 1px solid black;
        padding: .5rem;
    }



    @media print {



        .links,
        .btn,
        .allotmentTable,
        .main-footer {
            display: none;
        }

        th,
        td {
            padding: 5px;
            font-size: 16px;
        }

        @page {
            size: auto;
            margin: 0;
            margin-top: 0.5cm;
        }


        .entity_name {
            font-size: 5pt;
        }

        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }


    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>


<script src="<?php echo Url::base() ?>/frontend/web/js/jquery.min.js" type="text/javascript"></script>
<link href="<?php echo Url::base() ?>/frontend/web/js/select2.min.js" />
<link href="<?php echo Url::base() ?>/frontend/web/css/select2.min.css" rel="stylesheet" />
<script>
    let asignatory = []
    var positions = []
    const division = '<?= $division ?>';



    $(document).ready(function() {
        console.log('division')
        positions = ['Head', 'Budget', 'Division', 'Unit', 'Authorized Representative']
        $('.position').select2({
            data: positions,
            placeholder: "Select Position",

        })

        $.getJSON('<?php echo Url::base() ?>/frontend/web/index.php?r=assignatory/get-all-assignatory')

            .then(function(data) {

                let array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.name,
                        text: val.name,
                        emp: val.name,

                    })
                    asignatory.push({
                        position: val.position,
                        name: val.name,

                    })
                })
                console.log(asignatory)
                $('.asignatory').select2({
                    data: array,
                    placeholder: "Select ",

                })
                setDefaultSignatory(division)

            })
        $("#ors_form").on('change', '.asignatory', function() {
            const ors_picked_signatory = $(this).val()
            const ors_signatory_position = $(this).attr('data-pos')
            setSignatoryPosition(ors_picked_signatory, ors_signatory_position)
        })
        $("#dv_form").on('change', '.asignatory', function() {
            const picked_signatory = $(this).val()
            const signatory_position = $(this).attr('data-pos')
            setSignatoryPosition(picked_signatory, signatory_position)
        })

    })

    function setSignatoryPosition(signatory_name, signatory_position) {

        $.each(asignatory, function(key, val) {
            if (val.name == signatory_name) {
                $(`.${signatory_position}`).text(val.position)
                return
            }
        })
    }

    function setDefaultSignatory(_division) {
        if (_division == 'sdd') {
            $("#form1_box_a_signatory").val('JASMIN B. FAELNAR').trigger('change')
            $("#form2_box_a_signatory").val('JASMIN B. FAELNAR').trigger('change')
        } else if (_division == 'idd' || _division == 'rapid') {
            $("#form1_box_a_signatory").val('MARSON JAN S. DOLENDO').trigger('change')
            $("#form2_box_a_signatory").val('MARSON JAN S. DOLENDO').trigger('change')
        } else if (_division == 'cpd') {
            $("#form1_box_a_signatory").val('ATTY. KURT CHINO A. MONTERO').trigger('change')
            $("#form2_box_a_signatory").val('ATTY. KURT CHINO A. MONTERO').trigger('change')
        } else if (_division == 'fad') {
            $("#form1_box_a_signatory").val('JOHN VOLTAIRE S. ANCLA').trigger('change')
            $("#form2_box_a_signatory").val('JOHN VOLTAIRE S. ANCLA').trigger('change')
        } else if (_division == 'mssd') {
            $("#form1_box_a_signatory").val('BRENDA B. CORVERA, CESO V').trigger('change')
            $("#form2_box_a_signatory").val('BRENDA B. CORVERA, CESO V').trigger('change')
        } else if (_division == 'ord') {
            $("#form1_box_a_signatory").val('GAY A. TIDALGO, CESO IV').trigger('change')
            $("#form2_box_a_signatory").val('GAY A. TIDALGO, CESO IV').trigger('change')
        }

        $("#form1_box_b_signatory").val('JULIETA B. OGOY').trigger('change')
        $("#form2_box_c_signatory").val('CHARLIE C. DECHOS').trigger('change')
        $("#form2_box_d_signatory").val('GAY A. TIDALGO, CESO IV').trigger('change')
    }
</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

    $('#print').click(function(){
        if (
            $('#assignatory_1').val()==''
            ||$('#assignatory_2').val()==''
            ||$('#assignatory_3').val()==''
            ||$('#assignatory_4').val()==''
            ||$('#assignatory_5').val()==''
        ){
            swal({
                title:'Please Choose Asignatory',
                type:'error',
                button:false,

            })
        }
        else{
            window.print()
        }
    })

JS;
$this->registerJs($script);
?>
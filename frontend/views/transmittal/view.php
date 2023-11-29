<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$approvedBy  = !empty($model->fk_approved_by) ? $model->approvedBy->getEmployeeDetails() : [];
$officerInCharge  = !empty($model->fk_officer_in_charge) ? $model->officerInCharge->getEmployeeDetails() : [];
$date = DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y');

?>
<?= $this->render('/modules/download_pdf_with_header', [
    'date' => $date,
    'serial_number' => $model->transmittal_number,
    'fileName' => 'Transmittal',
    'headerTexts' => MyHelper::getTransmittalPdfHeaderTexts($date, $model->transmittal_number),
]) ?>
<div class="transmittal-view">
    <div class="container card p-2">
        <ul class="notes">
            <li>
                <p class="text-danger">To Print Click Download to PDF</p>
            </li>
        </ul>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button onclick="generatePDF() " class="btn "> <i class="fa fa-file-pdf"></i> Download PDF</button>

        </p>
        <div class="row" style="float:right">
            <div class="col-sm-12">
                <?= Html::img(Yii::$app->request->baseUrl . '/frontend/web/images/dti_header.png', [
                    'alt' => 'some', 'class' => 'pull-left img-responsive',
                    'style' => 'width: 16em;'
                ]); ?>
            </div>
        </div>
        <div class="addresseeInfo p-3">
            <div class="addresseeInfo head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></div>
            <div class="addresseeInfo head" style="font-weight: bold;">ADA JUNE M. HORMILLADA</div>
            <div class="addresseeInfo head">State Auditor III</div>
            <div class="addresseeInfo head">OIC - Audit Team Leader</div>
            <div class="addresseeInfo head">COA - DTI Caraga</div>
            <div class="addresseeInfo head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</div>
            <p style="font-size: 12pt;">
                We are hereby submitting the following DVs, with assigned Transmittal # <?php echo $model->transmittal_number; ?> of DTI Regional Office:
            </p>
        </div>

        <table id="tableData" class="pdf-export">
            <thead>
                </tr>
                <th>No.</th>
                <th>DV Number</th>
                <th>Check/ADA</th>
                <th>Check/ADA Date</th>
                <th>Payee</th>
                <th>Particulars</th>
                <th>Amount Disbursed</th>
                <th>Tax Withheld</th>
            </thead>
            <tbody>

                <?php
                $total = 0;
                $totalTax = 0;
                foreach ($model->getItems() as $i => $val) {
                    $q = $i + 1;
                    echo "<tr>
                        <td>$q</td>
                        <td>{$val['dv_number']}</td>
                        <td>{$val['check_or_ada_no']}</td>
                        <td>{$val['issuance_date']}</td>
                        <td>{$val['payee']}</td>
                        <td>{$val['particular']}</td>
                        <td style='text-align:right'>" . number_format($val['amtDisbursed'], 2) . "</td>
                        <td style='text-align:right'>" . number_format($val['taxWitheld'], 2) . "</td>
                    </tr>";
                    $total += floatval($val['amtDisbursed']);
                    $totalTax += floatval($val['taxWitheld']);
                }
                ?>
                <tr>
                    <th colspan="6" class='text-center'>Total</th>
                    <th class='text-right'> <b><?= number_format($total, 2) ?></b></th>
                    <th class='text-right'> <b><?= number_format($totalTax, 2) ?></b></th>
                </tr>
                <tr>
                    <td colspan="8" class="border-bottom-0 border-right-0 border-left-0 head">
                        <br>
                        Thank you.
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="border-0 pt-2 head">
                        <br>
                        <br>
                        <br>
                        <p class="mt-5">Very truly yours,</p>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th colspan="8" class="border-0 head">
                        <p><?= !empty($approvedBy['fullName']) ? strtoupper($approvedBy['fullName']) : '' ?>
                    </th>
                </tr>
                <tr>
                    <td colspan="8" class="border-0 head">
                        <p><?= !empty($approvedBy['position']) ? $approvedBy['position'] : '' ?></p>

                    </td>
                </tr>
                <?php if (!empty($model->fk_officer_in_charge)) : ?>
                    <tr>
                        <td colspan="8" class="border-0 head">
                            <br>
                            <br>
                            <p> For the Regional Director</p>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="8" class="border-0 head">
                            <b class=""><?= !empty($officerInCharge['fullName']) ? strtoupper($officerInCharge['fullName']) : '' ?></b>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="8" class="border-0 head">
                            <p><?= !empty($officerInCharge['position']) ? $officerInCharge['position'] : '' ?></p>
                            <br>
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<style>
    th,
    td {
        border: 1px solid black;
        padding: 4px;
        font-size: .7rem;
    }





    @media print {

        @page {
            size: A4;
            margin: .4in
                /* Adjust margins as needed */
        }

        #tableData th,
        #tableData td {
            font-size: 15px;
        }


        .main-footer,
        .btn,
        .notes {
            display: none;
        }

        .header-tbl th,
        .header-tbl td,
        .head {
            font-size: 16px;
        }
    }
</style>
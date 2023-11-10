<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = $model->transmittal_number;
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$date = DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y');
$approvedBy  = !empty($model->fk_approved_by) ? $model->approvedBy->getEmployeeDetails() : [];
$officerInCharge  = !empty($model->fk_officer_in_charge) ? $model->officerInCharge->getEmployeeDetails() : [];
?>

<?= $this->render('/modules/download_pdf_with_header', [
    'date' => $date,
    'serial_number' => $model->transmittal_number,
    'headerTexts' => MyHelper::getTransmittalPdfHeaderTexts($date, $model->transmittal_number),
    'fileName' => 'PO Transmittal to Coa'
]) ?>
<div class="transmittal-view">
    <div class="container card" style="padding: 1rem;">
        <ul>
            <li>
                <p class="text-danger">To Print Click Download to PDF</p>
            </li>
        </ul>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <button onclick="generatePDF() " class="btn  generate-pdf"> <i class="fa fa-file-pdf"></i> Download PDF</button>
        </p>


        <div class="addresseeInfo">
            <div class="addresseeInfo head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></div><br>
            <div class="addresseeInfo head" style="font-weight: bold;">ADA JUNE M. HORMILLADA</div>
            <div class="addresseeInfo head">State Auditor III</div>
            <div class="addresseeInfo head">OIC - Audit Team Leader</div>
            <div class="addresseeInfo head">COA - DTI Caraga</div>
            <div class="addresseeInfo head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</div>
            <p style="font-size: 12pt;">

                We are hereby submitting the following DVs, with assigned Transmittal # <?php echo $model->transmittal_number; ?> of DTI Regional Office:
            </p>
        </div>
        <table class="pdf-export">
            <thead style="border-top: 1px solid black;">
                <th class="text-center">No.</th>
                <th class="text-center">Transmittal Number</th>
                <th class="text-center">Date</th>
                <th class="text-center">Total DVs</th>
                <th class="text-center">Total Withdrawals</th>
            </thead>

            <tbody>

                <?php
                $total = 0;
                $ttlDvCnt = 0;
                foreach ($items as $i => $itm) {
                    $q = $i + 1;
                    $edited = '';
                    echo "<tr>
                        <td>$q</td>
                        <td>" . $itm['transmittal_number'] . ' ' . $edited . "</td>
                        <td>{$itm['date']}</td>
                        <td >{$itm['total_dv']}</td>
                        <td style='text-align:right'>" . number_format($itm['total_withdrawals'], 2) . "</td>
                    </tr>";
                    $total += floatval($itm['total_withdrawals']);
                    $ttlDvCnt += intval($itm['total_dv']);
                }
                ?>
                <tr>
                    <th colspan="3" style="font-weight: bold;text-align:center"> Total</th>
                    <th style='text-align:center'> <?= $ttlDvCnt ?></th>
                    <th style='text-align:right'> <?php echo number_format($total, 2); ?></th>
                </tr>
                <tr>
                    <td colspan="8" class="border-bottom-0 border-right-0 border-left-0">
                        <br>
                        Thank you.
                    </td>
                </tr>
                <tr>
                    <td colspan="8" class="border-0 pt-2">
                        <br>
                        <br>
                        <br>
                        <p class="mt-5">Very truly yours,</p>
                        <br>
                        <br>
                    </td>
                </tr>
                <tr>
                    <th colspan="8" class="border-0">
                        <p><?= !empty($approvedBy['fullName']) ? strtoupper($approvedBy['fullName']) : '' ?>
                    </th>
                </tr>
                <tr>
                    <td colspan="8" class="border-0">
                        <p><?= !empty($approvedBy['position']) ? $approvedBy['position'] : '' ?></p>

                    </td>
                </tr>
                <?php if (!empty($model->fk_officer_in_charge)) : ?>
                    <tr>
                        <td colspan="8" class="border-0">
                            <br>
                            <br>
                            <p> For the Regional Director</p>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="8" class="border-0">
                            <b class=""><?= !empty($officerInCharge['fullName']) ? strtoupper($officerInCharge['fullName']) : '' ?></b>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="8" class="border-0">
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
</style>
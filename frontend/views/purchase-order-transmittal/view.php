<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$date = DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y');
$approvedBy  = !empty($model->fk_approved_by) ? $model->approvedBy->getEmployeeDetails() : [];
$officerInCharge  = !empty($model->fk_officer_in_charge) ? $model->officerInCharge->getEmployeeDetails() : [];
$headerTexts = [
    [
        'value' =>  $date,
    ],
    [
        'value' => '',
    ],
    [
        'value' => 'ADA JUNE M.HORMILLADA',
        'fontStyle' => 'bold',
    ],
    [
        'value' => 'State Auditor III',
    ],
    [
        'value' => 'OIC - Audit Team Leader',
    ],
    [
        'value' => 'COA - DTI Caraga',
    ],
    [
        'value' => '',
    ],
    [
        'value' => 'Dear Ma’am Hormillada:',
    ],
    [
        'value' => '',
    ],
    [
        'value' => "       We are hereby submitting the following Purchase Orders, with assigned Transmittal # {$model->serial_number} of DTI Regional Office:",
    ],
];
?>
<?= $this->render('/modules/download_pdf_with_header', [
    'date' => $date,
    'serial_number' => $model->serial_number,
    'fileName' => 'Purchase Order Transmittals',
    'headerTexts' => $headerTexts,

]) ?>
<div class="purchase-order-transmittal-view">



    <div class="container card" style="padding: 1rem;">
        <ul>
            <li>
                <p class="text-danger">To Print Click Download to PDF</p>
            </li>
        </ul>
        <p>
            <?= Yii::$app->user->can('update_purchase_order_transmittal') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => ' btn btn-primary']) : '' ?>
            <button onclick="generatePDF() " class="btn "> <i class="fa fa-file-pdf"></i> Download PDF</button>
        </p>
        <table>
            <tr>
                <td colspan="5" style="border: 0;">
                    <span class=" head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></span><br><br>
                    <b class=" head">ADA JUNE M. HORMILLADA</b><br>
                    <span class=" head">State Auditor III</span><br>
                    <span class=" head">OIC - Audit Team Leader</span><br>
                    <span class=" head">COA - DTI Caraga</span><br>

                </td>
            </tr>
            <tr>

                <td colspan="5" style="border: 0;">
                    <span class=" head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</span><br><br>
                    <p style="font-size: 12pt;">
                        We are hereby submitting the following Purchase Orders, with assigned Transmittal # <?= $model->serial_number ?> of DTI Regional Office:
                    </p>
                </td>
            </tr>
        </table>
        <table class="pdf-export">
            <thead>
                <tr>
                    <th class="text-center">No.</th>
                    <th class="text-center">Division</th>
                    <th class="text-center">PO Number</th>
                    <th class="text-center">Payee</th>
                    <th class="text-center">Purpose</th>
                    <th class="text-center">Amount</th>
                </tr>

            </thead>

            <tbody>

                <?php
                $total = 0;
                foreach ($model->getTransmittalItems() as $i => $val) {

                    $total += floatval($val['total_amount']);
                    $i++;
                    echo "<tr>
                        <td>$i</td>
                        <td class='text-uppercase'>{$val['division']}</td>
                        <td>{$val['serial_number']}</td>
                        <td>{$val['payee']}</td>
                        <td>{$val['purpose']}</td>
                        <td class='amount'>" . number_format($val['total_amount'], 2) . "</td>
                    
                    </tr>";
                }
                ?>
                <tr>

                    <th colspan="4" class="text-center"> Total</th>
                    <th class='text-right'> <?= number_format($total, 2) ?></th>
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

</div>

<style>
    th,
    td {
        border: 1px solid black;
        padding: 4px;
        font-size: .7rem;
    }
</style>
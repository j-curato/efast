<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PurchaseOrderTransmittal */

$approvedBy  = !empty($model->fk_approved_by) ? $model->approvedBy->getEmployeeDetails() : [];
$officerInCharge  = !empty($model->fk_officer_in_charge) ? $model->officerInCharge->getEmployeeDetails() : [];
$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'IAR Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$date = DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y');
?>
<?= $this->render('/modules/download_pdf_with_header', [
    'date' => $date,
    'serial_number' => $model->serial_number,
    'fileName' => 'Transmittal',
    'headerTexts' => MyHelper::getTransmittalPdfHeaderTexts($date, $model->serial_number),
]) ?>
<div class="">
    <div class="container card p-2">

        <p>
            <?= Yii::$app->user->can('update_iar_transmittal') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
            <button onclick="generatePDF() " class="btn "> <i class="fa fa-file-pdf"></i> Download PDF</button>

        </p>

        <table>
            <tr>
                <td colspan="11" style="border: 0;">
                    <span class=" head" style=" margin-bottom:2rem"><?php echo date('F d, Y', strtotime($model->date)) ?></span><br><br>
                    <b class=" head">ADA JUNE M. HORMILLADA</b><br>
                    <span class=" head">State Auditor III</span><br>
                    <span class=" head">OIC - Audit Team Leader</span><br>
                    <span class=" head">COA - DTI Caraga</span><br>

                </td>
            </tr>
            <tr>

                <td colspan="11" style="border: 0;">
                    <span class=" head" style="padding-top: 2rem;padding-bottom: 2rem;">Dear Ma’am Hormillada:</span><br><br>
                    <p style="font-size: 12pt;">
                        We are hereby submitting the following Purchase Orders, with assigned Transmittal # <?= $model->serial_number ?> of DTI Regional Office:
                    </p>
                </td>
            </tr>
        </table>
        <table class="pdf-export">
            <thead style="border-top: 1px solid black;">
                <th>No.</th>
                <th>IAR Number</th>
                <th>IR Number</th>
                <th>RFI Number</th>
                <th>End-User</th>
                <th>Purpose</th>
                <th>Inspector</th>
                <th>Responsible Center</th>
                <th>PO Number</th>
                <th>Payee</th>
                <th>Requested By</th>
            </thead>

            <tbody>

                <?php
                $total = 0;
                foreach ($items as $i => $val) {

                    $iar_number = $val['iar_number'];
                    $ir_number = $val['ir_number'];
                    $rfi_number = $val['rfi_number'];
                    $end_user = $val['end_user'];
                    $purpose = $val['purpose'];
                    $inspector_name = $val['inspector_name'];
                    $division = $val['division'];
                    $po_number = $val['po_number'];
                    $payee_name = $val['payee_name'];
                    $requested_by_name = $val['requested_by_name'];
                    $i++;
                    echo "<tr>
                        <td>$i</td>
                        <td>$iar_number</td>
                        <td>$ir_number</td>
                        <td>$rfi_number</td>
                        <td>$end_user</td>
                        <td>$purpose</td>
                        <td>$inspector_name</td>
                        <td>$division</td>
                        <td>$po_number</td>
                        <td>$payee_name</td>
                        <td>$requested_by_name</td>
                    
                    </tr>";
                }
                ?>

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
<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", []);
?>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 4px;
        font-size: .7rem;
    }
</style>

<script>
    $(document).ready(function() {
        $('#employee').on('change', () => {
            const emp_id = $('#employee').val()
            $.ajax({
                url: window.location.pathname + "?r=employee/search-employee",
                data: {
                    id: emp_id
                },
                success: function(data) {
                    $('#asig_pos').text(data.results.position)
                    $('#signatory').text(data.results.text)
                }
            })
        })


    })
</script>
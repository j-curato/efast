<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Iar */

$this->title = $model->iar_number;
$this->params['breadcrumbs'][] = ['label' => 'Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$end_user = '';
if (!empty($model->inspectionReport->fk_end_user)) {
    $end_user = YIi::$app->db->createCommand("SELECT employee_name FROM employee_search_view WHERE employee_id = :id")->bindValue(':id', $model->inspectionReport->fk_end_user)->queryScalar();
}

function details($sig)
{
    if (!empty($sig['chairperson'])) {

        $GLOBALS['chairperson'] = $sig['chairperson'];
    }

    if (!empty($sig['inspector'])) {
        $GLOBALS['inspector'] = $sig['inspector'];
    }
    if (!empty($sig['property_unit'])) {
        $GLOBALS['property_unit'] = $sig['property_unit'];
    }
    if (!empty($sig['payee'])) {
        $GLOBALS['payee'] = $sig['payee'];
    }
    if (!empty($sig['department'])) {

        $GLOBALS['department'] = $sig['department'];
    }
    if (!empty($sig['po_date'])) {

        $GLOBALS['po_date'] = $sig['po_date'];
    }
    if (!empty($sig['po_number'])) {

        $GLOBALS['po_number'] = $sig['po_number'];
    }
    if (!empty($sig['date_generated'])) {

        $GLOBALS['date_generated'] = $sig['date_generated'];
    }
    if (!empty($sig['inspection_from_date'])) {
        $date_generated = $sig['date_generated'];
        if ($sig['inspection_from_date'] != $sig['inspection_to_date']) {


            $GLOBALS['date_inspected'] = $sig['inspection_from_date'] . ' to ' . $sig['inspection_to_date'];
        } else {

            $GLOBALS['date_inspected'] = $sig['inspection_from_date'];
        }
    }
}
if (!empty($signatories)) {

    details($signatories);
} else if (!empty($noPOsignatories)) {
    details($noPOsignatories);
}


$chairperson = !empty($GLOBALS['chairperson']) ? $GLOBALS['chairperson'] : '';
$inspector = !empty($GLOBALS['inspector']) ? $GLOBALS['inspector'] : '';
$property_unit = !empty($GLOBALS['property_unit']) ? $GLOBALS['property_unit'] : '';
$payee = !empty($GLOBALS['payee']) ? $GLOBALS['payee'] : '';
$department = !empty($GLOBALS['department']) ? $GLOBALS['department'] : '';
$po_date = !empty($GLOBALS['po_date']) ? $GLOBALS['po_date'] : '';
$po_number = !empty($GLOBALS['po_number']) ? $GLOBALS['po_number'] : '';
$date_generated = !empty($GLOBALS['date_generated']) ? $GLOBALS['date_generated'] : '';
$date_inspected = !empty($GLOBALS['date_inspected']) ? $GLOBALS['date_inspected'] : '';


?>
<div class="iar-view">
    <div class="container">
        <p>
            <?= Html::a('IR Link', ['inspection-report/view', 'id' => $model->fk_ir_id], ['class' => 'btn btn-link', 'style' => 'margin-bottom:2rem']) ?>
            <!-- <?= Html::a('Add End-User', ['update', 'id' => $model->id], ['class' => 'btn btn-primary', 'title' => 'Update', 'style' => 'margin-bottom:2rem']); ?> -->
        </p>

        <table class="iar">

            <tbody>
                <tr>
                    <th colspan="4" class='center'> INSPECTION AND ACCEPTANCE REPORT</th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>Entity Name:</span>
                    </th>
                    <th colspan="2">
                        <span>Fund CLuster:</span>
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <span class="bold ">Supplier:</span>
                        <span class="udl_txt"><?= $payee ?></span>
                        <br>
                        <span class="bold">PO No./Date:</span>
                        <span class="udl_txt"><?= $po_number . '; ' . $po_date ?></span>
                        <br>
                        <span class="bold"> Requisitioning Office/Dept:</span>
                        <span class="udl_txt"><?= $department ?></span>
                        <br>
                        <span class="bold"> Responsibility Center Code:</span>
                        <span>_________________________</span>
                    </td>
                    <td colspan="2">
                        <span class="bold"> IAR No.:</span>
                        <span class="udl_txt"><?= $model->iar_number ?></span>
                        <br>
                        <span class="bold"> Date Generated:</span>
                        <span class="udl_txt"><?= $date_generated ?></span>
                        <br>
                        <span class="bold"> Invoice No.:</span>
                        <span>_________________________</span>
                        <br>
                        <span class="bold"> Date:</span>
                        <span>_________________________</span>
                    </td>
                </tr>
                <tr>
                    <th>Stock/Property No.</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                </tr>

                <?php

                if (!empty($items)) {
                    foreach ($items as $val) {
                        echo "<tr>
                            <td class='v-align-top'>{$val['bac_code']}</td>
                            <td>
                                <span class='bold'>{$val['stock_title']}</span><br>
                                <span class='italic'>{$val['specification']}</span>
                            </td>
                            <td class='v-align-top'>{$val['unit_of_measure']}</td>
                            <td class='v-align-top'>{$val['quantity']}</td>
                        </tr>";
                    }
                } else if (!empty($noPOItems)) {

                    foreach ($noPOItems as $val) {
                        echo "<tr>
                            <td class='v-align-top'>{$val['bac_code']}</td>
                            <td>
                                <span class='bold'>{$val['stock_title']}</span><br>
                                <span class='italic'>{$val['specification']}</span>
                            </td>
                            <td class='v-align-top'>{$val['unit_of_measure']}</td>
                            <td class='v-align-top'>{$val['quantity']}</td>
                        </tr>";
                    }
                }
                ?>

                <tr>
                    <th class='center' colspan="2">INSPECTION</th>
                    <th class='center' colspan="2">ACCEPTANCE</th>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: none;"><br>Date Inspected : <span class="udl_txt"><?= $date_inspected ?></span></td>
                    <td colspan="2" style="border-bottom: none;"><br>Date Received : _____________________</td>
                </tr>
                <tr>
                    <td colspan="2" class="center" style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm udl_txt"><?= strtoupper($chairperson) ?></span><br>
                        <span>Inspection Committee, Chairperson</span>
                    </td>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm udl_txt upper-case"><?= strtoupper($property_unit) ?></span><br>
                        <span>Supply and/or Property Custodian</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm udl_txt upper-case"><?= strtoupper($inspector) ?></span><br>
                        <span>Inspection Committee, Member</span>
                    </td>
                    <td colspan="2" style="padding-top:5rem;border-top:none;border-bottom: none;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center upper-case " style="padding-top:5rem;border-top:none">

                        <span class="bold bdr-btm udl_txt "><?= $end_user ?></span><br>
                        <span>End-User/ Project Management Office (PMO)</span>
                    </td>
                    <td colspan="2" style="padding-top:5rem;border-top:none">


                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php if (Yii::$app->user->can('super-user')) { ?>
        <div class="container document_trace">
            <table id="tracking">
                <thead>
                    <tr>
                        <td colspan="8">
                            <span>*Note</span>
                            <ul class="notes">
                                <li> Click the blue or red text to redirect to its view </li>
                                <li>Red Texts means it is cancelled</li>
                            </ul>
                        </td>
                    </tr>
                    <th>Transaction No.</th>
                    <th>ORS No.</th>
                    <th>DV No.</th>
                    <th>Cash Link</th>
                    <th>Check No.</th>
                    <th>ADA No.</th>
                    <th>Check Issuance Date</th>
                    <th>Check Cancelled Period</th>
                </thead>
                <tbody>
                    <?php
                    // transaction_id
                    // txn_num
                    // ors_id
                    // ors_num
                    // ors_is_cancelled
                    // dv_id
                    // dv_num
                    // dv_is_cancelled
                    // check_or_ada_no
                    // ada_number
                    // issuance_date
                    // cancelled_period
                    // cash_cancelled
                    foreach ($paymentTracking as $itm) {

                        echo "<tr>";
                        echo " <td>" . Html::a($itm['txn_num'], ['transaction/view', 'id' => $itm['transaction_id']], ['class' => 'btn btn-link']) . "</td>";
                        echo " <td>" . Html::a($itm['ors_num'], ['process-ors/view', 'id' => $itm['ors_id']], ['style' => $itm['ors_is_cancelled'] == '1' ? 'color:red' : '', 'class' => 'btn btn-link']) . "</td>";
                        echo " <td>" . Html::a($itm['dv_num'], ['dv-aucs/view', 'id' => $itm['dv_id']], ['style' => $itm['dv_is_cancelled'] == '1' ? 'color:red' : '', 'class' => 'btn btn-link']) . "</td>";
                        echo " <td>" . Html::a('cash', ['cash-disbursement/view', 'id' => $itm['cash_id']], ['style' => $itm['cash_cancelled'] == '1' ? 'color:red' : '', 'class' => 'btn btn-link']) . "</td>";
                        echo "<td>" . $itm['check_or_ada_no'] . "</td>";
                        echo "<td>" . $itm['ada_number'] . "</td>";
                        echo "<td>" . $itm['issuance_date'] . "</td>";
                        echo "<td>" . $itm['cancelled_period'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
<?php
// $this->registerCssFile(Yii::$app->request->baseUrl . '/css/customCss.css');
$this->registerCssFile(yii::$app->request->baseUrl . "/css/customCss.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<style>
    .container {
        background-color: white;
        padding: 1rem;
    }

    #tracking {
        width: 100%;
        padding: 12px;
    }

    .notes li {
        color: red;
    }



    th,
    td {
        border: 1px solid black;
        padding: 5px;
    }

    table {
        width: 100%;
    }

    @media print {

        .main-footer,
        .document_trace {
            display: none;
        }

        .btn {
            display: none;
        }

    }
</style>
<?php
$js = <<<JS
    $(document).ready(function(){

        $('a[title=Update]').click(function(e){
            e.preventDefault();
            $('#genericModal').modal('show').find('#modalContent').load($(this).attr('href'));
        });
    })
JS;
$this->registerJs($js);

?>
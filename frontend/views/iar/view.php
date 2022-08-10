<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Iar */

$this->title = $model->iar_number;
$this->params['breadcrumbs'][] = ['label' => 'Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$chairperson = '';
$inspector = '';
$property_unit = '';
$unit_head = '';
$payee = '';
$department = '';
$po_date = '';
$date_generated = '';
$date_inspected = '';
if (!empty($signatories['chairperson'])) {
    $chairperson = $signatories['chairperson'];
}
if (!empty($signatories['unit_head'])) {
    $unit_head = $signatories['unit_head'];
}
if (!empty($signatories['inspector'])) {
    $inspector = $signatories['inspector'];
}
if (!empty($signatories['property_unit'])) {
    $property_unit = $signatories['property_unit'];
}
if (!empty($signatories['payee'])) {
    $payee = $signatories['payee'];
}
if (!empty($signatories['department'])) {
    $department = $signatories['department'];
}
if (!empty($signatories['po_date'])) {
    $po_date = $signatories['po_date'];
}
if (!empty($signatories['date_generated'])) {
    $date_generated = $signatories['date_generated'];
}
if (!empty($signatories['inspection_from_date'])) {
    $date_generated = $signatories['date_generated'];
    if ($signatories['inspection_from_date'] != $signatories['inspection_to_date']) {

        $date_inspected = $signatories['inspection_from_date'] . ' to ' . $signatories['inspection_to_date'];
    } else {

        $date_inspected = $signatories['inspection_from_date'];
    }
}

?>
<div class="iar-view">

    <div class="container">
        <?= Html::a('IR Link', ['inspection-report/view', 'id' => $model->fk_ir_id], ['class' => 'btn btn-primary']) ?>
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
                        <span class="udl_txt"><?= $po_date ?></span>
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
                            <td>{$val['bac_code']}</td>
                            <td>
                                <span class='bold'>{$val['stock_title']}</span><br>
                                <span class='italic'>{$val['specification']}</span>
                            </td>
                            <td>{$val['unit_of_measure']}</td>
                            <td>{$val['quantity']}</td>
                        </tr>";
                    }
                }
                ?>

                <tr>
                    <th class='center' colspan="2">INSEPECTION</th>
                    <th class='center' colspan="2">ACCEPTANCE</th>
                </tr>
                <tr>
                    <td colspan="2" style="border-bottom: none;"><br>Date Inspected : <span class="udl_txt"><?= $date_inspected ?></span></td>
                    <td colspan="2" style="border-bottom: none;"><br>Date Received : _____________________</td>
                </tr>
                <tr>
                    <td colspan="2" class="center" style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm udl_txt"><?= $chairperson ?></span><br>
                        <span>Inspection Committee, Chairperson</span>
                    </td>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm udl_txt"><?= $property_unit ?></span><br>
                        <span>Supply and/or Property Custodian</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm udl_txt"><?= $inspector ?></span><br>
                        <span>IInspection Committee, Member</span>
                    </td>
                    <td colspan="2" style="padding-top:5rem;border-top:none;border-bottom: none;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none">

                        <span class="bold bdr-btm udl_txt "><?= $unit_head ?></span><br>
                        <span>End-User/ Project Management Office (PMO)</span>
                    </td>
                    <td colspan="2" style="padding-top:5rem;border-top:none">


                    </td>
                </tr>
            </tbody>
        </table>
    </div>

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




    th,
    td {
        border: 1px solid black;
        padding: 5px;
    }

    table {
        width: 100%;
    }

    @media print {

        .main-footer {
            display: none;
        }

        .btn {
            display: none;
        }

    }
</style>
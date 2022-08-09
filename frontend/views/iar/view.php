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
$division_chief = '';
$payee = '';

if (!empty($signatories['chairperson'])) {
    $chairperson = $signatories['chairperson'];
}
if (!empty($signatories['division_chief'])) {
    $division_chief = $signatories['division_chief'];
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
?>
<div class="iar-view">

    <div class="container">

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
                    <th colspan="2">
                        <span>Supplier:</span>
                        <span><?= $payee ?></span>
                        <br>
                        <span>PO No./Date:</span>
                        <span>_________________________</span>
                        <br>
                        <span> Requisitioning Office/Dept:</span>
                        <span>_________________________</span>
                        <br>
                        <span> Responsibility Center Code:</span>
                        <span>_________________________</span>
                    </th>
                    <th colspan="2">
                        <span> IAR No.:</span>
                        <span><?= $model->iar_number ?></span>
                        <br>
                        <span> Date Generated:</span>
                        <span></span>
                        <br>
                        <span> Invoice No.:</span>
                        <span></span>
                        <br>
                        <span> Date:</span>
                        <span></span>
                    </th>
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
                    <td colspan="2" style="border-bottom: none;"><br>Date Inspected : ________________________</td>
                    <td colspan="2" style="border-bottom: none;"><br>Date Received : _____________________</td>
                </tr>
                <tr>
                    <td colspan="2" class="center" style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm"><?= $chairperson ?></span><br>
                        <span>Inspection Committee, Chairperson</span>
                    </td>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm"><?= $property_unit ?></span><br>
                        <span>Supply and/or Property Custodian</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none;border-bottom: none;">

                        <span class="bold bdr-btm"><?= $inspector ?></span><br>
                        <span>IInspection Committee, Member</span>
                    </td>
                    <td colspan="2" style="padding-top:5rem;border-top:none;border-bottom: none;">
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center  " style="padding-top:5rem;border-top:none">

                        <span class="bold bdr-btm "><?= $division_chief ?></span><br>
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
</style>
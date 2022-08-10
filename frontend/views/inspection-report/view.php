<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InspectionReport */

$this->title = $model->ir_number;
$this->params['breadcrumbs'][] = ['label' => 'Inspection Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$chairperson = '';
$inspector = '';
$division_chief = '';
$payee = '';
$project_title = '';
$inspect_date = '';
$property_unit = '';
if (!empty($signatories)) {

    $chairperson = strtoupper($signatories['chairperson']);
    $inspector = strtoupper($signatories['inspector']);
    $division_chief = strtoupper($signatories['division_chief']);
    $payee = $signatories['payee'];
    $project_title = $signatories['project_title'];
    $property_unit = $signatories['property_unit'];
    if ($signatories['from_date'] != $signatories['to_date']) {
        $inspect_date = $signatories['from_date'] . ' to ' . $signatories['to_date'];
    } else {
        $inspect_date = $signatories['from_date'];
    }
}
?>
<div class="inspection-report-view">

    <div class="container">
        <?php
        if (!empty($rfi_id)) {
            echo Html::a('RFI Link', ['request-for-inspection/view', 'id' => $rfi_id], ['class' => 'btn btn-primary']);
        }
        if (!empty($iar_id)) {
            echo ' ' . Html::a('IAR Link', ['iar/view', 'id' => $iar_id], ['class' => 'btn btn-warning']);
        }
        ?>
        <table>
            <tr>
                <th colspan="2" class="center">
                    <h4>INSPECTION REPORT</h4>
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="fnt-large" style="font-size:16px">
                        This is to certify that per request of <span class='bdr-btm'><?= $division_chief ?></span> the Undersigned inspected the
                        <span class='bdr-btm'><?= $project_title ?></span> last <span class='bdr-btm'><?= $inspect_date ?></span> The following is /are findings and recommendation/s.
                    </span>
                </td>
            </tr>
            <tr>
                <th colspan="2">A. Specific Description of the item.</th>
            </tr>
            <tr>
                <td colspan="2">



                    <?php
                    foreach ($itemDetails as $val) {

                        echo "<span class='bold'>{$val['stock_title']}</span>
                        <br>
                        <span class='italic' >{$val['specification']}</span>
                        <br>
                        ";
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <th colspan="2">B. Finding/s</th>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 0;padding-bottom:0;padding-left:2rem;border-bottom:1px solid black;">

                    <span>Quantity:</span>

                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 0;padding-bottom:0;padding-left:2rem;border-bottom:1px solid black;">
                    <span>Specifications/Descriptions of the items:
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 0;padding-bottom:0;padding-left:2rem;border-bottom:1px solid black;">
                    <span>Attendance Sheet:</span>
                </td>
            </tr>
            <?php
            for ($i = 0; $i <= 4; $i++) {
                echo "<tr>
                <td colspan='2' style='padding-top: 16px;padding-bottom:0;padding-left:2rem;border-bottom:1px solid black;'>
                  
                </td>
            </tr>";
            }
            ?>
            <tr>
                <th colspan="2">
                    <span>C. Recommendation:</span>
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <span class='box'></span>
                    <span>For Full Acceptance</span><br>
                    <span class='box'></span>
                    <span>For Partial Acceptance/Rejection</span><br>
                    <span class='box'></span>
                    <span>For Rejection</span><br>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <br>
                    Submitted by:
                </td>
            </tr>
            <tr>
                <td class="center">
                    <span class="bold"><?= $inspector ?></span>
                    <br>
                    <span>Inspector</span>
                </td>
                <td class="center">
                    <span class="bold"><?= $chairperson ?></span>
                    <br>
                    <span>Chairperson, Inspection Commitee</span>
                </td>
            </tr>
            <tr>
                <td style="text-align: right;  padding-right:5rem;padding-top:5rem;" colspan='2'>
                    <span>IR No. :</span><span><?= $model->ir_number ?></span>
                </td>
            </tr>
        </table>


    </div>
</div>
<style>
    .italic {
        font-style: italic;
    }

    .bold {
        font-weight: bold;
    }

    .no-bdr-btm {
        border-bottom: none;
    }

    .no-bdr-top {
        border-top: none;
    }

    .no-bdr-left {
        border-left: none;
    }

    .no-bdr-rigt {
        border-right: none;
    }


    .bdr-btm {
        border-bottom: 1px solid;
        padding-left: 5rem;
        padding-right: 5rem;
    }

    .center {
        text-align: center;
    }

    .iar td,
    .iar th {
        border: 1px solid black;
    }

    .container {
        background-color: white;
        padding: 5px;
    }

    table {
        width: 100%;
    }

    td,
    th {
        padding: 5px;
        /* border: 1px solid black; */
    }

    .box {
        height: 4px;
        border: 1px solid black;
        padding-left: 15px;
        margin: 4px
    }

    .line {
        height: 4px;
        border-bottom: 1px solid black;
        padding-left: 100%;

    }

    .no-bdr {
        border: none;
    }

    @media print {
        .main-footer {
            display: none;
        }
    }
</style>
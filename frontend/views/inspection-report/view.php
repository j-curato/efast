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
if (!empty($model->requestForInspectionItem->requestForInspection->fk_chairperson)) {
    $chairperson = Yii::$app->memem->employeeName($model->requestForInspectionItem->requestForInspection->fk_chairperson);
}
if (!empty($model->requestForInspectionItem->requestForInspection->fk_inspector)) {
    $inspector = Yii::$app->memem->employeeName($model->requestForInspectionItem->requestForInspection->fk_inspector);
}
?>
<div class="inspection-report-view">

    <div class="container">
        <table>
            <tr>
                <th colspan="2" class="center">INSPECTION REPORT</th>
            </tr>
            <tr>
                <td colspan="2">
                    <span>
                        This is to certify that per request of _____ the Undersigned inspected the ____ last ____ The following is /are findings and recommendation/s.
                    </span>
                </td>
            </tr>
            <tr>
                <th colspan="2">A. Specific Description of the item.</th>
            </tr>
            <tr>
                <td colspan="2">
                    <span>
                        1 meal and 2 snacks
                    </span>
                    <br>
                    <span>___________________________________________________________________________</span>
                </td>
            </tr>
            <tr>
                <th colspan="2">B. Finding/s</th>
            </tr>
            <tr>
                <td colspan="2">

                    <span>Quantity:</span><br>
                    <span>Specifications/Descriptions of the items:</span><br>
                    <span>Attendance Sheet:</span>
                </td>
            </tr>
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
                <td colspan="2">Submitted by:</td>
            </tr>
            <tr>
                <td class="center">
                    <span><?= !empty($inspector) ? $inspector['employee_name'] : '' ?></span>
                    <br>
                    <span>Inspector</span>
                </td>
                <td class="center"> <span><?= !empty($chairperson) ? $chairperson['employee_name'] : '' ?></span>
                    <br>
                    <span>Chairperson, Inspection Commitee</span>
                </td>
            </tr>
        </table>
    </div>
</div>
<style>
    .center {
        text-align: center;
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
        border: 1px solid black;
    }

    .box {
        height: 4px;
        border: 1px solid black;
        padding-left: 15px;
        margin: 4px
    }
</style>
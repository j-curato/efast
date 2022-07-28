<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspection */

$this->title = $model->rfi_number;
$this->params['breadcrumbs'][] = ['label' => 'Request For Inspections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$chairperson = '';
$inspector = '';
$property_unit = '';
$requested_by = '';

if (!empty($model->fk_chairperson)) {
    $chairperson = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_chairperson)
        ->queryOne();
}
if (!empty($model->fk_inspector)) {
    $inspector = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_inspector)
        ->queryOne();
}
if (!empty($model->fk_property_unit)) {
    $property_unit = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_property_unit)
        ->queryOne();
}
if (!empty($model->fk_requested_by)) {
    $requested_by = Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_requested_by)
        ->queryOne();
}
?>
<div class="request-for-inspection-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <div class="container">
        <table>
            <tr>
                <th colspan="4" class="center">
                    FOR INSPECTION AND ACCEPTANCE
                    <hr>

                </th>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>
                    <br>

                    <span> Date:</span>
                    <?= DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') ?>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td>
                    <span>No.:</span>
                    <?= $model->rfi_number ?>
                </td>
            </tr>
            <tr>
                <th colspan="4" class="center">REQUEST FOR INSPECTION
                    <br>
                    <br>

                </th>
            </tr>
            <tr>
                <td colspan="4">

                    <span style="font-weight: bold;"><?= $chairperson['employee_name'] ?></span>
                    <br>
                    <span>Chairperson</span>
                    <br>
                    <span>Inspection Commitee</span>
                    <br>
                    <span>DTI-Caraga</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span>Madam:</span>
                    <br>
                    <br>

                </td>
                <td colspan="3"></td>
            </tr>
            <tr>
                <td colspan="4">
                    <span>This is to request inspection for the following:</span>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <th class="center">PO No.</th>
                <th class="center">Name of Activity</th>
                <th class="center">Location</th>
                <th class="center">Date</th>
            </tr>
            <?php
            if (!empty($purchase_orders)) {

                foreach ($purchase_orders as $val) {
                    echo "<tr>
                            <td class='center'>{$val['po_number']}</td>
                            <td class='center'>{$val['project_title']}</td>
                            <td class='center'>{$val['place_of_delivery']}</td>
                            <td class='center'>{$val['po_date']}</td>
                        </tr>";
                }
            }
            ?>
            <tr>
                <td colspan="4"><br><br>Requested By</td>
            </tr>
            <tr>
                <td colspan="2" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($requested_by) ? $requested_by['employee_name'] : '' ?></span>
                    <br>
                    <span>Office/Division/Section/Unit Head</span>
                </td>
                <td colspan="2" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($inspector) ? $inspector['employee_name'] : '' ?></span>
                    <br>
                    <span>Name /Signature of Inspector/Date</span>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td colspan="2" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($property_unit) ? $property_unit['employee_name'] : '' ?></span>
                    <br>
                    <span>Name /Signature of Supply/Property Unit Head / Date</span>
                </td>
            </tr>
        </table>
    </div>
</div>
<style>
    th,
    td {

        padding: 5px;
    }

    table {
        width: 100%;
    }

    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    @media print {

        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>
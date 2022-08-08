<?php

use yii\helpers\ArrayHelper;
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
if (!empty($model->fk_requested_by_division)) {
    $requested_by = Yii::$app->db->createCommand("SELECT employee_name,position FROM 
    divisions
    LEFT JOIN employee_search_view ON divisions.fk_division_chief =  employee_search_view.employee_id 
    WHERE divisions.id = :id")
        ->bindValue(':id', $model->fk_requested_by_division)
        ->queryOne();
}
?>
<div class="request-for-inspection-view">




    <div class="container">
        <h5 class='note'>
            *Note:
            <br>
            &emsp;• Click Final button to generate IR.
            <br>
            &emsp;• RFI that is already finalized cannot be edited.
        </h5>
        <p>
            <?php
            if (!$model->is_final) {
                echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ';
                echo Html::a('Final', ['final', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to final this item?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
        </p>

        <table>
            <tr>
                <th colspan="7" class="center">
                    FOR INSPECTION AND ACCEPTANCE
                    <hr>

                </th>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td colspan="2">

                    <span> Date:</span>
                    <?php

                    if (!empty($model->date)) {

                        echo   DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y');
                    } ?>
                </td>
            </tr>
            <tr>
                <td colspan="5"></td>
                <td colspan="2">
                    <span>No.:</span>
                    <?= $model->rfi_number ?>
                </td>
            </tr>
            <tr>
                <th colspan="7" class="center">REQUEST FOR INSPECTION
                    <br>
                    <br>

                </th>
            </tr>
            <tr>
                <td colspan="7">

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
                <td colspan="6"></td>
            </tr>
            <tr>
                <td colspan="7">
                    <span>This is to request inspection for the following:</span>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <th class="center">PO No.</th>
                <th class="center">Name of Activity</th>
                <th class="center">Payee</th>
                <th>Description</th>
                <th class="center">Quantity</th>
                <th class="center">From Date</th>
                <th class="center">To Date</th>
            </tr>
            <?php
            if (!empty($purchase_orders)) {

                foreach ($purchase_orders as $val) {

                    $from_date = DateTime::createFromFormat('Y-m-d', $val['date_from'])->format('F d, Y');
                    $to_date = DateTime::createFromFormat('Y-m-d', $val['date_to'])->format('F d, Y');
                    echo "<tr>
                            <td class='center v-align-top' >{$val['po_number']}</td>
                            <td class='center'>{$val['project_title']}</td>
                            <td class='center'>{$val['payee']}</td>
                            <td >
                            <span class='bold'>{$val['stock_title']}</span>
                            <br>
                            {$val['specification']}
                            </td>
                            <td class='center'>{$val['quantity']}</td>
                            <td class='center'>{$from_date}</td>
                            <td class='center'>{$to_date}</td>
                         
                        </tr>";
                }
            }
            ?>
            <tr>
                <td colspan="7"><br><br>Requested By</td>
            </tr>
            <tr>
                <td colspan="3" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($requested_by) ? $requested_by['employee_name'] : '' ?></span>
                    <br>
                    <span>Office/Division/Section/Unit Head</span>
                </td>
                <td colspan="4" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($inspector) ? $inspector['employee_name'] : '' ?></span>
                    <br>
                    <span>Name /Signature of Inspector/Date</span>
                </td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td colspan="4" class="center">
                    <br>
                    <br>
                    <br>
                    <span style="font-weight: bold;text-decoration:underline;"><?= !empty($property_unit) ? $property_unit['employee_name'] : '' ?></span>
                    <br>
                    <span>Name /Signature of Supply/Property Unit Head / Date</span>
                </td>
            </tr>
        </table>

        <table class="link table table-striped" style="margin-top: 5rem;">

            <thead>
                <tr>
                    <th colspan="2" class="center">
                        <h5>INSPECTION REQUEST LINKS</h5>
                    </th>
                </tr>
                <th style='text-align:center'>RFI No.</th>
                <th>Link</th>
            </thead>
            <tbody>

                <?php
                foreach ($ir_links as $val) {

                    echo "<tr>
                            <td style='text-align:center'>{$val['ir_number']}</td>
                           <td > " . HTML::a('Link', ['inspection-report/view', 'id' => $val['id']], ['class' => 'btn btn-link']) . "</td>
                        </tr>";
                }

                ?>
            </tbody>
        </table>
    </div>
</div>
<style>
    .container {
        background-color: white;
    }

    .note {
        color: red;
        font-style: italic;
    }

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

        th,
        td {
            padding: 3px;
        }

        .link {
            display: none;
        }

        .note {
            display: none;
        }
    }
</style>
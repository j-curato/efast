<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmp */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Supplemental Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
function GetEmployeeData($id)
{
    return Yii::$app->db->createCommand("SELECT employee_id,employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $id)
        ->queryOne();
}
$prepared_by = '';
$reviewed_by = '';
$approved_by = '';
$certified_funds_available_by = '';
if (!empty($model->fk_prepared_by)) {

    $prepared_by = GetEmployeeData($model->fk_prepared_by);
}
if (!empty($model->fk_reviewed_by)) {

    $reviewed_by = GetEmployeeData($model->fk_reviewed_by);
}
if (!empty($model->fk_approved_by)) {

    $approved_by = GetEmployeeData($model->fk_approved_by);
}
if (!empty($model->fk_certified_funds_available_by)) {

    $certified_funds_available_by = GetEmployeeData($model->fk_certified_funds_available_by);
}

?>
<div class="supplemental-ppmp-view">

    <div class="" style="background-color: white;padding:10px;">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>


        <table class="table" style="max-width: 70%;" id="head_table">

            <tbody>
                <tr>

                    <th>Serial Number:</th>
                    <td><?= $model->serial_number ?></td>

                    <th>Budget Year :</th>
                    <td><?= $model->budget_year ?></td>

                    <th>CSE/NON-CSE:</th>
                    <td><?= strtoupper(str_replace('_', '-', $model->cse_type)) ?></td>
                </tr>
                <tr>

                    <th>Office:</th>
                    <td><?= strtoupper($model->office->office_name) ?></td>




                    <th>Division :</th>
                    <td><?= strtoupper($model->divisionName->division) ?></td>

                    <th>Division/Program/Unit:</th>
                    <td><?= strtoupper($model->divisionProgramUnit->name) ?></td>
                </tr>

            </tbody>




        </table>



        <table class="table table-stripe">

            <?php
            $colspan = 1;
            if ($model->cse_type === 'cse') {
                $total = 0;
                $colspan = 10;
                echo "<thead>
                <th>Stock</th>
                <th>Unit of Measure</th>
                <th>Amount</th>
                <th>Total Qty</th>
                <th>January Qty</th>
                <th>February Qty</th>
                <th>March Qty</th>
                <th>Q1 Qty</th>
                <th>April Qty</th>
                <th>May Qty</th>
                <th>June Qty</th>
                <th>Q2 Qty</th>
                <th>July Qty</th>
                <th>August Qty</th>
                <th>September Qty</th>
                <th>Q3 Qty</th>
                <th>October Qty</th>
                <th>November Qty</th>
                <th>December Qty</th>
                <th>Q4 Qty</th>
                </thead><tbody>";

                foreach ($items as $item) {
                    $amt_dsp = number_format($item['amount'], 2);
                    $total += floatval($item['amount']);
                    $q1_qty =
                        intval($item['jan_qty']) +
                        intval($item['feb_qty']) +
                        intval($item['mar_qty']);
                    $q2_qty = intval($item['apr_qty']) +
                        intval($item['may_qty']) +
                        intval($item['jun_qty']);
                    $q3_qty = intval($item['jul_qty']) +
                        intval($item['aug_qty']) +
                        intval($item['sep_qty']);
                    $q4_qty = intval($item['oct_qty']) +
                        intval($item['nov_qty']) +
                        intval($item['dec_qty']);
                    $total_qty =  $q1_qty + $q2_qty + $q3_qty + $q4_qty;
                    echo "<tr>
                <td>{$item['stock_title']}</td>
                <td>{$item['unit_of_measure']}</td>
                <td>{$amt_dsp}</td>
                <td>{$total_qty}</td>
                <td>{$item['jan_qty']}</td>
                <td>{$item['feb_qty']}</td>
                <td>{$item['mar_qty']}</td>
                <td>{$q1_qty}</td>
                <td>{$item['apr_qty']}</td>
                <td>{$item['may_qty']}</td>
                <td>{$item['jun_qty']}</td>
                <td>{$q2_qty}</td>
                <td>{$item['jul_qty']}</td>
                <td>{$item['aug_qty']}</td>
                <td>{$item['sep_qty']}</td>
                <td>{$q3_qty}</td>
                <td>{$item['oct_qty']}</td>
                <td>{$item['nov_qty']}</td>
                <td>{$item['dec_qty']}</td>
                <td>{$q4_qty}</td>
                </tr>
                ";
                }
                echo "    <th colspan='2'>Total</th>
                <td >" . number_format($total, 2) . "</td>
          ";
            } else if ($model->cse_type === 'non_cse') {
                $colspan = 4;
                echo "<tr>
            <th>Budget Year</th>
            <th>MFO/PAP Code</th>
            <th>Activity Name</th>
            <th>Item Code</th>
            <th>Stock Name</th>
            <th>Specification</th>
            <th>Early Procurement?</th>
            <th style='text-align:right'>Amount</th>
            </tr>";
                foreach ($items as $item) {
                    $budget_year = $item['budget_year'];
                    $cse_type = $item['cse_type'];
                    $mfo_code = $item['mfo_code'];
                    $activity_name = $item['activity_name'];
                    $bac_code = $item['bac_code'];
                    $stock_title = $item['stock_title'];
                    $description = $item['description'];
                    $early_procurement = $item['early_procurement'];
                    $amount = number_format($item['amount'], 2);
                    echo "<tr>

                <td>$budget_year</td>
           
                <td>$mfo_code</td>
                <td>$activity_name</td>
                <td>$bac_code</td>
                <td>$stock_title</td>
                <td>$description</td>
                <td>$early_procurement</td>
                <td style='text-align:right'>$amount</td>
                </tr>";
                }
            }
            ?>



            <tr>
                <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">


                    <u class="bold"><?= !empty($prepared_by) ? $prepared_by['employee_name'] : ''  ?></u>
                    <br>
                    <span><?= !empty($prepared_by) ? $prepared_by['position'] : ''  ?></span>
                    <br>
                    <span>Prepared By</span>

                </td>
                <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">
                    <u class="bold"><?= !empty($reviewed_by) ? $reviewed_by['employee_name'] : ''  ?></u>
                    <br>
                    <span><?= !empty($reviewed_by) ? $reviewed_by['position'] : ''  ?></span>
                    <br>
                    <span>Reviewed By</span>
                </td>

            </tr>

            <tr>
                <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">
                    <u class="bold"><?= !empty($certified_funds_available_by) ? $certified_funds_available_by['employee_name'] : ''  ?></u>
                    <br>
                    <span><?= !empty($certified_funds_available_by) ? $certified_funds_available_by['position'] : ''  ?></span>
                    <br>
                    <span>Certified Funds Available By</span>

                </td>
                <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">
                    <u class="bold"><?= !empty($approved_by) ? $approved_by['employee_name'] : ''  ?></u>
                    <br>
                    <span><?= !empty($approved_by) ? $approved_by['position'] : ''  ?></span>
                    <br>
                    <span>Approved By</span>

                </td>
            </tr>
            <?= "</tbody>" ?>
        </table>
    </div>


</div>
<style>
    #head_table th {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

    }
</style>
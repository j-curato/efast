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
            <tr>

                <th>Prepared By:</th>
                <td><?= !empty($prepared_by) ? $prepared_by['employee_name'] : ''  ?></td>

                <th>Reviewed By:</th>
                <td><?= !empty($reviewed_by) ? $reviewed_by['employee_name'] : ''  ?></td>

                <th>Approved By:</th>
                <td><?= !empty($approved_by) ? $approved_by['employee_name'] : ''  ?></td>
                <th>Certified Funds Available By :</th>
                <td><?= !empty($certified_funds_available_by) ? $certified_funds_available_by['employee_name'] : ''  ?></td>
            </tr>
        </tbody>




    </table>



    <table class="table table-stripe">

        <?php

        if ($model->cse_type === 'cse') {
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
                echo "
                <td>{$item['stock_title']}</td>
                <td>{$item['unit_of_measure']}</td>
                <td>{$item['amount']}</td>
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
                ";
            }
            echo "</tbody>";
        } else if ($model->cse_type === 'non_cse') {
            echo "<tbody>";
            foreach ($items as $non_cse) {
                $min_key = min(array_keys($non_cse));
                $type = $non_cse[$min_key]['type'];
                $display_type = ucwords($type);
                $activity_name = $non_cse[$min_key]['activity_name'];
                $fund_source_name = $non_cse[$min_key]['fund_source_name'];
                $early_procurement = $non_cse[$min_key]['early_procurement'];
                $early_procurement_disp = $early_procurement ? 'Yes' : 'No';

                echo "<tr>

                <th>Activity Name:</th>
                <th>$activity_name</th>
                <th>Fund Source:</th>
                <th>$fund_source_name</th>

                <th>Type:</th>
                <th>$display_type</th>
                <th>is this early Procurement? :</th>
                <th>$early_procurement_disp</th>
                </tr>";

                foreach ($non_cse as $non_cse_item) {
                    $stock_title = $non_cse_item['stock_title'];
                    $amount = number_format($non_cse_item['amount'], 2);
                    $quantity = $non_cse_item['quantity'];
                    $description = $non_cse_item['description'];
                    echo "<tr>
                    
                    <td>$stock_title</td>
                    <td>$description</td>
                    <td>$quantity</td>
                    <td>$amount</td>
                    </tr>";
                }
            }
            echo "</tbody>";
        }
        ?>
    </table>


</div>
<style>
    #head_table th {
        text-align: center;
    }
</style>
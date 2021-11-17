<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\InventoryReport */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Inventory Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="inventory-report-view">


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
    <div class="con">

        <table class="property-list-table  " id="list-table">
            <thead>

                <th>Property Number</th>
                <th>PAR Number</th>
                <th>Property Car Number</th>
                <th>PTR Number</th>
                <th>Article</th>
                <th>Model</th>
                <th>Serial Number</th>
                <th>Date Acquired</th>
                <th>Accoutable Person</th>
                <th>Acquisation Amount</th>
            </thead>

            <tbody>
                <?php

                $query  = Yii::$app->db->createCommand("SELECT
            par.par_number,
            IFNULL(ptr.ptr_number,'') as ptr_number,
            par.date as par_date,
            property.property_number,
            property.quantity,
            property.acquisition_amount,
            property.article,
            property.iar_number,
            property.model,
            property.serial_number,
            property.date as date_acquired,
            property_card.pc_number,
            UPPER(recieved_by.employee_name) as accountable_officer
            FROM property_card
			LEFT JOIN par ON  property_card.par_number =par.par_number
            LEFT JOIN property ON par.property_number = property.property_number
            LEFT JOIN employee_search_view as recieved_by ON par.employee_id  = recieved_by.employee_id
            LEFT JOIN ptr ON par.par_number = ptr.par_number
            WHERE property_card.pc_number IN (SELECT inventory_report_entries.pc_number FROM inventory_report_entries 
            WHERE inventory_report_entries.inventory_report_id = :id)
          ")
                    ->bindValue(':id', $model->id)
                    ->queryAll();

                foreach ($query as $val) {
                    echo "<tr>
                    <td>{$val['pc_number']}</td>
                    <td>{$val['property_number']}</td>
                    <td>{$val['par_number']}</td>
                    <td>{$val['ptr_number']}</td>
                    <td>{$val['article']}</td>
                    <td>  {$val['model']}</td>
                    <td>  {$val['serial_number']}</td>
                    <td>  {$val['date_acquired']}</td>
                    <td>  {$val['accountable_officer']}</td>
                    <td>  {$val['acquisition_amount']}</td>

                </tr>";
                }
                ?>


            </tbody>

        </table>
    </div>


</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    table {
        width: 100%;
    }

    .con {
        background-color: white;
        width: 100%;
        padding: 20px;

    }
</style>
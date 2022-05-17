<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Remittance */

$this->title = $model->remittance_number;
$this->params['breadcrumbs'][] = ['label' => 'Remittances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$items = Yii::$app->db->createCommand("SELECT 
payroll.payroll_number,
process_ors.serial_number as ors_number,
dv_aucs.dv_number,
payee.account_name as payee,
accounting_codes.object_code,
accounting_codes.account_title,
remittance_items.amount as remittance_item_amount,
IFNULL(dv_accounting_entries.credit,0) + IFNULL(dv_accounting_entries.debit,0) as amount_to_be_remitted,
        remitted.remitted_amount,
        (IFNULL(dv_accounting_entries.credit,0) + IFNULL(dv_accounting_entries.debit,0)) - IFNULL(  remitted.remitted_amount,0) unremitted_amount

 FROM `remittance_items`
LEFT JOIN dv_accounting_entries ON remittance_items.fk_dv_acounting_entries_id = dv_accounting_entries.id
LEFT JOIN payroll ON dv_accounting_entries.payroll_id = payroll.id
LEFT JOIN process_ors ON payroll.process_ors_id = process_ors.id
LEFT JOIN dv_aucs ON payroll.id = dv_aucs.payroll_id
LEFT JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
LEFT JOIN payee ON remittance_payee.payee_id = payee.id
LEFT JOIN accounting_codes ON dv_accounting_entries.object_code  = accounting_codes.object_code
LEFT JOIN dv_aucs_entries ON dv_accounting_entries.dv_aucs_id = dv_aucs_entries.dv_aucs_id
LEFT JOIN (SELECT 
        remittance_items.fk_dv_acounting_entries_id,
        IFNULL(SUM(remittance_items.amount),0)as remitted_amount
        FROM remittance_items
        INNER JOIN remittance ON remittance_items.fk_remittance_id = remittance.id
WHERE 
remittance.created_at < :create_at 
AND remittance_items.is_removed = 0
        GROUP BY remittance_items.fk_dv_acounting_entries_id) as remitted ON dv_accounting_entries.id = remitted.fk_dv_acounting_entries_id 
WHERE remittance_items.fk_remittance_id = :id
AND remittance_items.is_removed = 0
")
    ->bindValue(':id', $model->id)
    ->bindValue(':create_at', $model->created_at)
    ->queryAll();
?>
<div class="remittance-view">



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>


    <div class="container">
        <h4><?= Html::encode($this->title) ?></h4>
        <table >

            <thead>
                <tr class="primary">
                    <th>Reporting Period</th>
                    <th>Book</th>
                    <th>Type</th>
                    <th colspan="4">

                        <?php
                        if ($model->type === 'adjustment') {
                            echo 'Payee';
                        } else {
                            echo 'Payroll No.';
                        }
                        ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $model->reporting_period ?></td>
                    <td><?php echo $model->book->name ?></td>
                    <td><?php echo str_replace('_', ' ', strtoupper($model->type)) ?></td>
                    <td colspan="4">

                        <?php
                        echo $model->payee->account_name;
                        ?>
                    </td>
                </tr>
                <tr class="danger">
                    <th> Payroll No.</th>
                    <th> ORS No.</th>
                    <th> DV No.</th>
                    <th> Payee</th>
                    <th> Object Code</th>
                    <th> Account Title</th>
                    <th class="amount"> Amount Withheld</th>
                    <th class="amount"> Total Remitted</th>
                    <th class="amount"> Amount UnRemitted</th>
                    <th class="amount"> Remitted</th>

                </tr>
                <?php
                $total_remittance_item_amount = 0;
                $total_amount_to_be_remitted = 0;
                $total_remitted_amount = 0;
                $total_unremitted_amount = 0;
                foreach ($items as $val) {
                    $remittance_item_amount = floatval($val['remittance_item_amount']);
                    $amount_to_be_remitted = floatval($val['amount_to_be_remitted']);
                    $remitted_amount = floatval($val['remitted_amount']);
                    $unremitted_amount = floatval($val['unremitted_amount']);
                    echo "<tr>
                            <td>{$val['payroll_number']}</td>
                            <td>{$val['ors_number']}</td>
                            <td>{$val['dv_number']}</td>
                            <td>{$val['payee']}</td>
                            <td>{$val['object_code']}</td>
                            <td>{$val['account_title']}</td>
                            <td class='amount'>" . number_format($amount_to_be_remitted, 2) . "</td>
                            <td class='amount'>" . number_format($remitted_amount, 2) . "</td>
                            <td class='amount'>" . number_format($unremitted_amount, 2) . "</td>
                            <td class='amount'>" . number_format($remittance_item_amount, 2) . "</td>
                        </tr>";

                    $total_remittance_item_amount += $remittance_item_amount;
                    $total_amount_to_be_remitted += $amount_to_be_remitted;
                    $total_remitted_amount += $remitted_amount;
                    $total_unremitted_amount += $unremitted_amount;
                }
                echo "<tr>
                <td colspan='6' style='text-align:center;font-weight:bold'>TOTAL</td>
               
                <td style='font-weight:bold' class='amount'>" . number_format($total_amount_to_be_remitted, 2) . "</td>
                <td style='font-weight:bold' class='amount'>" . number_format($total_remitted_amount, 2) . "</td>
                <td style='font-weight:bold' class='amount'>" . number_format($total_unremitted_amount, 2) . "</td>
                <td style='font-weight:bold' class='amount'>" . number_format($total_remittance_item_amount, 2) . "</td>
            </tr>";
                ?>

            </tbody>
        </table>
    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;


    }

    .amount {
        text-align: right;
    }
    table{
        width: 100%;
    }
    th,td{
        padding: .8rem;
        font-size: medium;
    }

    @media print {
        .btn {
            display: none;
        }
        .main-footer{
            display: none;
        }
        th,td{
        padding: 4px;
        font-size:12px;
    }
    }
</style>
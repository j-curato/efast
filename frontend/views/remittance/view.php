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
remittance_items.amount,
dv_accounting_entries.debit +dv_accounting_entries.credit as amount_disbursed
 FROM `remittance_items`
INNER JOIN dv_accounting_entries ON remittance_items.fk_dv_acounting_entries_id = dv_accounting_entries.id
INNER JOIN payroll ON dv_accounting_entries.payroll_id = payroll.id
INNER JOIN process_ors ON payroll.process_ors_id = process_ors.id
INNER JOIN dv_aucs ON payroll.id = dv_aucs.payroll_id
INNER JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
INNER JOIN payee ON remittance_payee.payee_id = payee.id
INNER JOIN accounting_codes ON dv_accounting_entries.object_code  = accounting_codes.object_code
INNER JOIN dv_aucs_entries ON dv_accounting_entries.dv_aucs_id = dv_aucs_entries.dv_aucs_id
WHERE remittance_items.fk_remittance_id = :id
AND remittance_items.is_removed = 0
")
    ->bindValue(':id', $model->id)
    ->queryAll();
?>
<div class="remittance-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

    </p>


    <div class="container">
        <table class="table table-striped">

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
                    <th> Amount Remitted</th>
                    <th> Amount To Be Remitted</th>

                </tr>
                <?php
                foreach ($items as $val) {
                    echo "<tr>
                            <td>{$val['payroll_number']}</td>
                            <td>{$val['ors_number']}</td>
                            <td>{$val['dv_number']}</td>
                            <td>{$val['payee']}</td>
                            <td>{$val['object_code']}</td>
                            <td>{$val['account_title']}</td>
                            <td>{$val['amount_disbursed']}</td>
                            <td>{$val['amount']}</td>
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
</style>
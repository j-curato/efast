<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Payroll */

$this->title = $model->payroll_number;
$this->params['breadcrumbs'][] = ['label' => 'Payrolls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payroll-view">


    <div class="container">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('ORS Link', ['process-ors/view', 'id' => $model->process_ors_id], ['class' => ' btn btn-warning ']) ?>

            <?php
            if (!empty($model->dvAucs->id)) {
                echo Html::a('DV Link', ['dv-aucs/view', 'id' => $model->dvAucs->id], ['class' => ' btn btn-link profile-link']);
            }
            ?>
        </p>

        <table class="table table-striped">
            <tbody>
                <tr>
                    <th>Payroll Number:
                        <span><?= $model->payroll_number ?></span>
                    </th>
                    <th>ORS Number:
                        <span><?= $model->processOrs->serial_number ?></span>
                    </th>
                </tr>
                <tr>
                    <th>Amount Disbursed:
                        <span><?= number_format($model->amount, 2) ?></span>
                    </th>
                    <th>Due to BIR: <span> <?= number_format($model->due_to_bir_amount, 2) ?></span></th>
                </tr>
                <tr>
                    <th>
                        Reporting Period:
                        <span><?= DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y') ?></span>
                    </th>

                </tr>
                <tr class="info">
                    <th>Payee</th>
                    <th colspan="3">Account Title</th>
                    <th style='text-align:right;'>Amount</th>
                </tr>
                <?php
                $total_obligation = 0;
                $total_due_to_bir = floatval($model->due_to_bir_amount);
                $total_trust_liab = 0;
                $items = Yii::$app->db->createCommand(" SELECT 
                payee.account_name as payee,
                accounting_codes.object_code,
                accounting_codes.account_title,
                IFNULL(dv_accounting_entries.debit,0) + IFNULL(dv_accounting_entries.credit,0) as amount,
                remittance_payee.object_code as parent_object_code
                
                FROM dv_accounting_entries
                LEFT JOIN remittance_payee ON dv_accounting_entries.remittance_payee_id = remittance_payee.id
                LEFT JOIN payee ON remittance_payee.payee_id = payee.id
                LEFT JOIN accounting_codes ON dv_accounting_entries.object_code = accounting_codes.object_code
                
                           
            WHERE dv_accounting_entries.payroll_id = :id")
                    ->bindValue(':id', $model->id)
                    ->queryAll();
                foreach ($items as $val) {
                    $amount = floatval($val['amount']);
                    echo "<tr>
                        <td>{$val['payee']}</td>
                        <td colspan='3'>{$val['object_code']} - {$val['account_title']}</td>
                        <td style='text-align:right;'>" . number_format($val['amount'], 2) . "</td>
                </tr>";

                    if ($val['parent_object_code'] === '2020101000') {
                        $total_due_to_bir += $amount;
                    } else if (!empty($val['parent_object_code'])) {
                        $total_trust_liab += $amount;
                    }
                    $total_obligation += $amount;
                }

                $_2307 = 0;
                $_1601c = 0;
                if ($model->type === '2307') {
                    $_2307 = $total_due_to_bir;
                } else {
                    $_1601c = $total_due_to_bir;
                }
                ?>

                <tr class="danger">
                    <th>Amount Disbursed</th>
                    <th>2307(EWT)</th>
                    <th>1601c(Compensation)</th>
                    <th>Other Trust Liabilities</th>
                    <th>Total Obligations</th>
                </tr>
                <tr>
                    <td><?= number_format($model->amount, 2) ?></td>
                    <td><?= number_format($_2307, 2) ?></td>
                    <td><?= number_format($_1601c, 2) ?></td>
                    <td><?= number_format($total_trust_liab, 2) ?></td>
                    <td><?= number_format($total_obligation + floatval($model->amount) + floatval($model->due_to_bir_amount), 2) ?></td>
                </tr>

            </tbody>
        </table>



    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    @media print {

        .main-footer,
        .btn {
            display: none;
        }

        #dv-links-table {
            display: none;
        }
    }
</style>
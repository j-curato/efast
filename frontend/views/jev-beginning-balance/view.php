<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JevBeginningBalance */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jev Beginning Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="jev-beginning-balance-view">

    <h1><?= Html::encode($this->title) ?></h1>



    <div class="container">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>
        <table class="table table-striped">
            <thead>
                <th>Object Code</th>
                <th>Account Title</th>
                <th class="amount">Debit </th>
                <th class="amount">Credit</th>
            </thead>
            <tbody>

                <?php
                $query = Yii::$app->db->createCommand("SELECT 

            accounting_codes.object_code,
            accounting_codes.account_title,
            jev_beginning_balance_item.debit,
            jev_beginning_balance_item.credit
            
            FROM jev_beginning_balance_item 
            LEFT JOIN accounting_codes ON jev_beginning_balance_item.object_code = accounting_codes.object_code
            WHERE jev_beginning_balance_item.jev_beginning_balance_id = :id")
                    ->bindValue(':id', $model->id)
                    ->queryAll();
                foreach ($query as $val) {
                    $debit = number_format($val['debit'], 2);
                    $credit = number_format($val['credit'], 2);
                    echo "<tr>
                 <td>{$val['object_code']}</td>
                 <td>{$val['account_title']}</td>
                 <td class='amount'>{$debit}</td>
                 <td class='amount'>{$credit}</td>
            
            </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>
<style>
    .amount {
        text-align: right;
    }

    .container {
        background-color: white;
        padding: 2em;
    }
</style>
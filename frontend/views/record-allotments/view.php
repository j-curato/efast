<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Record Allotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="record-allotments-view">

    <h1><?= Html::encode($this->title) ?></h1>

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
    <div class="container" style="background-color: white;padding:10px">

        <table class="table table-striped">

            <thead>
                <th>
                    General Ledger
                </th>
                <th style='text-align:right'>
                    Amount
                </th>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($model->recordAllotmentEntries as $val) {
                    echo "<tr>
                    <td>{$val->chartOfAccount->general_ledger}</td>
                    <td style='text-align:right'>" . number_format($val->amount, 2) . "</td>
                </tr>";
                    $total += $val->amount;
                }
                ?>
                <tr>
                    <td>
                        <h5>Total</h5>
                    </td>
                    <td style='text-align:right'><?php echo number_format($total, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
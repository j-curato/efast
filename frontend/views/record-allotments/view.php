<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Record Allotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="record-allotments-view">



    <div class="container" style="background-color: white;padding:10px">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        </p>
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
                foreach ($items as $item) {
                    echo "<tr>
                            <td>{$item['uacs']}-{$item['general_ledger']}</td>
                            <td style='text-align:right'>" . number_format($item['amount'], 2) . "</td>
                        </tr>";
                    $total += floatval($item['amount']);
                }
                ?>
                <tr class="warning">
                    <th style="text-align: center;">
                        Total
                    </th>
                    <th style='text-align:right'><?= number_format($total, 2); ?></th>
                </tr>
            </tbody>
        </table>
    </div>

</div>
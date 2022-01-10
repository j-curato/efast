<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-stock-view">




    <div class="container panel panel-default">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th>Stock/Property</th>
                    <td><?= $model->stock ?></td>
                </tr>
                <tr>
                    <th>BAC Code</th>
                    <td><?= $model->bac_code ?></td>
                </tr>
                <tr>
                    <th>Unit of Measure</th>
                    <td><?= $model->unitOfMeasure->unit_of_measure ?></td>
                </tr>
                <tr>
                    <th>Chart of Account</th>
                    <td><?= $model->chartOfAccount->uacs . ' - ' . $model->chartOfAccount->general_ledger  ?></td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><?= number_format($model->amount, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table table-striped">
            <thead>
                <th>
                    <h4>Specifications</h4>
                </th>

            </thead>
            <tbody>

                <?php
                foreach ($model->prStockSpecification as $val) {
                    echo "<tr>
                             <td>$val->description</td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div>
<style>
    .panel {
        padding: 20px;
    }
</style>
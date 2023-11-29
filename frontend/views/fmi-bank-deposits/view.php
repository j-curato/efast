<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDeposits */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-bank-deposits-view">

    <div class="container">

        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary lrgModal']) ?>

            </span>
        </div>
        <div class="card p-3">
            <table class="table table-striped">

                <tr>
                    <th class="text-center" colspan="4">
                        <h3 class="font-weight-bold"><?= $model->fmiBankDepositType->deposit_type ?></h3>
                    </th>
                </tr>
                <tr>
                    <th>Serial Number</th>
                    <td><?= $model->serial_number ?></td>
                    <th>Deposit Date</th>
                    <td><?= $model->deposit_date ?></td>
                </tr>
                <tr>
                    <th>Reporting Period</th>
                    <td><?= $model->reporting_period ?></td>
                    <th>Subproject Serial Number</th>
                    <td><?= $model->fmiSubproject->serial_number ?></td>
                </tr>

                <tr>
                    <th>Province</th>
                    <td><?= $model->fmiSubproject->province->province_name ?></td>
                    <th>Municipality/City</th>
                    <td><?= $model->fmiSubproject->municipality->municipality_name ?></td>
                </tr>
                <tr>
                    <th>Barangay</th>
                    <td><?= $model->fmiSubproject->barangay->barangay_name ?></td>
                    <th>Purok/Sitio</th>
                    <td><?= $model->fmiSubproject->purok ?></td>
                </tr>
                <tr>
                    <th>Grant Amount</th>
                    <td><?= number_format($model->fmiSubproject->grant_amount, 2) ?></td>
                    <th>Equity</th>
                    <td><?= number_format($model->fmiSubproject->equity_amount, 2) ?></td>
                </tr>
                <tr>
                    <th>Particular</th>
                    <td><?= $model->particular ?></td>
                    <th>Deposit Amount</th>
                    <td><?= number_format($model->deposit_amount, 2) ?></td>
                </tr>
            </table>

        </div>
    </div>



</div>
<style>
    th,
    td {

        border: 1px solid #dbd9d9;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }
    }
</style>
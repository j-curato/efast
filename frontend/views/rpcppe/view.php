<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */

$this->title = $model->rpcppe_number;
$this->params['breadcrumbs'][] = ['label' => 'Rpcppes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="rpcppe-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->rpcppe_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->rpcppe_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<div class="con">

    <table>

        <thead>
            <tr>
                <th colspan="11" style="text-align: center;">
                    <span>REPORT ON THE PHYSIICAL COUNT OF PROPERTY, PLANT AND EQUPMENT</span>
                    <br>
                    <span>__________________</span>
                    <br>
                    <span>(Type of Property, Plant and Equipment)</span>
                    <br>
                    <span>As at ________________</span>
                </th>
            </tr>
            <tr>
                <th colspan="11">
                    <span>Fund Cluster: </span>
                    <span>_________________________</span>
                </th>
            </tr>
            <tr>
                <th>
                    <span>For which</span>
                </th>
            </tr>
            <tr>
                <th rowspan="2">ARTICLE</th>
                <th rowspan="2">DESCRIPTION</th>
                <th rowspan="2">PROPERTY NUMBER</th>
                <th rowspan="2">UNIT OF MEASURE</th>
                <th rowspan="2">UNIT VALUE</th>
                <th rowspan="2">QUANTITY per PROPERTY CARD</th>
                <th rowspan="2">QUANTITY PER PHYSICAL COUNT</th>
                <th rowspan="" colspan="2">SHORTAGE / OVERAGE</th>
                <th rowspan="2">REMARKS</th>
            </tr>
            <tr>
                <th>Quantity</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            
        </tfoot>
    </table>
    </div>

</div>
<style>
    .con{
        width: 100%;
        background-color: white;
    }
    table{
        width: 100%;
    }
    th,
    td {
        border: 1px solid black;
        padding: 10px;
    }
</style>
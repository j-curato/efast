<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-iar-view">

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


    <div class="container">
        <table>

            <tbody>
                <tr>
                    <th colspan="4"> INSPECTION AND ACCEPTANCE REPORT</th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>Entity Name:</span>
                    </th>
                    <th colspan="2">
                        <span>Fund CLuster:</span>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>Supplier:</span>
                        <br>
                        <span>PO No./Date:</span>
                        <br>
                        <span> Requisitioning Office/Dept:</span>
                        <br>
                        <span> Requisitioning Office/Dept.:</span>
                    </th>
                    <th colspan="2">
                        <span> IAR No.:</span>
                        <br>
                        <span> Date:</span>
                        <br>
                        <span> Invoice No.:</span>
                        <br>
                        <span> Date:</span>
                    </th>
                </tr>
                <tr>
                    <th>Stock/Property No.</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Quantity</th>
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
</style>
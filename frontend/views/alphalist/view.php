<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Alphalist */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="alphalist-view">

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

        <table class="" id="conso_table">
            <tbody></tbody>
        </table>
        <table class="" id="detailed_table" style="margin-top: 5rem;">
            <thead>
                <th>DV Number</th>
                <th>Check Date</th>
                <th>Check Number</th>
                <th>Payee</th>
                <th>Gross Amount</th>
                <th>Withdrawals</th>
                <th>Liquidation Damages</th>
                <th>Total Sales Tax (VAT/Non-VAT)</th>
                <th>Income Tax (Expanded Tax)</th>
                <th>Total Tax</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>

</div>
<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    table {
        width: 100%;
    }

    th,
    td {
        border: 1px solid black;
        padding: .8rem;
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    @media print {

        th,
        td {
            padding: 3px;
            font-size: 10px;
        }

        .container {
            padding: 0;

        }

        .btn {
            display: none;
        }

        .main-footer {
            display: none;
        }

        .total {
            font-weight: bold;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/alphalistJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        const res = JSON.parse(<?php echo json_encode($res) ?>);

        $('#conso_table tbody').html('')
        $('#detailed_table tbody').html('')

        displayConsoHead(res.r)
        displayConso(res.conso, res.r)
        displayDetailed(res.detailed)
    })
</script>
<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RoAlphalist */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$btn_color = 'btn-success';
$btn_name = 'Final';
if ($model->is_final == true) {
    $btn_color = 'btn-danger';
    $btn_name = 'UnFinal';
}
?>
<div class="ro-alphalist-view">


    <h5 style="font-weight:bold;color:red;font-style:italic" class="note">
        *NOTE: IF THE ALPHALIST IS FINAL AND YOU NEED TO UPDATE THE DATA JUST UNFINAL THE ALPHALIST AND THEN FINAL AGAIN
    </h5>
    <p style="margin-top: 6rem;">
        <!-- <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?> -->

        <?= Html::a($btn_name, ['final', 'id' => $model->id, 'reporting_period' => $model->reporting_period], [
            'class' => "btn $btn_color",
            'data' => [
                'confirm' => 'Are you sure you want to final this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="con">

        <table class="" id="conso_table">
            <tbody></tbody>
        </table>
        <table class="" id="detailed_table" style="margin-top: 5rem;">
            <thead>
                <th>Book</th>
                <th>DV Number</th>
                <th>Check Date</th>
                <th>Check Number</th>
                <th>Amount Disbursed</th>
                <th>Compensation</th>
                <th>Other Trust Liabilities</th>
                <th>Total Sales Tax (VAT/Non-VAT)</th>
                <th>Income Tax (Expanded Tax)</th>
                <th>Total Tax</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>


</div>
<style>
    #detailed_table {
        width: 100%;
    }

    #conso_table {
        width: 50%;
    }

    .total {
        font-weight: bold;
    }

    .con {
        background-color: white;
        padding: 2rem;
    }

    th,
    td {
        border: 1px solid black;
        padding: .5rem;
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    .total {
        font-weight: bold;
    }

    @media print {
        .btn {
            display: none
        }

        th,
        td {
            padding: 5px;
            font-size: 10px;
        }
table {
    margin: 0;
}
        .note {
            display: none;
        }

        .main-footer {
            display: none;
        }
    }
</style>
<?php
SweetAlertAsset::register($this);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/roAlphalistJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {
        const data = <?php echo $data ?>;
        displayConsoHead(data.reporting_periods)
        displayConso(data.conso, data.reporting_periods)
        displayDetailedTable(data.detailed)
    })
</script>
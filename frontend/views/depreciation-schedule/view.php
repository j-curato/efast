<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\DepreciationSchedule */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Depreciation Schedules', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$this->registerCssFile("@web/frontend/views/depreciation-schedule/styles.css", ['depends' => YiiAsset::class]);

?>
<div class="depreciation-schedule-view panel">
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Jev', ['jev-preparation/create', 'depSchedId' => $model->id], ['class' => 'btn btn-warning']) ?>
    </p>

    <div class="qwe">

        <table class="table" id="data_tbl">
            <thead>
                <th>Property Number</th>
                <th>Article</th>
                <th>Description</th>
                <th>Date Acquired</th>
                <th>Acquisation Amount</th>
                <th>Book</th>
                <th>Acquisition Cost</th>
                <th>Salvage Value
                    <br>
                    (at least 5% of Cost, rounded to nearest ones)
                </th>
                <th>Depreciable Amount</th>
                <th>1st month of Depn.</th>
                <th>2nd to the last month</th>
                <th> Useful Life in Months</th>
                <th>Monthly Depreciation
                    <br>
                    (from 1st month to 2nd to the last month, rounded to the nearest ones)
                </th>

                <th>
                    Last Month
                </th>
                <th>
                    Monthly Depreciation
                    <br>
                    (Last Month)
                </th>
                <th>
                    Account Title
                </th>
                <th>
                    Derecognition Period
                </th>
                <th>
                    Reporting Period <?= $model->reporting_period; ?> Depreciated Amount
                </th>




            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<style>
    @media print {

        .btn,
        .main-footer,
        .prt-hdn {
            display: none;
        }
        td,th{
            font-size: smaller;
            padding: 2px;
        }
    }
</style>
<?php
 
$this->registerJsFile("@web/js/moment.min.js", ['depends' => [JqueryAsset::class]]);
$this->registerJsFile("@web/frontend/views/depreciation-schedule/script.js", ['depends' => [JqueryAsset::class]]);
?>
<script>
    $(document).ready(() => {
        let data = <?= json_encode($data) ?>;
        display(data)
    })
</script>
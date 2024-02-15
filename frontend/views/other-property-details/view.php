<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\OtherPropertyDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Other Property Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);;;

$useful_life_in_mnths = $model->useful_life;

$first_month =  $propertyDetails['date'];
?>
<div class="other-property-details-view card" style="padding:1rem">
    <p>
        <?= Yii::$app->user->can('update_other_property_details') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => ' btn btn-primary']) : '' ?>
    </p>
    <div>

        <span><b> Property Number:</b></span>
        <span><?= $propertyDetails['property_number'] ?></span>
        <br>
        <span><b> Article:</b></span>
        <span><?= $propertyDetails['article'] ?></span>
        <br>
        <span><b> Item Brand/Model:</b></span>
        <span><?= $propertyDetails['description'] ?></span>
        <br>
        <span><b> Serial Number:</b></span>
        <span><?= $propertyDetails['serial_number'] ?></span>
        <br>
        <span><b> Date Acquired:</b></span>
        <span><?= $propertyDetails['date'] ?></span>
        <br>
        <span><b> Asset Sub Account:</b></span>
        <span><?= !empty($model->assetSubAccount->object_code) ? $model->assetSubAccount->object_code . '-' . $model->assetSubAccount->name : '' ?></span>
        <br>
        <span><b> Depreciation Sub Account:</b></span>
        <span><?= !empty($model->depreciationSubAccount->object_code) ? $model->depreciationSubAccount->object_code . '-' . $model->depreciationSubAccount->name : '' ?></span>
        <br>
    </div>

    <table id="computation_table">
        <thead>
            <tr style="background-color: #2ab3f7;">
                <th colspan="11">
                    <h4>DEPRECIATION</h4>
                </th>
            </tr>

            <th>Book</th>
            <th>Acquisition Cost</th>
            <th>Salvage Value
                <br>
                (at least 5% of Cost, rounded to nearest ones)
            </th>
            <th>Depreciable Amount</th>
            <th>1st month of Depn.</th>
            <th>2nd to the last month</th>
            <th>No. of months <br>(from 1st month to 2nd to the last month)</th>
            <th>Monthly Depreciation
                <br>
                (from 1st month to 2nd to the last month, rounded to the nearest ones)
            </th>
            <th>
                Total Depreciation
                <br>
                (from 1st month to 2nd to the last month)
            </th>
            <th>
                Last Month
            </th>
            <th>
                Monthly Depreciation
                <br>
                (Last Month)
            </th>


        </thead>
        <tbody></tbody>
    </table>
</div>
<style>
    th,
    td {
        text-align: center;
        padding: 5px;
        border: 1px solid black;
    }

    .effect_table td {
        border: 1px solid black;
        padding: 12px;
    }

    .panel {
        padding: 3rem;
    }

    table {
        width: 100%;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/moment.min.js");
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/views/other-property-details/otherPropertyDetailsJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>


<script>
    $(document).ready(() => {
        const frst_month = <?php echo json_encode($first_month) ?>;
        var startDate = new Date('2020-03-01');
        var endDate = new Date('2025-03-01');
        let c = 1
        while (startDate <= endDate) {
            var year = startDate.getFullYear();
            var month = startDate.getMonth();
            console.log(year + '-' + (month)); // Or perform any other action with the year and month
            startDate.setFullYear(startDate.getFullYear() + 1);
            console.log(c)
            c++
        }
        calculateAndDisplay(
            <?= json_encode($items) ?>,
            <?= $model->salvage_value_prcnt ?>,
            <?= $useful_life_in_mnths ?>,
            frst_month
        )

    })
</script>
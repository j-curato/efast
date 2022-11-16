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

$useful_life_in_mnths = YIi::$app->db->createCommand("SELECT 
ppe_useful_life.life_from
FROM chart_of_accounts
LEFT JOIN ppe_useful_life ON chart_of_accounts.fk_ppe_useful_life_id = ppe_useful_life.id
 WHERE chart_of_accounts.id = :id")
    ->bindValue(':id', $model->fk_chart_of_account_id)
    ->queryScalar();

$first_month =  $model->first_month_depreciation;
?>
<div class="other-property-details-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <div>

        <span> Property Number:</span>
        <span><?= $propertyDetails['property_number'] ?></span>
        <br>
        <span> Article:</span>
        <span><?= $propertyDetails['article'] ?></span>
        <br>
        <span> Item Brand/Model:</span>
        <span><?= $propertyDetails['description'] ?></span>
        <br>
        <span> Serial Number:</span>
        <span><?= $propertyDetails['serial_number'] ?></span>
        <br>
        <span> Date Acquired:</span>
        <span><?= $propertyDetails['date'] ?></span>
        <br>
        <span> Related PAR Number:</span>
        <span></span>
        <br>
        <span> Accountable Officer:</span>
        <span></span>
        <br>
    </div>
    <?php

    if (intval($model->depreciation_schedule) > 1) {
    ?>
        <table class="effect_table">
            <?php foreach ($effect_of_adjustment as $book_name => $books) {
                $row = "<tr><td>$book_name</td>";

                foreach ($books as $book) {

                    foreach ($book as $depreciation_schedule) {
                        $row .= "<td>" . $depreciation_schedule['total_depreciated'] . "</td>";
                    }
                }
                $row .= "</tr>";
                echo $row;
            }
            ?>
        </table>
    <?php
    }
    ?>
    <table id="computation_table" class="table">
        <thead>
            <tr>
                <td>


                </td>
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
    }

    .effect_table td {
        border: 1px solid black;
        padding: 12px;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/moment.min.js");
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/views/other-property-details/otherPropertyDetailsJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<script>
    $(document).ready(() => {
        const frst_month = <?php echo json_encode($first_month) ?>;

        calculateAndDisplay(
            <?= json_encode($items) ?>,
            <?= $model->salvage_value_prcnt ?>,
            <?= $useful_life_in_mnths * 12 ?>,
            frst_month
        )

    })
</script>
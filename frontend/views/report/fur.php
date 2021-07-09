<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\MajorAccounts;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Conso DV's";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index panel" style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">
            <div class="col-sm-3">
                <label for="reporting_period">Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3">
                <label for="book_id">Book</label>
                <?php

                echo Select2::widget([
                    'data' => ArrayHelper::map(Books::find()

                        ->asArray()->all(), 'id', 'name'),
                    'name' => 'book_id',
                    'id' => 'book_id',
                    'pluginOptions' => [
                        'placeholder' => 'Select Book',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>
                <!-- 
                        5010000000
5060000000
                    5020000000
                 -->

            </div>
            <div class="col-sm-3">
                <label for="allotment_class">Allotment Class</label>
                <?php

                echo Select2::widget([
                    'data' => ArrayHelper::map(Yii::$app->db->createCommand("SELECT * FROM `major_accounts` WHERE object_code IN (5010000000,5060000000,5020000000)")->queryAll(), 'name', 'name'),
                    'name' => 'allotment_class',
                    'id' => 'allotment_class',
                    'pluginOptions' => [
                        'required' => true,
                        'placeholder' => 'Select Allotment',
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>
            <div class="col-sm-3" style="margin-top: 2.5rem;">
                <button class="btn btn-success" id="generate">Generate</button>
            </div>

        </div>
        <div class="col-sm-3">

        </div>
    </form>

    <!-- <div id="con"> -->
    <table>
        <thead>
            <th>Beginning Balance</th>
            <th>Beginning Balance</th>
            <th>Fund Recieved for the Month</th>
            <th>TOtal Disbursement for the Month</th>
            <th>Ending Balance</th>
        </thead>
    </table>
    <table class="" id="data_table">

        <thead>
            <th>Fund Source</th>
            <th>Beginning Balance</th>
            <th class="amount">Cash Advance for the month</th>
            <th class="amount">Total Liquidation For the Month</th>
            <th class="amount">Disbursements</th>
            <th class="amount">Ending Balance</th>
            <th>Particulars</th>
            <th>Advance Type</th>
            <th>SL Account Code</th>
        </thead>
        <tbody>

        </tbody>
    </table>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }

    #con {
        display: none;
        margin-top: 20px;
    }

    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    .amount {
        text-align: right;
    }

    @media print {

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/jquery.dataTables.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?php
SweetAlertAsset::register($this);
$script = <<< JS

JS;
$this->registerJs($script);
?>
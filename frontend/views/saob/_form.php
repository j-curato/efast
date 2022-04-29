<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use app\models\AdvancesEntries;
use app\models\Books;
use app\models\DvAucs;
use app\models\FundSourceType;
use app\models\MajorAccounts;
use app\models\MfoPapCode;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use Mpdf\Tag\Select;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "SAOB";
$this->params['breadcrumbs'][] = $this->title;

$from_reporting_period = '';
$to_reporting_period = '';
$book_id = '';
$mfo_id = '';
$document_recieve_id = '';

if (!empty($model->id)) {
    $from_reporting_period = $model->from_reporting_period;
    $to_reporting_period = $model->to_reporting_period;
    $book_id = $model->book_id;
    $mfo_id = $model->mfo_pap_code_id;
    $document_recieve_id = $model->document_recieve_id;
}
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px">



    <form id="filter">
        <div class="row">

            <div class="col-sm-3">
                <label for="from_reporting_period"> From Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'from_reporting_period',
                    'id' => 'from_reporting_period',
                    'value' => $from_reporting_period,
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
                <label for="to_reporting_period"> To Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'to_reporting_period',
                    'id' => 'to_reporting_period',

                    'value' => $to_reporting_period,
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
            <div class="col-sm-2">
                <label for="mfo_code">MFO/PAP Code</label>
                <?php

                $data = Yii::$app->db->createCommand("SELECT 'all' as id, 'ALL' as `new_text`
                UNION
                SELECT id,CONCAT(code,'-',`name`) as new_text FROM mfo_pap_code
                 ")->queryAll();
                // ->where('code IN (100000100001000,)')

                echo Select2::widget([
                    'name' => 'mfo_code',
                    'id' => 'mfo_code',
                    'data' => ArrayHelper::map($data, 'id', 'new_text'),
                    'value' => $mfo_id,
                    'options' => ['placeholder' => 'Select MFO/PAP'],

                ]);
                ?>
            </div>
            <div class="col-sm-2">
                <label for="document_recieve">Document Recive</label>
                <?php

                $data = Yii::$app->db->createCommand("SELECT 'all' as id, 'ALL' as `name`
                UNION
                SELECT id,`name`FROM document_recieve 
                ")->queryAll();
                // ->where('code IN (100000100001000,)')
                // var_dump($data);
                echo Select2::widget([
                    'name' => 'document_recieve',
                    'id' => 'document_recieve',
                    'data' => ArrayHelper::map($data, 'id', 'name'),
                    'value' => $document_recieve_id,
                    'options' => ['placeholder' => 'Select Document'],

                ]);
                ?>
            </div>
            <div class="col-sm-2">
                <label for="book_id">Books</label>
                <?php

                $data = Yii::$app->db->createCommand("SELECT id,`name`FROM books 
                ")->queryAll();
                // ->where('code IN (100000100001000,)')
                // var_dump($data);
                echo Select2::widget([
                    'name' => 'book_id',
                    'id' => 'book_id',

                    'data' => ArrayHelper::map($data, 'id', 'name'),
                    'value' => $book_id,
                    'options' => ['placeholder' => 'Select Book'],

                ]);
                ?>
            </div>
            <div class="col-sm-2" style="margin-top: 2.5rem;">
                <button class="btn btn-primary" type='button' id="generate">Generate</button>
                <button class="btn btn-success" type='submit' id="save">save</button>
            </div>

        </div>

    </form>

    <!-- <div id="con"> -->

    <div id='con'>
        <table id="summary_table">
            <thead>
                <tr>

                    <th rowspan="2"> MFO/PAP </th>
                    <th rowspan="2"> Document Recieve</th>
                    <th rowspan="2">Prev Allotment</th>
                    <th rowspan="2">Current Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>

                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody>

            </tbody>

        </table>

        <table class="" id="fur_table" style="margin-top: 30px;">
            <thead>
                <tr>

                    <th rowspan="2">Project / Program</th>
                    <th rowspan="2">Prev Allotment</th>
                    <th rowspan="2">Allotment</th>
                    <th colspan="3">Obligation</th>
                    <th rowspan="2">BALANCES</th>
                    <th rowspan="2"> UTILIZATION</th>
                    <th rowspan="2"> MFO/PAP </th>
                    <th rowspan="2"> Document Recieve</th>
                </tr>
                <tr>
                    <th>Last Month</th>
                    <th>This Month</th>
                    <th>To Date</th>
                </tr>




            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- </div> -->
    <div id="dots5" style="display: none;">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        text-align: center;
        padding: 12px;
    }

    #summary_table {
        margin-top: 30px;
    }

    /* #con {
        display: none;
    } */

    .amount {
        text-align: right;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

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
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/scripts.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/saobJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $('#filter').submit(function(e) {
        e.preventDefault()

        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=saob/create',
            data: $('#filter').serialize(),
            success: function(data) {
                console.log(data)
            }
        })

    })
    $('#generate').click((e) => {
        e.preventDefault();
        $('#con').hide()
        $('#dots5').show()
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=saob/generate',
            data: $("#filter").serialize(),
            success: function(data) {
                var res = JSON.parse(data)
                // var detailed = res.detailed
                console.log(res)
                addData(res.result, res.allotments)
                addToSummaryTable(res.conso_saob)

                $('#con').show()
                $('#dots5').hide()
            }

        })
    })


</script>
<?php
SweetAlertAsset::register($this);
$script = <<< JS
    var month= ''
    var year=''
    var province={
        'adn' : 'Agusan Del Norte',
        'ads' : 'Agusan Del Sur',
        'sdn' : 'Surigao Del Norte',
        'sds' : 'Surigao Del Sur',
        'pdi' : 'Province of Dinagat Islands',
    }
JS;
$this->registerJs($script);
?>
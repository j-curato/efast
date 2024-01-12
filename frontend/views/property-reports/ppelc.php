<?php

use app\models\Books;
use app\models\DvAucs;
use app\models\Office;
use app\models\PoTransmittal;
use kartik\grid\GridView;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\JqueryAsset;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Report";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppelc-index card">


    <form id='filter'>

        <div class="row">
            <?php

            if (Yii::$app->user->can('ro_property_admin')) {

            ?>
                <div class="col-sm-2">
                    <label for="office">Office</label>
                    <?= Select2::widget([
                        'name' => 'office_id',
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office'
                        ]
                    ])
                    ?>
                </div>
            <?php } ?>
            <div class="col-sm-2">
                <label for="reporting_period">Reporting Period</label>
                <?= DatePicker::widget([
                    'name' => 'reporting_period',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true,
                    ]
                ])
                ?>
            </div>
            <div class="col-sm-2">
                <label for="book_id">Book</label>
                <?= Select2::widget([
                    'name' => 'book_id',
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ])
                ?>
            </div>

            <div class="col-sm-2">
                <label for="uacs">UACS</label>
                <?= Select2::widget([
                    'name' => 'uacs',
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=other-property-details/search-chart-of-accounts',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(property_number) { return property_number.text; }'),
                        'templateSelection' => new JsExpression('function (property_number) { return property_number.text; }'),
                    ],

                ]) ?>
            </div>

            <div class="col-sm-2">
                <label for="employee_id">Accountable Officer</label>
                <?= Select2::widget([
                    'name' => 'employee_id',
                    'options' => ['placeholder' => 'Search Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=employee/search-employee',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-2">
                <label for="generate">Action</label>
                <br>
                <button class="btn btn-primary" type="submit">Generate</button>
            </div>
        </div>
    </form>
    <div class="container">

        <table id="data_table">
            <thead>

                <tr>
                    <th colspan="11" class="ctr"> PROPERTY, PLANT AND EQUIPMENT LEDGER CARD</th>
                </tr>
                <tr>
                    <th colspan="8">Entity Name:
                        <u>Department of Trade and Industry</u>
                    </th>
                    <th colspan="3">Fund Cluster: <u>Book Name</u></th>
                </tr>
                <tr>
                    <th colspan="8">Property, Plant and Equipment:</th>
                    <th rowspan="2" colspan="3" style="min-width:220px">
                        Object Account Code:___________________ <br>
                        Estimated Useful Life:___________________ <br>
                        Rate of Depreciation:___________________ <br>
                    </th>
                </tr>
                <tr>
                    <th colspan="8">Description</th>

                </tr>
                <tr>
                    <th class="ctr" rowspan="2">Date</th>
                    <th class="ctr" rowspan="2">Reference</th>
                    <th class="ctr" colspan="3">Receipt</th>
                    <th class="ctr" rowspan="2"> Accumulated Depreciation</th>
                    <th class="ctr" rowspan="2"> Accumulated <br> Impairment Losses</th>
                    <th class="ctr" rowspan="2"> Issues/ Transfers/ Adjustment/s</th>
                    <th class="ctr" rowspan="2"> Adjusted Cost</th>
                    <th class="ctr" colspan="2"> Repair History</th>
                </tr>
                <tr>
                    <td>Qty</td>
                    <td>Book Value</td>
                    <td>Total Cost</td>
                    <td>Nature of Repair</td>
                    <td>Amount</td>
                </tr>

            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

</div>
<style>
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }

    .amt {
        text-align: right;
    }

    table {
        width: 100%;
    }

    .container,
    .ppelc-index {
        background-color: white;
        padding: 2rem;
    }

    .ctr {
        text-align: center;
    }

    @media print {

        #filter,
        .main-footer {
            display: none;

        }

        th,
        td {
            padding: 3px;
            font-size: x-small;
        }

        .container {
            padding: 0;
        }
    }
</style>
<?php

$this->registerJsFile("@web/js/moment.min.js", ['depends' => [JqueryAsset::class]]);
$this->registerCssFile("@web/frontend/web/css/site.css");
?>
<script>
    $(document).ready(function() {

        $('#filter').submit(function(e) {
            $('#data_table tbody').html('')
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: $('#filter').serialize(),
                success: function(data) {
                    const res = JSON.parse(data)
                    console.log(res)
                    displayData(res)
                }
            })
        })
    })

    function displayData(data) {
        let total_count = 0
        $.each(data, function(key, val) {
            // acquisition_amount
            // book_name
            // book_val
            // date_acquired
            // general_ledger
            // mnthly_depreciation
            // pc_num
            // strt_mnth
            // uacs
            // useful_life
            const data_row = `<tr>
            <td>${val.date_acquired}</td>
            <td>${val.property_number}</td>
            <td>1</td>
            <td class='amt'>${thousands_separators(val.book_val)}</td>
            <td class='amt'>${thousands_separators(val.acquisition_amount)}</td>
            <td class='amt'>${thousands_separators(val.depreciated_amt)}</td>
            <td></td>
            <td></td>
            <td class='amt'>${thousands_separators(val.book_bal)}</td>
            <td></td>
            <td></td>
            </tr>`;

            $('#data_table tbody').append(data_row)
            total_count++
        })
        console.log(total_count)
    }
</script>
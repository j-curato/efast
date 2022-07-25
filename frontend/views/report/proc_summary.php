<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php


use aryelds\sweetalert\SweetAlertAsset;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Summary";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="summary-index" style="background-color:white;padding:1rem">

    <form id='filter'>

        <div class="row">
            <div class="col-sm-3">
                <label for="pr_id"> Purchase Request</label>
                <?= Select2::widget([
                    'name' => 'pr_id',
                    'options' => ['placeholder' => 'Search for a Purchase Request'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=pr-purchase-request/search-pr',
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
            <div class="col-sm-3">
                <label for="rfq_id"> RFQ</label>

                <?= Select2::widget(
                    [
                        'name' => 'rfq_id',
                        'options' => ['placeholder' => 'Search for a RFQ'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=pr-rfq/search-rfq',
                                'dataType' => 'json',
                                'delay' => 250,
                                'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],
                    ]
                ) ?>
            </div>
            <div class="col-sm-3">
                <label for="aoq_id"> Aoq</label>

                <?= Select2::widget([
                    'name' => 'aoq_id',
                    'options' => ['placeholder' => 'Search for a AOQ Number'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=pr-aoq/search-aoq',
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
                <label for="po_id"> Purchase Order</label>

                <?= Select2::widget([
                    'options' => ['placeholder' => 'Search Purchase Order Number'],
                    'name' => 'po_id',
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=pr-purchase-order/search-purchase-order',
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

        </div>

        <div class="row">

            <div class="col-sm-4">
                <label for="pr_from">PR From</label>
                <?= DatePicker::widget([
                    'name' => 'pr_from',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>

            <div class="col-sm-4">
                <label for="pr_to">PR To</label>
                <?= DatePicker::widget([
                    'name' => 'pr_to',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-4">
                <label for="rfq_from">RFQ From</label>
                <?= DatePicker::widget([
                    'name' => 'rfq_from',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>

            <div class="col-sm-4">
                <label for="rfq_to">RFQ To</label>
                <?= DatePicker::widget([
                    'name' => 'rfq_to',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-4">
                <label for="aoq_from">AOQ From</label>
                <?= DatePicker::widget([
                    'name' => 'aoq_from',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>

            <div class="col-sm-4">
                <label for="aoq_to">AOQ To</label>
                <?= DatePicker::widget([
                    'name' => 'aoq_to',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-4">
                <label for="po_from">PO From</label>
                <?= DatePicker::widget([
                    'name' => 'po_from',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>

            <div class="col-sm-4">
                <label for="po_to">PO To</label>
                <?= DatePicker::widget([
                    'name' => 'po_to',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-1">
                <button type="submit" class="btn btn-success" style="margin-top: 1rem;">Generate</button>
            </div>
        </div>
    </form>


    <table id='data_table'>

        <thead>

        </thead>
        <tbody></tbody>
    </table>
</div>
<style>
    td,
    th {
        border: 1px solid black;
        padding: 6px;
    }

    table {
        width: 100%;
        margin-top: 2rem;
    }

    .amount {
        text-align: right;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(function() {

        $('#filter').on('submit', function(e) {
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
        $('#data_table tbody').empty()
        let i = 1
        $.each(data, function(pr_number, pr_val) {

            let pr_row = `<tr>`
            const pr_array = pr_number.split('[/]')
            console.log(pr_array)
            $.each(pr_array, function(key, val) {
                pr_row += `<th>${val}</th>`
            })
            pr_row += '<td></td><td></td><td></td></tr>'
            $('#data_table tbody').append(pr_row)

            $.each(pr_val, function(rfq_number, rfq_val) {
                const rfq_row = `<tr><td></td><th>RFQ#: ${rfq_number}</th><td></td><td></td><td></td><td></td></tr>`
                $('#data_table tbody').append(rfq_row)

                $.each(rfq_val, function(aoq_number, aoq_val) {
                    const aoq_row = `<tr><td></td><td></td><th>AOQ#: ${aoq_number}</th><td></td><td></td><td></td></tr>`
                    $('#data_table tbody').append(aoq_row)
                    $.each(aoq_val, function(po_number, po_val) {
                        const po_row = `<tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>${po_number}</td>
                                            <td>${po_val.payee}</td>
                                            <td class='amount'>${thousands_separators(po_val.total_cost)}</td>
                                        </tr>`
                        $('#data_table tbody').append(po_row)
                    })
                })
            })
        })

    }
</script>
<?php
$js = <<<JS
     
JS;
$this->registerJs($js);

?>
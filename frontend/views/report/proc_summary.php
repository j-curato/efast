<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php


use aryelds\sweetalert\SweetAlertAsset;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Summary";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="summary-index">

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
            <div class="col-sm-1">
                <button type="submit" class="btn btn-success">Generate</button>
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
    }
</style>

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

        $.each(data, function(pr_number, pr_val) {

            let pr_row = `<tr>`
            const pr_array = pr_number.split('[/]')
            console.log(pr_array)
            $.each(pr_array, function(key, val) {
                pr_row += `<td>${val}</td>`
            })
            pr_row += '<td></td><td></td><td></td></tr>'
            $('#data_table tbody').append(pr_row)

            $.each(pr_val, function(rfq_number, rfq_val) {
                const rfq_row = `<tr><td></td><td>${rfq_number}</td><td></td><td></td><td></td><td></td></tr>`
                $('#data_table tbody').append(rfq_row)

                $.each(rfq_val, function(aoq_number, aoq_val) {
                    const aoq_row = `<tr><td></td><td></td><td>${aoq_number}</td><td></td><td></td><td></td></tr>`
                    $('#data_table tbody').append(aoq_row)
                    $.each(aoq_val, function(po_number, po_val) {
                        const po_row = `<tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>${po_number}</td>
                                            <td>${po_val.payee}</td>
                                            <td>${po_val.total_cost}</td>
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
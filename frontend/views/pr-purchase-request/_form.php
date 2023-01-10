<?php

use app\models\Books;
use app\models\PrAllotmentViewSearch;
use app\models\RecordAllotmentsViewSearch;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\money\MaskMoney;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseRequest */
/* @var $form yii\widgets\ActiveForm */

$row_number = 1;
$allotment_row_num = 1;

if (!empty($error)) {
    echo SweetAlert::widget([
        'options' => [
            'title' => "Error",
            'text' => "$error",
            'type' => "error"
        ]
    ]);
}
$requested_by = '';
$approved_by = '';
$pr_project = '';
if (!empty($model->id)) {
    $requested_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->requested_by_id)
        ->queryAll();
    $requested_by = ArrayHelper::map($requested_by_query, 'employee_id', 'employee_name');
    $approved_by_query   = Yii::$app->db->createCommand("SELECT employee_id,UPPER(employee_name)  as employee_name FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->approved_by_id)
        ->queryAll();
    $approved_by = ArrayHelper::map($approved_by_query, 'employee_id', 'employee_name');
    // $pr_project_query   = Yii::$app->db->createCommand("SELECT id,title   FROM pr_project_procurement WHERE id = :id")
    //     ->bindValue(':id', $model->ppmp_id)
    //     ->queryAll();
    // $pr_project = ArrayHelper::map($pr_project_query, 'id', 'title');
}


$ppmp_item_id  = '';
$ppmp_item_data  = [];
if (!empty($model->fk_supplemental_ppmp_noncse_id)) {

    $ppmp_item_id = $model->fk_supplemental_ppmp_noncse_id . '-non_cse';

    $ppmp_item_data = ArrayHelper::map(Yii::$app->db->createCommand("SELECT CONCAT(id,'-non_cse') as id,activity_name FROM supplemental_ppmp_non_cse WHERE id =:id")
        ->bindValue(':id', $model->fk_supplemental_ppmp_noncse_id)->queryAll(), 'id', 'activity_name');
} else if (!empty($model->fk_supplemental_ppmp_cse_id)) {
    $ppmp_item_id = $model->fk_supplemental_ppmp_cse_id . '-cse';
    $ppmp_item_data = ArrayHelper::map(Yii::$app->db->createCommand("SELECT CONCAT(supplemental_ppmp_cse.id,'-cse') as id,pr_stock.stock_title
     FROM supplemental_ppmp_cse
     LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
      WHERE supplemental_ppmp_cse.id =:id")
        ->bindValue(':id', $model->fk_supplemental_ppmp_cse_id)->queryAll(), 'id', 'stock_title');
}
$user_data = Yii::$app->memem->getUserData();
?>

<div class="pr-purchase-request-form" style="padding:3rem">

    <div class="panel panel-body shadow p-3 mb-5 bg-white rounded">
        <?= Html::beginForm([$action, 'id' => $model->id], 'post', ['id' => 'pr_form']); ?>
        <div class="row">
            <div class="col-sm-3">
                <label for="budget_year">Budget Year</label>
                <?php
                echo DatePicker::widget([
                    'name' => 'budget_year',
                    'id' => 'budget_year',
                    'value' => $model->budget_year,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy',
                        'minViewMode' => 'years'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]
                ]);
                ?>
            </div>

            <div class="col-sm-6">
                <label for="ppmp_id">Project</label>
                <?= Select2::widget([
                    'data' => $ppmp_item_data,
                    'name' => 'ppmp_id',
                    'id' => 'ppmp_id',
                    'value' => $ppmp_item_id,
                    'options' => [
                        'placeholder' => 'Search for a Activity/Project ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=pr-purchase-request/search-ppmp',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { 
                                return {q:params.term,page: params.page||1,budget_year:$("#budget_year").val()}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>


            <div class="col-sm-2">
                <label for="book_id">Book </label>
                <?= Select2::widget([
                    'name' => 'book_id',
                    'value' => $model->book_id,
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ]) ?>
            </div>
        </div>

        <label for="purpose">Purpose </label>
        <?php echo Html::textarea('purpose', $model->purpose, ['rows' => 4, 'required' => true, 'id' => 'purpose']) ?>





        <div class="row">
            <div class="col-sm-6">
                <label for="requested_by_id">Requested By </label>
                <?= Select2::widget([
                    'name' => 'requested_by_id',
                    'value' => $model->requested_by_id,
                    'data' => $requested_by,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
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
            <div class="col-sm-6">
                <label for="approved_by_id">Approved By </label>
                <?= Select2::widget([
                    'name' => 'approved_by_id',
                    'data' => $approved_by,
                    'value' => $model->approved_by_id,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
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
        </div>
        <hr style="  position: relative;
                            top: 10px;
                            border: none;
                            height: 2px;
                            background: black;
                            margin-bottom: 20px;">








        <table class="table" id="form_fields_data">
            <thead>
                <tr class="info">
                    <th colspan="2" style="text-align: center;">
                        <h3>Specification</h3>
                    </th>
                </tr>
            </thead>

            <tbody>

                <?php
                $row_num = 1;
                $specs_grnd_ttl = 0;
                if (!empty($items)) {
                    foreach ($items as $i => $val) {

                        $item_id = $val['item_id'];
                        $stock_id = $val['stock_id'];
                        $stock_title = $val['stock_title'];
                        $unit_of_measure_id = $val['unit_of_measure_id'];
                        $unit_of_measure = $val['unit_of_measure'];
                        $unit_cost = $val['unit_cost'];
                        $quantity = $val['quantity'];
                        $bal_amt = '';
                        $specification  = preg_replace('#\<br\>#', "\n",  $val['specification']);
                        $item_ttl = floatval($unit_cost) * intval($quantity);
                        $specs_grnd_ttl += $item_ttl;
                        echo "<tr class='' style='margin-top: 2rem;margin-bottom:2rem;'>
                        <td style='max-width:100rem;'>
                            <div class='panel panel-default' style=' padding: 15px;'>
                                <div class='row'>
                                <input required type='hidden' name='pr_items[$row_number][item_id]' class='stock_input form-control' style='width: 100%' value='$item_id'>
                                    <div class=' col-sm-12'>
                                        <a class='remove_this_row btn btn-danger btn-xs  pull-right' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <label for='stocks'>Stock</label>
                                        <input required type='hidden' name='pr_items[$row_number][pr_stocks_id]' class='stock_input form-control' style='width: 100%' value='$stock_id'>
                                        <p>$stock_title</p>
                                            
                                    </div>
                                    <div class='col-sm-1'>
                                        <label for='balance'>Balance Amount</label>
                                        <p>$bal_amt</p>
                                    </div>
                                    <div class='col-sm-2'>
                                        <label for='unit_of_measure'>Unit of Measure</label>
                                        <select required name='pr_items[$row_number][unit_of_measure_id]' class='unit_of_measure form-control' style='width: 100%'>
                                            <option value='$unit_of_measure_id'>$unit_of_measure</option>
                                        </select>
                                    </div>
                                    <div class='col-sm-2'>
                                        <label for='amount'>Unit Cost</label>
                                        <input type='text' class='amount form-control' value='" . number_format($unit_cost, 2) . "' onkeyup='updateMainAmount(this)'>
                                        <input type='hidden' name='pr_items[$row_number][unit_cost]' class='unit_cost main-amount' value='$unit_cost'>
                                    </div>
                                    <div class='col-sm-1'>
                                        <label for='quantity'>Quantity</label>
                                        <input type='number' name='pr_items[$row_number][quantity]' class='form-control quantity' value='$quantity' min='0'>
                                    </div>
                                    <div class='col-sm-2'>
                                        <label for='total'>Total</label>
                                        <h5 class='item_total'>" . number_format($item_ttl, 2) . "</h5>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <label for='specs_view'>Specification</label>
                                        <textarea rows='2' class='specs_view form-control' onkeyup='updateMainSpecs(this)'>$specification</textarea>
                                        <textarea name='pr_items[$row_number][specification]' class='main-specs' style='display:none'>$specification</textarea>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>";
                        $row_number++;
                    } ?>

                    <!-- <tr class="" style="margin-top: 2rem;margin-bottom:2rem;">
                    <td style="max-width:100rem;">

                        <div class='panel panel-default' style=' padding: 15px;'>
                            <div class="row">

                                <div class=' col-sm-12'>
                                    <a class='remove_this_row btn btn-danger btn-xs disabled pull-right' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="stocks">Stock</label>
                                    <select required name="pr_stocks_id[0]" class="stocks form-control" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <label for="unit_of_measure">Unit of Measure</label>
                                    <select required name="unit_of_measure_id[0]" class="unit_of_measure form-control" style="width: 100%">
                                        <option></option>
                                    </select>
                                </div>




                                <div class="col-sm-3">
                                    <label for="amount">Unit Cost</label>
                                    <input type="text" class="amount form-control">
                                    <input type="hidden" name="unit_cost[0]" class="unit_cost">
                                </div>

                                <div class="col-sm-2">

                                    <label for="quantity">Quantity</label>
                                    <input type="number" name='quantity[0]' class="form-control quantity">

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <label for="specs_view">Specification</label>
                                    <textarea rows="2" class="specs_view form-control" id="q"></textarea>
                                    <input name="specification[0]" rows="2" class="specs" type='hidden'>
                                    <textarea name='specification[0]' class='specs' type='hidden' style='display:none'></textarea>
                                </div>
                            </div>

                        </div>
                    </td>


                </tr> -->

                    <!-- <tr class="panel  panel-default" style="margin-top: 2rem;margin-bottom:2rem;">
                            <td style="max-width:100rem;">

                                <div class="row">
                                    <div class="col-sm-4">
                                        <label for="stocks">Stock</label>
                                        <select required name="pr_stocks_id[0]" class="stocks form-control" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="unit_of_measure">Unit of Measure</label>
                                        <select required name="unit_of_measure_id[0]" class="unit_of_measure form-control" style="width: 100%">
                                            <option></option>
                                        </select>
                                    </div>




                                    <div class="col-sm-3">
                                        <label for="amount">Unit Cost</label>
                                        <input type="text" class="amount form-control">
                                        <input type="hidden" name="unit_cost[0]" class="unit_cost">
                                    </div>

                                    <div class="col-sm-2">

                                        <label for="quantity">Quantity</label>
                                        <input type="number" name='quantity[0]' class="form-control quantity">

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <label for="specs_view">Specification</label>
                                        <textarea rows="2" class="specs_view form-control" id="q"></textarea>
                                        <input name="specification[0]" rows="2" class="specs" type='hidden'>
                                        <textarea name='specification[0]' class='specs' type='hidden' style='display:none'></textarea>
                                    </div>
                                </div>
                            </td>
                            <td style='  text-align: center;'>
                                <div class='pull-right'>
                                    <button class='add_new_row btn btn-primary btn-xs'><i class='fa fa-plus fa-fw'></i> </button>
                                    <a class='remove_this_row btn btn-danger btn-xs disabled' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </td>


                        </tr>
                        <tr>
                            <td colspan="2">
                                <hr>
                            </td>
                        </tr> -->


                <?php } ?>

            </tbody>
            <tfoot>

                <tr>
                    <th colspan="5" class="">
                        <span style="margin-left:auto">
                            <b>Total</b>
                        </span>

                        <span class="float-right" style="float: right;margin-right:20rem">

                            <b class="specs_grand_total"><?= number_format($specs_grnd_ttl, 2) ?></b>
                        </span>
                    </th>
                </tr>
            </tfoot>
        </table>

        <hr style="  position: relative;
                            top: 10px;
                            border: none;
                            height: 2px;
                            background: black;
                            margin-bottom: 20px;">

        <table class="table" id="allotment_table">
            <thead>
                <tr class="info">
                    <th colspan="7" class="center">
                        <h3>Allotments</h3>
                    </th>
                </tr>
                <tr>
                    <th>Mfo Name</th>
                    <th>Fund Source</th>
                    <th> General Ledger</th>
                    <th class='amount'>Amount </th>
                    <th class='amount'>Balance </th>
                    <th>Gross Amount</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                <?php
                $allotment_grnd_ttl  = 0;
                if (!empty($allotment_items)) {
                    foreach ($allotment_items as $item) {
                        $allotment_entry_id = $item['allotment_entry_id'];
                        $pr_allotment_item_id = $item['pr_allotment_item_id'];
                        $mfo_name = $item['mfo_name'];
                        $fund_source_name = $item['fund_source_name'];
                        $account_title = $item['account_title'];
                        $amount = $item['amount'];
                        $balance = $item['balance'];
                        $gross_amount = floatval($item['gross_amount']);
                        $allotment_grnd_ttl += $gross_amount;
                        $gross_display = number_format($gross_amount, 2);
                        echo "<tr>
                            <td style='display:none;'><input type='text' class='entry_id' name='allotment_items[{$allotment_row_num}][pr_allotment_item_id]' value='$pr_allotment_item_id'></td>
                            <td style='display:none;'><input type='text' class='entry_id' name='allotment_items[{$allotment_row_num}][allotment_id]' value='$allotment_entry_id'></td>
                            <td>$mfo_name</td>
                            <td>$fund_source_name</td>
                            <td>$account_title</td>
                            <td >" . number_format($amount, 2) . "</td>
                            <td >" . number_format($balance, 2) . "</td>
                            <td>
                                <input type='text' class='mask-amount amount form-control' onkeyup='updateMainAmount(this)' value='$gross_display'>
                                <input type='hidden' name='allotment_items[$allotment_row_num][gross_amount]' class='gross_amount main-amount' value='$gross_amount'>
                            </td>
                            <td class='right'>
                            <button type='button' class='remove btn-xs btn-danger'><i class='fa fa-times'></i></button>
                            </td>
                        </tr>";
                        $allotment_row_num++;
                    }
                }
                ?>
            </tbody>
            <tfoot>

                <tr>
                    <th colspan="5" class="" style="text-align: right;">Total: </th>
                    <th class='allotment_total' style="padding-left: 2rem;"><?= number_format($allotment_grnd_ttl, 2) ?></th>
                </tr>
            </tfoot>
        </table>
        <div class="form-group" style="text-align: center;">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:15em;font-size:15px']) ?>
        </div>

        <?= Html::endForm(); ?>


    </div>
    <style>
        .error {
            color: red;
        }
    </style>
    <?php
    $division = Yii::$app->user->identity->division;
    $searchModel = new PrAllotmentViewSearch();
    $searchModel->budget_year = date('Y');
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'ors', $division);
    $dataProvider->pagination = ['pageSize' => 10];
    $office = '';
    $division = '';
    if (Yii::$app->user->can('super-user')) {
        $office =   'office_name';
        $division =   'division';
        $col = [
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::input('text', 'item[allotment_id]', $model->allotment_entry_id, ['class' => 'allotment_id']);
                },
                'hidden' => true
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'addAllotment(this)']);
                },
            ],

            [
                'attribute' => 'budget_year',
                'hidden' => true

            ],

            'office_name',
            'division',
            'mfo_name',
            'fund_source_name',
            'account_title',
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                // 'hAlign' => 'right'
            ],
            [
                'attribute' => 'balance',
                'format' => ['decimal', 2],
                // 'hAlign' => 'right'
            ],



        ];
    } else {
        $col = [
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::input('text', 'item[allotment_id]', $model->allotment_entry_id, ['class' => 'allotment_id']);
                },
                'hidden' => true
            ],
            [
                'label' => 'Actions',
                'format' => 'raw',
                'value' => function ($model) {
                    return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'addAllotment(this)']);
                },
            ],

            [
                'attribute' => 'budget_year',
                'hidden' => true

            ],

            'mfo_name',
            'fund_source_name',
            'account_title',
            [
                'attribute' => 'amount',
                'format' => ['decimal', 2],
                // 'hAlign' => 'right'
            ],
            [
                'attribute' => 'balance',
                'format' => ['decimal', 2],
                // 'hAlign' => 'right'
            ],



        ];
    }


    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',
        ],
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'Allotments'
        ],
        'export' => false,
        'pjax' => true,


        'columns' => $col
    ]); ?>

</div>
<style>
    textarea {
        width: 100%;
        max-width: 100%;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }
</style>
<?php

// $this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/validate.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script type="text/javascript">
    let row_number = <?= $row_number ?>;
    let allotment_row_num = <?= $allotment_row_num ?>;

    function updateMainAmount(q) {
        $(q).parent().find('.main-amount').val($(q).maskMoney('unmasked')[0])
    }

    function updateMainSpecs(q) {

        var specs = $(q).val()
        var main_specs = $(q).closest('tr');
        specs = specs.replace(/\n/g, "[n]");
        specs = specs.replace(/"/g, '\'');
        // main_specs.children('td').eq(0).find('.specs').val(specs)
        $(q).parent().find('.main-specs').val(specs)
    }

    function stockSelect() {
        $('.stocks').select2({
            ajax: {
                url: window.location.pathname + '?r=pr-stock/search-stock',
                dataType: 'json',
                data: function(params) {

                    return {
                        q: params.term,
                    };
                },
                processResults: function(data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                },
            },
        });
    }

    function unitOfMeasureSelect() {
        $('.unit_of_measure').select2({
            data: unit_of_measure,
            placeholder: "Select Unit of Measure",

        })

    }

    function maskAmount() {

        $('.amount').maskMoney({
            allowNegative: true
        });


    }

    function thousands_separators(num) {
        if (isNaN(num)) {
            console.log('nan')
            return 0;
        }
        var number = Number(Math.round(num + "e2") + "e-2");
        var num_parts = number.toString().split(".");
        num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return num_parts.join(".");
    }

    function addAllotment(ths) {
        const clone = $(ths).closest('tr').clone()
        clone.find('.add').closest('td').remove()
        clone.find('.allotment_id').attr('name', `allotment_items[${allotment_row_num}][allotment_id]`)
        clone.append(`<td> <input type='text' class='mask-amount amount form-control' onkeyup='updateMainAmount(this)'><input type='hidden' name='allotment_items[${allotment_row_num}][gross_amount]' class='gross_amount main-amount'></td>`)
        clone.append('<td class="right"><button type="button" class="remove btn-xs btn-danger"><i class="fa fa-times"></i></button></td>')
        $('#allotment_table tbody').append(clone)
        maskAmount()
        allotment_row_num++
    }

    var unit_of_measure = []
    $(document).ready(function() {
        $('#form_fields_data').on('keyup change', '.quantity, .amount', () => {

            let specs_total = 0

            $(this).closest('tr').find('.item_total').text('qwe')
            $(".quantity").each(function(key, val) {
                const unit_cost = $(val).closest('tr').find('.main-amount').val()
                const qty = $(val).val()
                let res = parseFloat(unit_cost) * parseInt(qty)
                specs_total += res
                $(val).closest('tr').find('.item_total').text(thousands_separators(res))
            })
            if (isNaN(specs_total)) {
                console.log('true')
                specs_total = 0
            }
            console.log(specs_total)
            $('.specs_grand_total').text(thousands_separators(specs_total))

            // console.log(specs_total)
            // console.log(isNaN(specs_total))
            // console.log($(this).val())
        })
        $('#allotment_table').on('change keyup', '.amount  ', function(e) {
            let specs_total = 0
            $('#allotment_table .gross_amount').each(function(key, val) {
                const gross_amount = $(val).val()
                specs_total += parseFloat(gross_amount)
            })
            if (isNaN(specs_total)) {
                specs_total = 0
            }
            $('.allotment_total').text(thousands_separators(specs_total))
        })

        var x = <?= $row_num ?>;
        maskAmount()
        $.getJSON(window.location.pathname + '/frontend/web/index.php?r=unit-of-measure/get-all-measure')
            .then(function(data) {

                var array = []
                $.each(data, function(key, val) {
                    array.push({
                        id: val.id,
                        text: val.unit_of_measure
                    })
                })
                unit_of_measure = array
                unitOfMeasureSelect()

            });
        stockSelect()

        // function addNewLine() {
        //     var text = document.getElementById('q').value;
        //     text = text.replace(/\n/g, "[n]");
        //     console.log(text)
        // }
        // $('.specs_view').on('change', function(e) {
        //     e.preventDefault()
        //     var specs = $(this).val()
        //     var main_specs = $(this).closest('tr');
        //     specs = specs.replace(/\n/g, "[n]");
        //     specs = specs.replace(/"/g, '\'');
        //     main_specs.children('td').eq(0).find('.specs').val(specs)
        // })

        $('.amount').on('change keyup', function(e) {
            e.preventDefault()
            var amount = $(this).maskMoney('unmasked')[0];
            var source = $(this).closest('tr');
            source.children('td').eq(0).find('.unit_cost').val(amount)

        })
        // $('.stocks').on('change', function(e) {
        //     var source = $(this).closest('tr');
        //     $.ajax({
        //         type: 'POST',
        //         url: window.location.pathname + '?r=pr-stock/stock-info',
        //         data: {
        //             id: $(this).val()
        //         },
        //         success: function(data) {
        //             var res = JSON.parse(data)
        //             source.children('td').eq(0).find('.amount').val(res.amount).trigger('change')
        //             source.children('td').eq(0).find('.unit_of_measure').val(res.unit_of_measure_id).trigger('change')
        //         }
        //     })


        // })
        stockSelect()
        $('#form_fields_data').on('click', '.remove_this_row', function(event) {
            event.preventDefault();
            // $(this).closest('tr').next().remove();
            $(this).closest('tr').remove();
        });
        // $('.add_new_row').on('click', function(event) {
        //     event.preventDefault();
        //     $('.stocks').select2('destroy');
        //     $('.unit_of_measure').select2('destroy');
        //     $('.unit_cost').maskMoney('destroy');
        //     var source = $(this).closest('tr');
        //     var clone = source.clone(true);
        //     clone.children('td').eq(0).find('.desc').text('')
        //     clone.children('td').eq(0).find('.quantity').val(0)
        //     clone.children('td').eq(0).find('.quantity').attr('name', 'quantity[' + x + ']')
        //     clone.children('td').eq(0).find('.unit_of_measure').val('')
        //     clone.children('td').eq(0).find('.unit_of_measure').attr('name', 'unit_of_measure_id[' + x + ']')
        //     clone.children('td').eq(0).find('.pr_item_id').val('')
        //     clone.children('td').eq(0).find('.pr_item_id').attr('name', 'pr_item_id[' + x + ']')
        //     clone.children('td').eq(0).find('.stocks').val('')
        //     clone.children('td').eq(0).find('.stocks').attr('name', 'pr_stocks_id[' + x + ']')
        //     clone.children('td').eq(0).find('.unit_cost').val(0)
        //     clone.children('td').eq(0).find('.unit_cost').attr('name', 'unit_cost[' + x + ']')
        //     clone.children('td').eq(0).find('.amount').val(0)
        //     clone.children('td').eq(0).find('.specs').val(null)
        //     clone.children('td').eq(0).find('.specs_view').val(null)
        //     clone.children('td').eq(0).find('.specs').attr('name', 'specification[' + x + ']');

        //     // clone.children('td').eq(0).find('.specification').val('')
        //     $('#form_fields_data').append(clone);
        //     var spacer = `<tr>
        //                 <td colspan="2">
        //                     <hr>
        //                 </td>
        //             </tr>`
        //     $('#form_fields_data').append(spacer);
        //     clone.find('.remove_this_row').removeClass('disabled');
        //     stockSelect()
        //     maskAmount()
        //     unitOfMeasureSelect()
        //     x++


        // });
        $('.specs_remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        // $('.specs_add_new_row').on('click', function(event) {
        //     event.preventDefault();
        //     var source = $(this).closest('tr');
        //     var clone = source.clone(true);

        //     $(this).closest('.specs-table').append(clone);
        //     clone.find('.specs_remove_this_row').removeClass('disabled');
        // });

        // $('.specs').on('change', function(event) {
        //     event.preventDefault();
        //     var source = $(this).closest('.form_fields_data > tr ');
        //     var new_val = source.children('td').eq(0).find('.unit_cost').val() + '|' + $(this).val()
        // });
        // $('#form_fields_data').on('change onkeyup', '.specs_view', () => {
        //     console.log($(this).closest('tr'))
        //     $(this).closest('tr').find('specs').val('qweqwe')
        // })

        // $('.stocks').val(1).trigger('change')



        const registrationForm = $('#pr_form');
        if (registrationForm.length) {
            registrationForm.validate({
                rules: {
                    ppmp_id: {
                        required: true
                    },
                    book_id: {
                        required: true
                    },

                    purpose: {
                        required: true
                    },

                    requested_by_id: {
                        required: true
                    },

                    approved_by_id: {
                        required: true
                    },
                    budget_year: {
                        required: true
                    },

                },
                messages: {

                    ppmp_id: {
                        required: 'Project/Stock is Required'
                    },

                    book_id: {
                        required: 'Book  is Required'
                    },

                    purpose: {
                        required: 'Purpose  is Required'
                    },

                    requested_by_id: {
                        required: 'Requested By  is Required'
                    },

                    budget_year: {
                        required: 'Budget Year  is Required'
                    },





                },
                errorPlacement: function(error, element) {
                    if (element.is("textarea")) {
                        error.insertAfter(element);
                        // element.append(error)
                    } else if (element.hasClass("qty") || element.hasClass("mask-amount")) {
                        element.parent().append(error)
                    } else if (element.is("select")) {
                        error.appendTo(element.parents('.hobbies'));
                        element.parent().append(error)
                    } else if (element.is('.date_from') || element.is('.date_to')) {
                        error.insertAfter(element);
                    } else {

                        element.parent().parent().append(error)
                    }

                },


            });
        }
        $('#allotment_table').on('click', '.remove', function(e) {
            $(this).closest('tr').remove()
        })

        $('#ppmp_id').change(() => {
            const id = $('#ppmp_id').val()
            if (id != '') {
                $.ajax({

                    type: 'POST',
                    url: window.location.pathname + "?r=pr-purchase-request/get-ppmp-items",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        const result = JSON.parse(data)
                        console.log($('#ppmp_id').text())
                        $('#purpose').val($('#ppmp_id :selected').text())
                        displayPpmpItems(result)
                    }
                })
            }
        })
        $('#budget_year').change(() => {
            const budget_year = $('#budget_year').val()
            $('#form_fields_data tbody').html('')
            $('#ppmp_id').val('').trigger('change')

            $('input[name^="PrAllotmentViewSearch[budget_year]"]').val(budget_year).trigger('change')

        })

    });

    function displayPpmpItems(data) {
        $('#form_fields_data tbody').html('')
        $.each(data, (key, val) => {
            const bal_amt = val.bal_amt
            const bal_qty = val.bal_qty
            const stock_id = val.stock_id
            const stock_title = val.stock_title
            const unit_cost = val.unit_cost
            const unit_of_measure = val.unit_of_measure
            const unit_of_measure_id = val.unit_of_measure_id
            const specification = val.description

            let row = `<tr class="" style="margin-top: 2rem;margin-bottom:2rem;">
                    <td style="max-width:100rem;">

                        <div class='panel panel-default' style=' padding: 15px;'>
                            <div class="row">
                                <div class=' col-sm-12'>
                                    <a class='remove_this_row btn btn-danger btn-xs  pull-right' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="stocks">Stock</label>
                                    <input required type='hidden' name="pr_items[${row_number}][pr_stocks_id]" class="stock_input form-control" style="width: 100%" value='${stock_id}'>
                                    <p>${stock_title}</p>
                                        
                                </div>
                                <div class="col-sm-1">
                                    <label for="balance">Balance Amount</label>
                                    <p>${bal_amt}</p>
                                </div>
                                <div class="col-sm-2">
                                    <label for="unit_of_measure">Unit of Measure</label>
                                    <select required name="pr_items[${row_number}][unit_of_measure_id]" class="unit_of_measure form-control" style="width: 100%">
                                        <option value='${unit_of_measure_id}'>${unit_of_measure}</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label for="amount">Unit Cost</label>
                                    <input type="text" class="amount form-control" value='${unit_cost}' onkeyup='updateMainAmount(this)'>
                                    <input type="hidden" name="pr_items[${row_number}][unit_cost]" class="unit_cost main-amount" value='${unit_cost}'>
                                </div>
                                <div class="col-sm-1">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name='pr_items[${row_number}][quantity]' class="form-control quantity" value='${bal_qty}'  min='0'>
                                </div>
                                 <div class='col-sm-2'>
                                        <label for='total'>Total</label>
                                        <h5 class='item_total'></h5>
                                    </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <label for='specs_view'>Specification</label>
                                    <textarea rows='2' class='specs_view form-control' onkeyup='updateMainSpecs(this)'>${specification}</textarea>
                                    <textarea name='pr_items[${row_number}][specification]' class='main-specs' style='display:none'>${specification}</textarea>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>`;
            $("#form_fields_data tbody").append(row)
            row_number++
            unitOfMeasureSelect()
            maskAmount()
        })
    }
</script>
<?php
SweetAlertAsset::register($this);
$js = <<<JS

    $(document).ready(()=>{
        $('#pr_form').on('submit', function(e) {
            e.preventDefault()
            const form = $(this)
            $.ajax({
                type: 'POST',
                url: window.location.pathname + form.attr('action'),
                data: form.serialize(),
                success: function(data) {
                    const res = JSON.parse(data)
                    if (!res.isSuccess) {

                        if (typeof res.error_message === 'object') {
                            console.log('object error')
                        } else if (typeof res.error_message === 'string') {
                            swal({
                                icon: 'error',
                                title: res.error_message,
                                type: "error",
                                timer: 3000,
                                closeOnConfirm: false,
                                closeOnCancel: false
                            })
                        }
                    }
                },

            })
        })
    })
JS;

$this->registerJs($js);
?>
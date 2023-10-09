<?php

use app\models\Books;
use yii\helpers\Html;
use app\models\Office;
use kartik\icons\Icon;
use common\models\User;
use app\models\Divisions;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use Matrix\Operators\Division;
use aryelds\sweetalert\SweetAlert;
use app\models\DivisionProgramUnit;
use app\models\PrAllotmentViewSearch;
use aryelds\sweetalert\SweetAlertAsset;
use app\models\RecordAllotmentDetailedSearch;

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
$pr_project = '';

$ppmp_item_id  = '';
$ppmp_item_data  = [];
if (!empty($model->fk_supplemental_ppmp_noncse_id)) {

    $check_ppmp_type = YIi::$app->db->createCommand("SELECT `type` FROM supplemental_ppmp_non_cse WHERE supplemental_ppmp_non_cse.id = :id  ")
        ->bindValue(':id', $model->fk_supplemental_ppmp_noncse_id)
        ->queryScalar();
    if ($check_ppmp_type === 'fixed expenses') {
        $query = Yii::$app->db->createCommand("SELECT 

        CONCAT(pr_purchase_request.fk_supplemental_ppmp_noncse_id,'-non_cse','-',pr_purchase_request_item.fk_ppmp_non_cse_item_id) as id,
        pr_ppmp_search_view.stock_or_act_name as activity_name
        FROM pr_purchase_request
        LEFT JOIN pr_purchase_request_item ON pr_purchase_request.id  = pr_purchase_request_item.pr_purchase_request_id
        LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
        LEFT JOIN pr_ppmp_search_view ON CONCAT(pr_purchase_request.fk_supplemental_ppmp_noncse_id,'-non_cse','-',pr_purchase_request_item.fk_ppmp_non_cse_item_id)  = pr_ppmp_search_view.id
        WHERE pr_purchase_request.id = :id
        AND pr_purchase_request_item.is_deleted = 0
        ")
            ->bindValue(':id', $model->id)
            ->queryAll();
        $ppmp_item_id =  $query[min(array_keys($query))]['id'];
    } else {
        $ppmp_item_id = $model->fk_supplemental_ppmp_noncse_id . '-non_cse';
        $query = Yii::$app->db->createCommand("SELECT CONCAT(id,'-non_cse') as id,activity_name FROM supplemental_ppmp_non_cse WHERE id =:id")
            ->bindValue(':id', $model->fk_supplemental_ppmp_noncse_id)->queryAll();
    }
    $ppmp_item_data = ArrayHelper::map($query, 'id', 'activity_name');
} else if (!empty($model->fk_supplemental_ppmp_cse_id)) {
    $ppmp_item_id = $model->fk_supplemental_ppmp_cse_id . '-cse';
    $ppmp_item_data = ArrayHelper::map(Yii::$app->db->createCommand("SELECT CONCAT(supplemental_ppmp_cse.id,'-cse') as id,pr_stock.stock_title
     FROM supplemental_ppmp_cse
     LEFT JOIN pr_stock ON supplemental_ppmp_cse.fk_pr_stock_id = pr_stock.id
      WHERE supplemental_ppmp_cse.id =:id")
        ->bindValue(':id', $model->fk_supplemental_ppmp_cse_id)->queryAll(), 'id', 'stock_title');
} else if (intval($model->is_fixed_expense) === 1) {
    $ppmp_item_id =  'fixed_expenses-fixed_expenses';
    $ppmp_item_data =
        ArrayHelper::map([
            ['id' => $ppmp_item_id, 'name' => 'Fixed Expenses']
        ], 'id', 'name');
}
$user_data = User::getUserDetails();
?>


<div class="panel panel-body shadow p-3 mb-5 bg-white rounded">
    <div class="pr-purchase-request-form" style="padding:3rem">
        <ul class="warning">
            <li>Notes</li>
            <li>Select Budget Year Before Selecting PPMPs</li>
            <li>PPMPs are created in Supplemental PPMP Module </li>
            <li>The total of the Allotment table and the Specification table total must be equal. </li>
            <li>If the balance of the Stock reaches zero, it will no longer be displayed in the selected PPMP.</li>
            <?= Yii::$app->user->can('ro_procurement_admin') ? '<li>Select the Office, Division, and Budget year first before selecting PPMPs.</li>' : '' ?>
        </ul>
    </div>
    <div>

        <?php $form = ActiveForm::begin([
            'id' => 'PurchaseRequestForm'
        ]); ?>
        <div class="row">
            <?php if (Yii::$app->user->can('ro_procurement_admin')) { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office',
                        ],

                    ]) ?>
                </div>


            <?php }
            if (Yii::$app->user->can('back_date')) {
            ?>
                <!-- <div class="col-sm-2">
                    <?= $form->field($model, 'back_date')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true
                        ],

                    ]) ?>
                </div> -->

            <?php
            }

            if (Yii::$app->user->can('ro_procurement_admin') || Yii::$app->user->can('po_procurement_admin')) { ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_division_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Division',
                        ],

                    ]) ?>
                </div>
            <?php } ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'budget_year')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy',
                        'minViewMode' => 'years'
                    ],
                    'options' => [
                        'readonly' => true,
                        'style' => 'background-color:white'
                    ]

                ]) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'fk_division_program_unit_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(DivisionProgramUnit::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Division/Program/Unit'
                    ]

                ]) ?>
            </div>
            <div class="col-sm-2">
                <?= $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]

                ]) ?>
            </div>
        </div>
        <?= $form->field($model, 'purpose')->textarea() ?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'requested_by_id')->widget(Select2::class, [
                    'data' => Arrayhelper::map($requested_by, 'employee_id', 'employee_name'),
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
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'approved_by_id')->widget(Select2::class, [
                    'data' => Arrayhelper::map($approved_by, 'employee_id', 'employee_name'),
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
                            'data' => new JsExpression('function(params) { return {q:params.term,page:params.page}; }'),
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
                $specs_grnd_ttl = 0;
                if (!empty($items)) {


                    foreach ($items as $key => $item) {

                        echo "<tr class='' style='margin-top: 1rem;margin-bottom:1rem;'>
                    <td style='max-width:100rem;'>
                        <div class='card' style='border:1px solid black'>
                            <div class='row' style='padding: 2rem;padding-left:4rem'>
                                <div class='col-sm-6'>
                                    <label for='ppmp'> Select PPMP</label>
                                    <select required name='q' class='ppmp form-control' style='width: 100%'>
                                    <option>{$key}</option>
                                    </select>
                                </div>
                                <div class=' col-sm-offset-5 col-sm-1' style='padding-top:2rem'>
                                    <a class='add_ppmp btn btn-success btn-xs ' title='Delete Row'><i class='fa fa-plus fa-fw'></i> </a>
                                    <a class='remove_ppmp btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </div>

                            <ul class='itemList'>";
                        foreach ($item as $specItem) {

                            $stock_id = $specItem['stock_id'];
                            $stock_title = $specItem['stock_title'];
                            $unit_cost = $specItem['unit_cost'];
                            $unit_of_measure = $specItem['unit_of_measure'];
                            $unit_of_measure_id = $specItem['unit_of_measure_id'];
                            $specification = $specItem['specification'];
                            $item_id = $specItem['item_id'];
                            $cse_type = $specItem['cse_type'];
                            $quantity = $specItem['quantity'];
                            $bac_code = $specItem['bac_code'];
                            $ppmp_item_id = $specItem['ppmp_item_id'];
                            $total_cost = $specItem['total_cost'];
                            $itemTtl  = floatval($unit_cost) * intval($quantity);
                            $specs_grnd_ttl += $itemTtl;
                            echo "<li style='width:100%;padding-right: 4rem;'>
                                <div class='card' style=' padding: 15px;'>
                                <div class='row'>
                                    <div class=' col-sm-12'>
                                        <a class='remove_ppmp_item btn btn-danger btn-xs  pull-right' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-4'>
                                        <label for='stocks'>Stock</label>
                                        <input required type='hidden' name='pr_items[$row_number][item_id]' class=' form-control' style='width: 100%' value='$item_id'>
                                        <input required type='hidden' name='pr_items[$row_number][$cse_type]' class=' form-control' style='width: 100%' value='$ppmp_item_id'>
                                        <input required type='hidden' name='pr_items[$row_number][pr_stocks_id]' class='stock_input form-control' style='width: 100%' value='$stock_id'>
                                        <p>$stock_title</p>
                                    </div>
                                    <div class='col-sm-1'>
                                        <label for='balance'>Balance Amount</label>
                                        <p></p>
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
                                        <input type='number' name='pr_items[$row_number][quantity]' class='form-control quantity' value='$quantity'  min='0'>
                                    </div>
                                    <div class='col-sm-2'>
                                        <label for='total'>Total</label>
                                        <h5 class='item_total'>" . number_format($itemTtl, 2) . "</h5>
                                    </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-12'>
                                        <label for='specs_view'>Specification</label>
                                        <textarea rows='2' class='specs_view form-control' onkeyup='updateMainSpecs(this)'>" . preg_replace("/<br\s*[\/]?>/i", "\n", $specification)  . "</textarea>
                                        <textarea name='pr_items[$row_number][specification]' class='main-specs' style='display:none'>$specification</textarea>
                                    </div>
                                </div>
                                </div>
                                </li>";
                            $row_number++;
                        }

                        echo "</ul>
                        </div>
                    </td>
                </tr>";
                    }
                } else {

                ?>
                    <tr class='' style='margin-top: 1rem;margin-bottom:1rem;'>
                        <td style='max-width:100rem;'>
                            <div class='card' style='border:1px solid black'>
                                <div class='row' style="padding: 2rem;padding-left:4rem">
                                    <div class="col-sm-6">
                                        <label for="ppmp"> Select PPMP</label>
                                        <select required name='q' class='ppmp form-control' style='width: 100%'></select>
                                    </div>
                                    <div class=' col-sm-offset-5 col-sm-1' style='padding-top:2rem'>
                                        <a class='add_ppmp btn btn-success btn-xs ' title='Delete Row'><i class='fa fa-plus fa-fw'></i> </a>
                                        <a class='remove_ppmp btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                    </div>
                                </div>

                                <ul class="itemList"></ul>
                            </div>

                        </td>
                    </tr>
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

        <?php
        $allotment_colspan = 9;
        if (Yii::$app->user->can('ro_procurement_admin')) {
            $allotment_colspan = 11;
            echo "<th>Office</th>";
            echo "<th>Division</th>";
        }
        if (strtolower($user_data->employee->office->office_name) === 'ro') {

        ?>

            <table class="table" id="allotment_table">
                <thead>
                    <tr class="info">
                        <th colspan="<?= $allotment_colspan ?>" class="center">
                            <h3>Allotments</h3>
                        </th>
                    </tr>
                    <tr>
                        <th>Budget Year</th>
                        <th>Office</th>
                        <th>Division</th>
                        <th>Allotment Number</th>
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
                            $gross_amount = ($item['gross_amount']);
                            $division = ($item['division']);
                            $office = ($item['office_name']);
                            $gross_amount = floatval($item['gross_amount']);
                            $allotmentNumber = $item['allotmentNumber'];
                            $budget_year = $item['budget_year'];
                            $allotment_grnd_ttl += $gross_amount;
                            $gross_display = number_format($gross_amount, 2);
                            echo "<tr><td>$budget_year</td>";


                            if (Yii::$app->user->can('ro_procurement_admin')) {
                                echo "<td>$office</td>
                            <td>$division</td>";
                            }
                            echo " 
                            <td style='display:none;'><input type='text' class='entry_id' name='allotment_items[{$allotment_row_num}][pr_allotment_item_id]' value='$pr_allotment_item_id'></td>
                            <td style='display:none;'><input type='text' class='entry_id' name='allotment_items[{$allotment_row_num}][allotment_id]' value='$allotment_entry_id'></td>
                            <td>$allotmentNumber</td>
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
        <?php } ?>
        <div class="row justify-content-center">

            <div class="form-group col-sm-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
    <style>
        .error {
            color: red;
        }
    </style>
    <?php
    if (strtolower($user_data->employee->office->office_name) === 'ro') {
        $division = Yii::$app->user->identity->division;
        $searchModel = new RecordAllotmentDetailedSearch();
        $searchModel->budget_year = date('Y');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'ors', $division);
        $dataProvider->pagination = ['pageSize' => 10];
        $office = '';
        $division = '';

        if (Yii::$app->user->can('ro_procurement_admin')) {
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
                    // 'hidden' => true

                ],
                [
                    'attribute' => 'office_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Office::find()->asArray()->all(), 'office_name', 'office_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select Office', 'multiple' => false],
                    'format' => 'raw'
                ],
                [
                    'attribute' => 'division',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($divisions_list, 'division', 'division'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select Division', 'multiple' => false],
                    'format' => 'raw'
                ],
                'allotmentNumber',

                [
                    'attribute' => 'mfo_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($mfo_list, 'mfo', 'mfo'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select MFO/PAP Code', 'multiple' => false],
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->mfo_code . '-' . $model->mfo_name;
                    }
                ],
                [
                    'attribute' => 'fund_source_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($fund_source_list, 'name', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select Fund Source', 'multiple' => false],
                    'format' => 'raw'
                ],
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
                    // 'hidden' => true

                ],
                [
                    'attribute' => 'office_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(Office::find()->asArray()->all(), 'office_name', 'office_name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select Office', 'multiple' => false],
                    'format' => 'raw'
                ],
                'allotmentNumber',
                'allotment_class',

                [
                    'attribute' => 'mfo_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($mfo_list, 'mfo', 'mfo'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select MFO/PAP Code', 'multiple' => false],
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->mfo_code . '-' . $model->mfo_name;
                    }
                ],
                [
                    'attribute' => 'fund_source_name',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map($fund_source_list, 'name', 'name'),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => 'Select Fund Source', 'multiple' => false],
                    'format' => 'raw'
                ],
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


        echo  GridView::widget([
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
        ]);
    }
    ?>

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

    .warning li {
        color: red;
        font-size: 12px;
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

    function PpmpSelect() {
        $(".ppmp").select2({
            allowClear: true,
            ajax: {
                url: window.location.pathname + "?r=pr-purchase-request/search-ppmp",
                dataType: "json",
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page || 1,
                        budget_year: $("#prpurchaserequest-budget_year").val(),
                        office_id: $("#prpurchaserequest-fk_office_id").val(),
                        division_id: $("#prpurchaserequest-fk_division_id").val(),
                    };
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results,
                        pagination: data.pagination
                    };
                },
            },
            // placeholder: "Search for a Payee",
        }).on('change', function(e) {
            var data = $(".select2 option:selected").text();

            const id = $(this).val()
            const office = $('#prpurchaserequest-fk_office_id').val()
            const division = $('#prpurchaserequest-fk_division_id').val()
            // $(this).closest('tr').find('.itemList').html('');
            // $(this).closest('tr').find('.itemList').append('<li><a href="#">New list item</a></li>');
            const itemList = $(this).closest('tr').find('.itemList')
            if (id != '') {
                $.ajax({

                    type: 'POST',
                    url: window.location.pathname + "?r=pr-purchase-request/get-ppmp-items",
                    data: {
                        id: id,
                        office: office,
                        division: division
                    },
                    success: function(data) {
                        const result = JSON.parse(data)
                        console.log(result)
                        displayPpmpItems(result, itemList)

                    }
                })
            }
        });;
    }

    function GetSpecificationsTotal() {
        let specs_total = 0

        $(".quantity").each(function(key, val) {
            const unit_cost = $(val).closest('li').find('.main-amount').val()
            const qty = $(val).val()
            let res = parseFloat(unit_cost) * parseInt(qty)
            specs_total += res
            $(val).closest('li').find('.item_total').text(thousands_separators(res))
        })
        if (isNaN(specs_total)) {
            console.log('true')
            specs_total = 0
        }
        $('.specs_grand_total').text(thousands_separators(specs_total))
    }
    let unit_of_measure = []
    $(document).ready(function() {
        maskAmount()
        PpmpSelect()
        $('#form_fields_data').on('keyup change', '.quantity, .amount', () => {
            GetSpecificationsTotal()

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

        // $('.amount').on('change keyup', function(e) {
        //     e.preventDefault()
        //     var amount = $(this).maskMoney('unmasked')[0];
        //     var source = $(this).closest('tr');
        //     source.children('td').eq(0).find('.unit_cost').val(amount)

        // })
        // remove ppmp
        $('#form_fields_data').on('click', '.remove_ppmp', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('#form_fields_data').on('click', '.remove_ppmp_item', function(event) {
            event.preventDefault();
            $(this).closest('li').remove();
        });
        // ADD PPMP
        $('#form_fields_data').on('click', '.add_ppmp', function(event) {
            event.preventDefault();
            const addRow = `<tr class='' style='margin-top: 1rem;margin-bottom:1rem;'>
                    <td style='max-width:100rem;'>
                        <div class='card' style='border:1px solid black'>
                            <div class='row' style="padding: 2rem;padding-left:4rem">
                                <div class="col-sm-6">
                                    <label for="ppmp"> Select PPMP</label>
                                    <select required name='q' class='ppmp form-control' style='width: 100%'></select>
                                </div>
                                <div class=' col-sm-offset-5 col-sm-1'  style='padding-top:2rem'>
                                    <a class='add_ppmp btn btn-success btn-xs ' title='Delete Row'><i class='fa fa-plus fa-fw'></i> </a>
                                    <a class='remove_ppmp btn btn-danger btn-xs ' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </div>

                            <ul class="itemList"></ul>
                        </div>

                    </td>
                </tr>`;
            $('#form_fields_data tbody').append(addRow)
            PpmpSelect()

        });
        $('.specs_remove_this_row').on('click', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });



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
                    office_id: {
                        required: true
                    },
                    division_id: {
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
            $('#form_fields_data tbody').html('')
            const id = $('#ppmp_id').val()
            const office = $('#office_id').val()
            const division = $('#division_id').val()
            if (id != '') {
                $.ajax({

                    type: 'POST',
                    url: window.location.pathname + "?r=pr-purchase-request/get-ppmp-items",
                    data: {
                        id: id,
                        office: office,
                        division: division
                    },
                    success: function(data) {
                        const result = JSON.parse(data)
                        $('#purpose').val($('#ppmp_id :selected').text())
                        displayPpmpItems(result)
                    }
                })
            }
        })


    });

    function displayPpmpItems(data, itemList) {
        itemList.html('')
        $.each(data, (key, val) => {
            const bal_amt = thousands_separators(val.bal_amt)
            const bal_qty = val.bal_qty
            const stock_id = val.stock_id
            const stock_title = val.stock_title
            const unit_cost = val.unit_cost
            const unit_of_measure = val.unit_of_measure
            const unit_of_measure_id = val.unit_of_measure_id
            const specification = val.description
            let itmGrs = parseInt(bal_qty) * parseFloat(val.unit_cost)
            let item_id = val.item_id
            let cse_type = val.cse_type
            let row = `
                <li style="width:100%;padding-right: 4rem;">
                
                     <div class='card' style=' padding: 15px;'>
                        <div class="row justify-content-end">
                          
                            <div class="">
                            <a class='remove_ppmp_item btn btn-danger btn-xs  pull-right' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                </div>
                            </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="stocks">Stock</label>
                                <input required type='hidden' name="pr_items[${row_number}][${cse_type}]" class=" form-control" style="width: 100%" value='${item_id}'>
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
                                <input type="text" class="amount form-control" value='${thousands_separators(unit_cost)}' onkeyup='updateMainAmount(this)'>
                                <input type="hidden" name="pr_items[${row_number}][unit_cost]" class="unit_cost main-amount" value='${unit_cost}'>
                            </div>
                            <div class="col-sm-1">
                                <label for="quantity">Quantity</label>
                                <input type="number" name='pr_items[${row_number}][quantity]' class="form-control quantity" value='${bal_qty}'  min='0'>
                            </div>
                            <div class='col-sm-2'>
                                <label for='total'>Total</label>
                                <h5 class='item_total'>${thousands_separators(itmGrs)}</h5>
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
                </li>`;

            itemList.append(row)
            row_number++
            unitOfMeasureSelect()
            maskAmount()
            GetSpecificationsTotal()
        })
    }
</script>
<?php
SweetAlertAsset::register($this);
$js = <<<JS

    $(document).ready(()=>{
            $("#PurchaseRequestForm").on("beforeSubmit", function (event) {
                event.preventDefault();
                var form = $(this);
                $.ajax({
                    url: form.attr("action"),
                    type: form.attr("method"),
                    data: form.serialize(),
                    success: function (data) {
                        // let res = JSON.parse(data)
                        swal({
                            icon: 'error',
                            title: data,
                            type: "error",
                            timer: 3000,
                            closeOnConfirm: false,
                            closeOnCancel: false
                        })
                    },
                    error: function (data) {
                
                    }
                });
                return false;
            });
    })
JS;

$this->registerJs($js);
?>
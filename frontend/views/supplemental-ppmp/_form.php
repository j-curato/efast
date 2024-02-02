<?php

use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\bootstrap4\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmp */
/* @var $form yii\widgets\ActiveForm */

$requested_by = [];
$offices = ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,office_name FROM office ")->queryAll(), 'id', 'office_name');
$divisions = ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,division FROM divisions ")->queryAll(), 'id', 'division');
$division_program_unit = ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,`name` FROM division_program_unit ")->queryAll(), 'id', 'name');
// echo json_encode(Yii::$app->memem->userData->divisionName->division);

$non_cse_rw_cnt = 1;
$cse_row_cnt = 1;
$prepared_by = [];
$reviewed_by = [];
$approved_by = [];
$certified_funds_available_by = [];

function GetEmployeeData($id)
{
    return Yii::$app->db->createCommand("SELECT employee_id,employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $id)
        ->queryAll();
}
if (!empty($model->fk_prepared_by)) {

    $prepared_by = ArrayHelper::map(GetEmployeeData($model->fk_prepared_by), 'employee_id', 'employee_name');
}
if (!empty($model->fk_reviewed_by)) {

    $reviewed_by = ArrayHelper::map(GetEmployeeData($model->fk_reviewed_by), 'employee_id', 'employee_name');
}
if (!empty($model->fk_approved_by)) {

    $approved_by = ArrayHelper::map(GetEmployeeData($model->fk_approved_by), 'employee_id', 'employee_name');
}
if (!empty($model->fk_certified_funds_available_by)) {

    $certified_funds_available_by = ArrayHelper::map(GetEmployeeData($model->fk_certified_funds_available_by), 'employee_id', 'employee_name');
}
$cse_type_data = [
    'cse' => 'CSE',
    'non_cse' => 'NON-CSE'
];

?>

<div class="supplemental-ppmp-form card" style="padding:1rem">
    <!-- <?= Html::button('<i class="fa fa-pencil-alt"></i> Create', [
                'value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=supplemental-ppmp/index'),
                'id' => 'lgmdModal', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector'
            ]); ?> -->
    <?= Html::beginForm([$action, 'id' => $model->id], 'post', ['id' => 'SupplementalPpmp']); ?>
    <div class="card">

        <div class="row ">
            <div class="col-sm-2">
                <label for="budget_year">Budget Year</label>
                <?= DatePicker::widget([
                    'name' => 'budget_year',
                    'id' => 'budget_year',
                    'value' => $model->budget_year,
                    'readonly' => true,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'minViewMode' => 'years',
                        'format' => 'yyyy',
                        'startDate' =>
                        strtotime(date('Y-m-d')) > strtotime('2023-11-28')
                            &&   !Yii::$app->user->can('super-user')
                            && !Yii::$app->user->can('create_2023_prs')
                            ? date('2024')
                            : date('2023'),

                    ]
                ]) ?>

            </div>
            <div class="col-sm-2">
                <label for="cse_type">CSE/NON-CSE</label>
                <?= Select2::widget([
                    'name' => 'cse_type',
                    'id' => 'cse_type',
                    'value' => $model->cse_type,
                    'pluginOptions' => [
                        'placeholder' => 'Select CSE Type'
                    ],
                    'data' => $cse_type_data
                ]) ?>
            </div>
            <?php
            if (Yii::$app->user->can('ro_procurement_admin')) {

            ?>
                <div class="col-sm-2">

                    <label for="fk_office_id">Office</label>
                    <?= Select2::widget([
                        'name' => 'fk_office_id',
                        'value' => $model->fk_office_id,
                        'data' => $offices,
                        'options' => [
                            'placeholder' => 'Select Office',
                            'class' => 'fk_office_id'
                        ],
                        // 'pluginOptions' => [
                        //     'allowClear' => true,
                        //     'minimumInputLength' => 1,
                        //     'language' => [
                        //         'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        //     ],
                        //     'ajax' => [
                        //         'url' => Yii::$app->request->baseUrl . '?r=office/search-office',
                        //         'dataType' => 'json',
                        //         'delay' => 250,
                        //         'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        //         'cache' => true
                        //     ],
                        //     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        //     'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        //     'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        // ],

                    ]) ?>
                </div>
            <?php
            }

            if (Yii::$app->user->can('select_ppmp_division')) {

            ?>
                <div class="col-sm-2">
                    <label for="fk_division_id">Division</label>
                    <?= Select2::widget([
                        'name' => 'fk_division_id',
                        'value' => $model->fk_division_id,
                        'data' => $divisions,
                        'options' => ['placeholder' => 'Select Division'],
                        // 'pluginOptions' => [
                        //     'allowClear' => true,
                        //     'minimumInputLength' => 1,
                        //     'language' => [
                        //         'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        //     ],
                        //     'ajax' => [
                        //         'url' => Yii::$app->request->baseUrl . '?r=office/search-office',
                        //         'dataType' => 'json',
                        //         'delay' => 250,
                        //         'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                        //         'cache' => true
                        //     ],
                        //     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        //     'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        //     'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        // ],

                    ]) ?>
                </div>
            <?php } ?>
            <div class="col-sm-2">
                <label for="fk_division_program_unit_id">Division/Program/Unit</label>
                <?= Select2::widget([
                    'name' => 'fk_division_program_unit_id',
                    'value' => $model->fk_division_program_unit_id,
                    'data' => $division_program_unit,
                    'options' => ['placeholder' => 'Select Division/Program/Unit'],
                    // 'pluginOptions' => [
                    //     'allowClear' => true,
                    //     'minimumInputLength' => 1,
                    //     'language' => [
                    //         'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    //     ],
                    //     'ajax' => [
                    //         'url' => Yii::$app->request->baseUrl . '?r=office/search-office',
                    //         'dataType' => 'json',
                    //         'delay' => 250,
                    //         'data' => new JsExpression('function(params) { return {q:params.term,province: params.province}; }'),
                    //         'cache' => true
                    //     ],
                    //     'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    //     'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    //     'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    // ],

                ]) ?>
            </div>



        </div>
        <div class="row ">
            <div class="col-sm-3">
                <label for="fk_prepared_by">Prepared By</label>
                <?= Select2::widget([
                    'name' => 'fk_prepared_by',
                    'value' => $model->fk_prepared_by,
                    'data' => $prepared_by,
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
                            'processResults' => new JsExpression('function(data,params) { 
                                params.page = params.page || 1;
                                return {
                                    results: data.results,
                                };
                            }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>
            </div>
            <div class="col-sm-3">

                <label for="reviewed_by">Reviewed By</label>
                <?= Select2::widget([
                    'name' => 'fk_reviewed_by',
                    'value' => $model->fk_reviewed_by,
                    'data' => $reviewed_by,
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
            <div class="col-sm-3">
                <label for="fk_approved_by">Approved By</label>
                <?= Select2::widget([
                    'name' => 'fk_approved_by',
                    'value' => $model->fk_approved_by,
                    'data' => $approved_by,
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
            <div class="col-sm-3">

                <label for="fk_certified_funds_available_by">Certified Funds Available By</label>
                <?= Select2::widget([
                    'name' => 'fk_certified_funds_available_by',
                    'value' => $model->fk_certified_funds_available_by,
                    'data' => $certified_funds_available_by,
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

    </div>
    <table class="table" id="entry_table">


        <tbody id="table_body">

            <?php

            if ($model->cse_type === 'non_cse') {
                foreach ($items as $non_cse_id => $non_cse) {
                    $min_key = min(array_keys($non_cse));
                    $type = $non_cse[$min_key]['type'];
                    $display_type = ucwords($type);
                    $activity_name = $non_cse[$min_key]['activity_name'];
                    $fk_fund_source_id = $non_cse[$min_key]['fk_fund_source_id'];
                    $fund_source_name = $non_cse[$min_key]['fund_source_name'];
                    $early_procurement = $non_cse[$min_key]['early_procurement'];
                    $mode_of_procurement_id = $non_cse[$min_key]['mode_of_procurement_id'];
                    $mode_of_procurement_name = $non_cse[$min_key]['mode_of_procurement_name'];
                    $early_procurement_disp = $early_procurement ? 'Yes' : 'No';
                    $show_hide_act_name = $type == 'activity' ? '' : 'display:none;';
                    echo "<tr>
                            <td >
                            <div class='card'  style=' padding: 15px;' >
                                <div class='row'>
                                    <div class='col-sm-3'>
                                    <input name='ppmp_non_cse[$non_cse_rw_cnt][non_cse_id]' class='form-control non_cse_id' type='hidden' value='$non_cse_id'>
                                        <label for='type'>Activity/Fixed Expenses</label>
                                        <select required name='ppmp_non_cse[$non_cse_rw_cnt][type]' class='form-control type activity_fixed_expense'  style='width: 100%;' onchange='hideShowActName(this)'>
                                            <option value='$type'>$display_type</option>
                                          
                                        </select>
                                    </div>
                                    <div class='col-sm-3' >
                                        <label for='early_procurement'>Is this an Early Procurement?</label>
                                        <select required name='ppmp_non_cse[$non_cse_rw_cnt][early_procurement]' class='form-control early_procurement'  style='width: 100%;'>
                                        <option value='$early_procurement'>$early_procurement_disp</option>
                                        </select>
                                    </div>
                                    <div class='col-sm-2'>
                                    <label for='fk_fund_source_id'>Fund Source</label>
                                        <select required name='ppmp_non_cse[$non_cse_rw_cnt][fk_fund_source_id]' class='form-control fk_fund_source_id'  style='width: 100%;'>
                                        <option value='$fk_fund_source_id'>$fund_source_name</option>

                                        </select>
                                    </div>
                                    <div class='col-sm-3'>
                                        <label for='fk_mode_of_procurement_id'>Mode of Procurement</label>
                                        <select required name='ppmp_non_cse[$non_cse_rw_cnt][fk_mode_of_procurement_id]' class='form-control mode_of_procurement'  style='width: 100%;'>
                                
                                        <option value='$mode_of_procurement_id'>$mode_of_procurement_name</option>

                                        </select>
                                    </div>
                                    <div class='col-sm-1 text-right'>
                                        <button class='btn-xs btn-success add_non_cse_row'><i class='fa fa-plus fa-fw'></i></button>
                                        <button class='btn-xs btn-danger remove_cse_row' type='button'><i class='fa fa-times fa-fw'></i></button>
                                    </div>
                                </div>
                                <div class='row'>
                                <div class='col-sm-11 activity_name' style='$show_hide_act_name'>
                                        <label for='activity_name'>Activity Name</label>
                                        <textarea required type='text' name='ppmp_non_cse[$non_cse_rw_cnt][activity_name]' class='form-control activity_name' >$activity_name</textarea>
                                    </div>

                                </div>";

                    echo "<table class='table'><tbody>";
                    $x = 0;
                    foreach ($non_cse as $non_cse_item_id => $non_cse_item) {
                        $stock_id = $non_cse_item['stock_id'];
                        $stock_title = $non_cse_item['stock_title'];
                        $amount = $non_cse_item['amount'];
                        $quantity = $non_cse_item['quantity'];
                        $description = $non_cse_item['description'];
                        $unit_of_measure = $non_cse_item['unit_of_measure'];
                        $unit_of_measure_id = $non_cse_item['unit_of_measure_id'];

                        echo "  <tr >
                        <td  style='max-width:120px'>
                        <input name='ppmp_non_cse[$non_cse_rw_cnt][items][$x][non_cse_item_id]' class='form-control non_cse_item_id' type='hidden' value='$non_cse_item_id'>
                            <label for='stock'>Stock</label>
                            <select required name='ppmp_non_cse[$non_cse_rw_cnt][items][$x][stock_id]' class='form-control stock-paginated'  style='width: 100%;' onchange='getStockAmount(this)'>
                                <option value='$stock_id'>$stock_title</option>
                            </select>
                        </td>
                        <td style='max-width:40px'>
                            <label for='qty'>Quantity</label>
                            <input required name='ppmp_non_cse[$non_cse_rw_cnt][items][$x][qty]' class='form-control qty' type='number' value='$quantity'>
                        </td>
                        <td  style='max-width:120px'>
                            <label for='unit_of_measure'>Unit of Measure</label>
                            <select name='ppmp_non_cse[$non_cse_rw_cnt][items][$x][unit_of_measure_id]' class='form-control  unit-of-measure'  style='width: 100%;' required>
                                <option value='$unit_of_measure_id'>$unit_of_measure</option>
                            </select>
                        </td>
                        <td style='max-width:50px'>
                            <label for='amount'>Gross Amount</label>
                            <input required type='text' class='form-control mask-amount amt' placeholder='Amount' onkeyup='updateMainAmount(this)' value='" . number_format($amount, 2) . "'>
                            <input type='hidden' class='form-control main-amount amt ' name='ppmp_non_cse[$non_cse_rw_cnt][items][$x][amount]' placeholder='Amount' value='$amount'>
                        </td>
                        <td style='max-width:150px'>
                            <label for='description'>Description</label>
                            <textarea  name='ppmp_non_cse[$non_cse_rw_cnt][items][$x][description]' class='form-control description' style='max-width:100%'>$description</textarea>
                        </td>
                        <td style='width:100px'>
                            <button class='btn-xs btn-info add_non_cse_item_row'><i class='fa fa-plus fa-fw'></i></button>
                            <button class='btn-xs btn-warning remove_cse_row' type='button'><i class='fa fa-times fa-fw'></i></button>
                        </td>
                    </tr>";
                        $x++;
                    }
                    echo "</tbody></table>";
                    echo "</div>";
                    echo "</td>
                    </tr>";
                    $non_cse_rw_cnt++;
                }
            } else if ($model->cse_type === 'cse') {

                foreach ($items as $item) {
                    $stock_id = $item['stock_id'];
                    $stock_title = $item['stock_title'];
                    $amount = $item['amount'];
                    $unit_of_measure = $item['unit_of_measure'];
                    $unit_of_measure_id = $item['unit_of_measure_id'];
                    echo "<tr>
                    <td>
                    <div class='card'  style=' padding: 15px;' >
                    <input type='hidden' class='form-control cse_item_id' name='cse_items[$cse_row_cnt][cse_item_id]' value='{$item['id']}' >
                        <div class='row'>
                      
                            <div class='col-sm-3'>
                                <label for='stock-paginated'>Stock</label>
                                <select name='cse_items[$cse_row_cnt][stock_id]' class='form-control stock-paginated' onchange='getStockAmount(this)'>
                                <option value='$stock_id'>$stock_title</option>
                                </select>
                            </div>
                            <div class='col-sm-3'>
                                <label for=' unit-of-measure'>Unit of Measure</label>
                                <select name='cse_items[$cse_row_cnt][unit_of_measure_id]' class='form-control unit-of-measure' >
                                    <option value='$unit_of_measure_id'>$unit_of_measure</option>
                                </select>
                            </div>
                            <div class='col-sm-3'>
                                <label for='amt'>Amount</label>
                                <input type='text' class='form-control mask-amount amt' placeholder='Amount' onkeyup='updateMainAmount(this)' value='$amount'>
                                <input type='hidden' class='form-control main-amount amt ' name='cse_items[$cse_row_cnt][amount]' placeholder='Amount' value='$amount'>
                            </div>
                            <div class='col-sm-3 col-sm-offset-2 text-right' > 
                                <button class='btn-xs btn-success add_cse_row' type='button'><i class='fa fa-plus fa-fw'></i></button>
                                <button class='btn-xs btn-danger remove_cse_row' type='button'><i class='fa fa-times fa-fw'></i></button>
                            </div>
                        </div>
                        <div class='row'>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>January</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][jan_qty]' value='{$item['jan_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>February</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][feb_qty]' value='{$item['feb_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>March</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][mar_qty]' value='{$item['mar_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>April</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][apr_qty]' value='{$item['apr_qty']}' placeholder='Quantity'>
                            </div>
                  
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>May</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][may_qty]' value='{$item['may_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>June</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][jun_qty]' value='{$item['jun_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>July</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][jul_qty]' value='{$item['jul_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>August</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][aug_qty]' value='{$item['aug_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>September</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][sep_qty]' value='{$item['sep_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>October</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][oct_qty]' value='{$item['oct_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>November</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][nov_qty]' value='{$item['nov_qty']}' placeholder='Quantity'>
                            </div>
                            <div class=' col-sm-1' style='text-align: center;'>
                                <h6>December</h6>
                                <input type='number' class='form-control qty' name='cse_items[$cse_row_cnt][dec_qty]' value='{$item['dec_qty']}' placeholder='Quantity'>
                          
                            </div>
                        </div>
                        </div>

                    </td>
    
                    </tr>";
                    $cse_row_cnt++;
                }
            }
            ?>

        </tbody>
    </table>
    <div class="row justify-content-center">
        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>

        </div>
    </div>

    <?= Html::endForm(); ?>

</div>
<style>
    .panel {
        padding: 2rem;
    }

    textarea {
        max-width: 100%;
    }

    .error {
        color: red;
    }

    table {
        margin-top: 12px
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
// $this->registerJsFile(yii::$app->request->baseUrl . "/js/select2.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/js/validate.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<script>
    let row_cnt = <?= $cse_row_cnt ?>;
    let non_cse_rw_cnt = <?= $non_cse_rw_cnt ?>;
    let fund_source_data = []

    function nonCseItemRowNum(name, row_num) {
        let qqt = name.split(/\[(.*?)\]/).filter(Boolean)
        let new_name = ''

        qqt[3] = parseFloat(row_num) + 1
        $.each(qqt, (key, val) => {

            if (key === 0) {
                new_name += val
            } else {
                new_name += '[' + val + ']'
            }
        })
        console.log(new_name)
        return new_name
    }

    function updateMainAmount(q) {
        $(q).parent().find('.main-amount').val($(q).maskMoney('unmasked')[0])
    }

    function hideShowActName(q) {
        console.log($(q).val())
        if ($(q).val() == 'fixed expenses') {
            $(q).closest('td').find('.activity_name').hide()
        } else {
            $(q).closest('td').find('.activity_name').show()
        }
    }

    function addNonCse() {
        // type
        // early_procurement
        // fk_mode_of_procurement_id
        // activity_name
        // fk_fund_source_id
        // proc_act_sched
        let non_cse_row = `
            <tr>
                <td>
                <div class='card'  style=' padding: 15px;' >
                    <div class='row'>
                        <div class='col-sm-3'>
                            <label for="type">Activity/Fixed Expenses</label>
                            <select required name="ppmp_non_cse[${non_cse_rw_cnt}][type]" class='form-control type activity_fixed_expense'  style='width: 100%;' onchange='hideShowActName(this)'>
                                <option value=''>Select</option>
                                
                            </select>
                        </div>
                        <div class='col-sm-2' >
                             <label for="early_procurement">Is this an Early Procurement?</label>
                            <select required name="ppmp_non_cse[${non_cse_rw_cnt}][early_procurement]" class='form-control early_procurement'  style='width: 100%;'>
                  
                            </select>
                        </div>
                        <div class='col-sm-3'>
                        <label for="fk_fund_source_id">Fund Source</label>
                            <select required name="ppmp_non_cse[${non_cse_rw_cnt}][fk_fund_source_id]" class='form-control fk_fund_source_id'  style='width: 100%;'>
                                <option value=''>Select</option>
          
                            </select>
                        </div>
                        <div class='col-sm-3'>
                            <label for='fk_mode_of_procurement_id'>Mode of Procurement</label>
                            <select required name='ppmp_non_cse[${non_cse_rw_cnt}][fk_mode_of_procurement_id]' class='form-control mode_of_procurement'  style='width: 100%;'>
                            <option value=''>Select</option>

                            </select>
                        </div>
                        <div class="col-sm-1  text-right">

                            <button class="btn-xs btn-success add_non_cse_row"><i class='fa fa-plus fa-fw'></i></button>
                            <button class="btn-xs btn-danger remove_cse_row" type="button"><i class='fa fa-times fa-fw'></i></button>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-sm-12 activity_name">
                            <label for='activity_name'>Activity Name</label>
                            <textarea  type="text" name="ppmp_non_cse[${non_cse_rw_cnt}][activity_name]" class="form-control activity_name" required></textarea>
                        </div>
                  
             
                    </div>
                    <table class="table">
                        <tbody>
                            <tr>
                      
                                <td  style='max-width:120px'>
                                    <label for="stock-paginated">Stock</label>
                                    <select name="ppmp_non_cse[${non_cse_rw_cnt}][items][1][stock_id]" class='form-control stock-paginated'  style='width: 100%;' onchange='getStockAmount(this)' required></select>
                                </td>
                                <td style='max-width:40px'>
                                    <label for="qty">Quantity</label>
                                    <input required name="ppmp_non_cse[${non_cse_rw_cnt}][items][1][qty]" class='form-control qty' type="number" >
                                </td>
                                <td  style='max-width:120px'>
                                    <label for="unit-of-measure">Unit of Measure</label>
                                    <select name="ppmp_non_cse[${non_cse_rw_cnt}][items][1][unit_of_measure_id]" class='form-control  unit-of-measure'  style='width: 100%;' required></select>
                                </td>
                                <td style='max-width:50px'>
                                    <label for='amt'>Gross Amount</label>
                                    <input type='text' required class='form-control mask-amount amt' placeholder='Amount' onkeyup='updateMainAmount(this)'>
                                    <input type='hidden' required class='form-control main-amount amt ' name='ppmp_non_cse[${non_cse_rw_cnt}][items][1][amount]' placeholder='Amount'>
                                </td>
                                <td style='max-width:150px'>
                                    <label for='description' >Description</label>
                                    <textarea name='ppmp_non_cse[${non_cse_rw_cnt}][items][1][description]' class='form-control description' style='max-width:100%' rows='1' ></textarea>
                                </td>
                                <td style='width:100px' class='text-right'>
                                    <button class="btn-xs btn-info add_non_cse_item_row"><i class='fa fa-plus fa-fw'></i></button>
                                    <button class="btn-xs btn-warning remove_cse_row" type="button"><i class='fa fa-times fa-fw'></i></button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                 </div>
                </td>
            </tr>`
        $('#table_body').append(non_cse_row)
        paginatedStockSelect()
        maskAmount()
        fund_source_select()
        ActivityOrFixedExpenseSelect()
        EarlyProcurementSelect()
        modeOfProcurementSelect()
        unitOfMeasureSelect()
        non_cse_rw_cnt++


    }

    function fund_source_select() {
        $('.fk_fund_source_id').select2({
            data: fund_source_data.fund_sources,
            placeholder: "Select Fund Source",
            containerCssClass: function(e) {
                return $(e).attr('required') ? 'required' : '';
            }

        })
    }

    function modeOfProcurementSelect() {
        $('.mode_of_procurement').select2({
            data: mode_of_procurement_data.modes,
            placeholder: "Select Mode of Procurement",
            containerCssClass: function(e) {
                return $(e).attr('required') ? 'required' : '';
            }

        })
    }

    function ActivityOrFixedExpenseSelect() {
        const arr = [

            {
                'id': 'activity',
                'text': 'Activity'
            },
            {
                'id': 'fixed expenses',
                'text': 'Fixed Expenses '
            }
        ];

        $('.activity_fixed_expense').select2({
            data: arr,
            placeholder: "Select Fund Source",
            containerCssClass: function(e) {
                return $(e).attr('required') ? 'required' : '';
            }

        })
    }

    function EarlyProcurementSelect() {
        const arr = [

            {
                'id': 0,
                'text': 'No'
            },
            {
                'id': 1,
                'text': 'Yes'
            }
        ];

        $('.early_procurement').select2({
            data: arr,
            placeholder: "Select is this early Procurement?",
            containerCssClass: function(e) {
                return $(e).attr('required') ? 'required' : '';
            }

        })
    }

    function getStockAmount(q) {

        const stock_id = $(q).val()
        console.log(stock_id)
        $.ajax({
            type: 'POST',
            url: window.location.pathname + '?r=supplemental-ppmp/get-stock-amount',
            data: {
                id: stock_id
            },
            success: function(data) {
                const res = JSON.parse(data)
                console.log(res)
                const tr = $(q).closest('tr')
                tr.find('.amt').val(res.amount)
                // tr.find('.unit_of_measure').val(res.amount)
                var data = {
                    id: res.id,
                    text: res.unit_of_measure
                };

                var newOption = new Option(data.text, data.id, false, false);
                tr.find('.unit-of-measure').append(newOption).trigger('change');
                tr.find('.unit-of-measure').val(res.id); // Select the option with a value of '1'
                tr.find('.unit-of-measure').trigger('change'); // No
            }
        })
    }
    async function q() {
        let fund_s = await getAllFundSource()
        fund_source_data = fund_s
        fund_source_select()

    }
    let mode_of_procurement_data = []
    async function modeOfProcurementData() {
        let query = await getAllModeOfProcurement()
        mode_of_procurement_data = query

        modeOfProcurementSelect()

    }

    $(document).ready(function() {

        q()
        modeOfProcurementData()
        ActivityOrFixedExpenseSelect()
        EarlyProcurementSelect()
        maskAmount()
        paginatedStockSelect()
        unitOfMeasureSelect()

        $('#table_body').on('.mask-amount', 'change keyup', function() {
            // $(this).closesthidden tr').find('.main-amount').val($(this).maskMoney('unmasked')[0])
            $(this).parent().find('.main-amount').val($(this).maskMoney('unmasked')[0])
            console.log($(this).val())
        })
        // ADD CSE ROW
        $('#entry_table').on('click', '.add_cse_row', function(event) {
            const source = $(this).closest('tr');
            source.find('.stock-paginated').select2('destroy')
            source.find('.unit-of-measure').select2('destroy')
            const clone = source.clone(true);
            clone.find('.qty').val('')
            clone.find('.stock-paginated').val('')
            clone.find('.unit-of-measure').val('')
            clone.find('.cse_item_id').remove()
            // console.log(clone.find('.qty').attr('name'))
            clone.find('.stock-paginated').attr('name', `cse_items[${row_cnt}][stock_id]`)
            clone.find('.unit-of-measure').attr('name', `cse_items[${row_cnt}][unit_of_measure_id]`)
            $('input[name^="cse_items"]').each(function(key, val) {
                console.log(val)
                const attr_name = $(val).attr('name')
                const suffix = attr_name.match(/\d+/);
                // console.log(suffix[0])
                clone.find(`input[name^='${attr_name}`).attr('name', attr_name.replace(suffix[0], row_cnt))
            });
            clone.find('.amt').val('')
            $('#table_body').append(clone)
            maskAmount()
            paginatedStockSelect()
            unitOfMeasureSelect()
            row_cnt++
        });
        $('#entry_table').on('click', '.remove_cse_row', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });


        // ADD NON CSE ITEMS
        $('#entry_table').on('click', '.add_non_cse_item_row', function(event) {
            event.preventDefault();
            const source = $(this).closest('tr');
            $('.stock-paginated').select2('destroy')
            $('.unit-of-measure').select2('destroy')
            const clone = source.clone(true);
            const c_table = $(this).closest('tbody').clone(true)
            const amt = clone.find('.main-amount')
            const description = clone.find('.description')
            const qty = clone.find('.qty')
            const stock = clone.find('.stock-paginated')
            const unit_of_measure = clone.find('.unit-of-measure')
            clone.find('.mask-amount').val('')
            amt.val(0)
            qty.val(0)
            description.val('')
            stock.val('')
            unit_of_measure.val('')
            let qty_name = qty.attr('name')
            let amt_name = amt.attr('name')
            let description_name = description.attr('name')
            let stock_name = stock.attr('name')
            let unit_of_measure_name = unit_of_measure.attr('name')
            let max = 0

            let non_cse_item_row_name = qty_name.slice(0, 22)
            $(`input[name^='${non_cse_item_row_name}'`).each((key, val) => {
                let r = val.getAttribute('name').split(/\[(.*?)\]/).filter(Boolean)

                if (parseInt(r[3]) > max) {
                    max = r[3]
                }

            })


            qty.attr('name', nonCseItemRowNum(qty_name, max))
            amt.attr('name', nonCseItemRowNum(amt_name, max))
            unit_of_measure.attr('name', nonCseItemRowNum(unit_of_measure_name, max))
            description.attr('name', nonCseItemRowNum(description_name, max))
            stock.attr('name', nonCseItemRowNum(stock_name, max))
            const attr_name = clone.find('.stock-paginated').attr('name')
            const suffix = attr_name.match(/\d+/);


            $(this).closest('tbody').append(clone)
            paginatedStockSelect()
            maskAmount()
            unitOfMeasureSelect()
        });


        $('#entry_table').on('click', '.add_non_cse_row', function(event) {
            event.preventDefault();
            addNonCse()
            // const source = $(this).closest('tr');
            // const clone = source.clone(true);
            // clone.find('.activity_name').attr('name',)
            // clone.find('.table').find("tr:gt(0)").remove();

        });
        $('#cse_type').change(() => {
            const type = $('#cse_type').val()
            $('#table_body').html('')

            if (type == 'cse') {
                const row = `<tr>
                <td>
                <div class='card'  style=' padding: 15px;' >
                    <div class="row">
                
                        <div class="col-sm-3">
                            <label for="stock">Stock</label>
                            <select name="cse_items[0][stock_id]" class='form-control stock-paginated' onchange='getStockAmount(this)'></select>
                        </div>
                        <div class="col-sm-3">
                            <label for="unit_of_measure">Unit of Measure</label>
                            <select name="cse_items[0][unit_of_measure_id]" class='form-control unit-of-measure' ></select>
                        </div>
                        <div class="col-sm-3">
                            <label for='amount'>Amount</label>
                            <input type='text' class='form-control mask-amount amt' placeholder='Amount' onkeyup='updateMainAmount(this)'>
                            <input type='hidden' class='form-control main-amount amt ' name='cse_items[0][amount]' placeholder='Amount'>
                        </div>
                        <div class='col-sm-3 text-right' > 
                                <button class='btn-xs btn-success add_cse_row' type='button'><i class='fa fa-plus fa-fw'></i></button>
                                <button class='btn-xs btn-danger remove_cse_row' type='button'><i class='fa fa-times fa-fw'></i></button>
                        </div>
                    </div>
                    <div class='row'>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>January</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][jan_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>February</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][feb_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>March</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][mar_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>April</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][apr_qty]' placeholder='Quantity'>
                        </div>
              
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>May</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][may_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>June</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][jun_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>July</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][jul_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>August</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][aug_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>September</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][sep_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>October</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][oct_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>November</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][nov_qty]' placeholder='Quantity'>
                        </div>
                        <div class=' col-sm-1' style='text-align: center;'>
                            <h6>December</h6>
                            <input type='number' class='form-control qty' name='cse_items[0][dec_qty]' placeholder='Quantity'>
                      
                        </div>
                    </div>
                    </div>
                </td>

                </tr>`

                $('#table_body').append(row)
                unitOfMeasureSelect()
            } else {
                addNonCse()
            }
            maskAmount()
            paginatedStockSelect()
        })
        const registrationForm = $('#SupplementalPpmp');
        if (registrationForm.length) {
            registrationForm.validate({
                rules: {
                    budget_year: {
                        required: true
                    },
                    cse_type: {
                        required: true
                    },
                    fk_office_id: {
                        required: true
                    },
                    fk_division_id: {
                        required: true
                    },
                    fk_division_program_unit_id: {
                        required: true
                    },
                    fk_prepared_by: {
                        required: true
                    },
                    fk_reviewed_by: {
                        required: true
                    },
                    fk_approved_by: {
                        required: true
                    },
                    fk_certified_funds_available_by: {
                        required: true
                    },






                },
                messages: {

                    budget_year: {
                        required: '  Budget Year is Required'
                    },
                    cse_type: {
                        required: 'CSE/Non CSE Type is Required',

                    },
                    fk_office_id: {
                        required: 'Office is Required'
                    },

                    fk_division_id: {
                        required: 'Division is Required!'
                    },
                    fk_division_program_unit_id: {
                        required: 'Division/Program/Unit is Required'
                    },
                    fk_prepared_by: {
                        required: 'Prepared By is Required'
                    },
                    fk_reviewed_by: {
                        required: 'Reviewed By is Required'
                    },
                    fk_approved_by: {
                        required: 'Approved By is Required'
                    },
                    fk_certified_funds_available_by: {
                        required: 'Certified Funds Available By is Required'
                    },

                },
                errorPlacement: function(error, element) {
                    if (element.is("textarea")) {
                        element.parent().append(error)
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



        $('#lgmdModal').click(function() {
            $('#lgModal').modal('show').find('#lgModalContent').load($(this).attr('value'));
        });
    })
</script>

<?php
SweetAlertAsset::register(($this));

$js = <<<JS
    $(document).ready(()=>{
        $('#SupplementalPpmp').on('submit', function(e) {
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
                                timer: 5000,
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
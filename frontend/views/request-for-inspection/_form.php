<?php

use app\models\Divisions;
use app\models\Office;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use common\models\User;
use kartik\form\ActiveForm;
use kartik\grid\GridView;
use kartik\icons\Icon;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\bootstrap\Button;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;

$this->registerJsFile("@web/js/vue.js", ['position' => $this::POS_HEAD]);
$userData = User::getUserDetails();
/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspection */
/* @var $form yii\widgets\ActiveForm */

if (!empty($error)) {

    echo SweetAlert::widget([
        'options' => [
            'title' => "Failed",
            'type' => 'error'
        ]
    ]);
}

$transaction_type = [
    'with_po' => 'with PO',
    'without_po - Plane Ticket' => 'without PO - Plane Ticket',
    'without_po - DBM PS APR' => 'without PO - DBM PS APR',
    'without_po - Fuel' => 'without PO - Fuel',
    'without_po - agency-to agency' => 'without PO - agency-to agency',
    'without_po - petty cash' => 'without PO - Petty Cash'
];
$entry_row = 1;
$no_po_item_row = 1;
$chairperson = '';
$inspector = '';
$property_unit = '';
$requested_by = '';
$rspnse_center = '';
if (!empty($model->fk_chairperson)) {
    $chairpersonQuery = Yii::$app->db->createCommand("SELECT employee_name,employee_id FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_chairperson)->queryAll();
    $chairperson = ArrayHelper::map($chairpersonQuery, 'employee_id', 'employee_name');
}
if (!empty($model->fk_inspector)) {
    $inspectorQuery = Yii::$app->db->createCommand("SELECT employee_name,employee_id FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_inspector)->queryAll();
    $inspector = ArrayHelper::map($inspectorQuery, 'employee_id', 'employee_name');
}
if (!empty($model->fk_property_unit)) {
    $property_unitQuery = Yii::$app->db->createCommand("SELECT employee_name,employee_id FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_property_unit)->queryAll();
    $property_unit = ArrayHelper::map($property_unitQuery, 'employee_id', 'employee_name');
}
if (!empty($model->fk_requested_by)) {
    $requested_byQuery = Yii::$app->db->createCommand("SELECT employee_name,employee_id FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_requested_by)->queryAll();
    $requested_by = ArrayHelper::map($requested_byQuery, 'employee_id', 'employee_name');
}
if (!empty($model->fk_responsibility_center_id)) {

    $rspnse_centerQuery = Yii::$app->db->createCommand("SELECT id,responsibility_center.`name` FROM responsibility_center WHERE responsibility_center.id = :id")
        ->bindValue(':id', $model->fk_responsibility_center_id)->queryAll();
    $rspnse_center = ArrayHelper::map($rspnse_centerQuery, 'id', 'name');
}

$no_po_display = 'display:none';
$with_po_display = 'display:none';
if (!empty($model->transaction_type)) {

    if ($model->transaction_type === 'with_po') {
        $with_po_display = '';
    } else {
        $no_po_display = '';
    }
}


?>

<div id="app">
    <div class=" card" style="padding:1rem">
        <?php
        // Html::beginForm([$action, 'id' => $model->id], 'post', ['id' => 'rfi_form']);
        ?>
        <?php $form = ActiveForm::begin([
            'id' => $model->formName()
        ]); ?>
        <div class="row">
            <?php

            if (YIi::$app->user->can('ro_inspection_admin')) {

            ?>
                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Office::find()->asArray()->all(), 'id', 'office_name'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Office'
                        ]
                    ]) ?>
                </div>

            <?php }

            if (YIi::$app->user->can('po_inspection_admin') || YIi::$app->user->can('ro_inspection_admin')) {
            ?>

                <div class="col-sm-2">
                    <?= $form->field($model, 'fk_division_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map(Divisions::find()->asArray()->all(), 'id', 'division'),
                        'pluginOptions' => [
                            'placeholder' => 'Select Division'
                        ]
                    ]) ?>
                </div>
            <?php } ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'name' => 'date',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                    ]
                ]) ?>
            </div>
            <?php if (YIi::$app->user->can('ro_inspection_admin')) { ?>
                <div class="col-sm-2">

                    <?= $form->field($model, 'fk_responsibility_center_id')->widget(Select2::class, [
                        'data' => $rspnse_center,
                        'value' => $model->fk_responsibility_center_id,
                        'name' => 'fk_responsibility_center_id',
                        'options' => ['placeholder' => 'Search for a Responsibility Center ...'],
                        'pluginOptions' => [
                            'allowClear' => false,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=responsibility-center/search-responsibility-center',
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
            <?php } ?>
            <div class="col-sm-2">
                <?= $form->field($model, 'transaction_type')->widget(Select2::class, [
                    'data' => $transaction_type,
                    'name' => 'transaction_type',
                    'id' => 'transaction_type',
                    'options' => ['placeholder' => 'Select Transaction Type'],
                    'pluginOptions' => [],

                ]) ?>

            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'fk_requested_by')->widget(Select2::class, [
                    'data' => $requested_by,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => false,
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
                <?= $form->field($model, 'fk_chairperson')->widget(Select2::class, [
                    'data' => $chairperson,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => false,
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
            <?php
            $empOffice  = $userData->employee->office->office_name ?? '';
            $userOffice = Yii::$app->user->identity->office->office_name ?? '';

            if (strtolower($empOffice)  === 'ro') :
            ?>
                <div class="col-sm-3">

                    <?= $form->field($model, 'fk_inspector')->widget(Select2::class, [
                        'data' => $inspector,
                        'options' => ['placeholder' => 'Search for a Employee ...'],
                        'pluginOptions' => [
                            'allowClear' => false,
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
            <?php endif; ?>
            <div class="col-sm-3">
                <?= $form->field($model, 'fk_property_unit')->widget(Select2::class, [
                    'data' => $property_unit,
                    'options' => ['placeholder' => 'Search for a Employee ...'],
                    'pluginOptions' => [
                        'allowClear' => false,
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
        <table id="no_po_entry" style='<?= $no_po_display ?>'>

            <thead>
                <tr>
                    <th>Project Name</th>
                    <th>Stock Name</th>
                    <th>Specification</th>
                    <th>Unit of Measure</th>
                    <th>Payee</th>
                    <th>Unit Cost</th>
                    <th>Quantity</th>
                    <th>From Date</th>
                    <th>To Date</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($no_po_items)) {
                    foreach ($no_po_items as $item) {
                        $id = $item['id'];
                        $project_name = $item['project_name'];
                        $stock_title = $item['stock_title'];
                        $specification = $item['specification'];
                        $specification_view = $item['specification_view'];
                        $unit_of_measure = $item['unit_of_measure'];
                        $unit_of_measure_id = $item['unit_of_measure_id'];
                        $payee_name = $item['payee_name'];
                        $payee_id = $item['payee_id'];
                        $unit_cost = $item['unit_cost'];
                        $quantity = $item['quantity'];
                        $from_date = $item['from_date'];
                        $to_date = $item['to_date'];
                        $stock_id = $item['stock_id'];
                        echo "  <tr>
                                    <td style='display:none;'>
                                        <input type='hidden' class='no_po_item_id form-control' name='noPoItems[$no_po_item_row][item_id]' value='$id'>
                                    </td>
                                    <td class='text_area'>
                                        <textarea name='noPoItems[$no_po_item_row][project_name]' cols='20' rows='1' class='project_name form-control'>$project_name</textarea>
                                    </td>
                                    <td class='stock_col'>
                                        <select name='noPoItems[$no_po_item_row][stock_name]' class='stock_name stock form-control'>
                                            <option value='$stock_id'>$stock_title</option>
                                        </select>
                                    </td>
                                    <td class='text_area'>
                                        <textarea cols='20' rows='1' class='specification-view form-control' >$specification_view</textarea>
                                        <textarea  name='noPoItems[$no_po_item_row][specification]' cols='20' rows='1' class='specification form-control'>$specification</textarea>
                                    </td>
                                    <td>
                                        <select name='noPoItems[$no_po_item_row][unit_of_measure]' class='unit-of-measure form-control'>
                                            <option value='$unit_of_measure_id'>$unit_of_measure</option>
                                        </select>
                                    </td>
                                    <td class='payee_col'>
                                        <select name='noPoItems[$no_po_item_row][payee]' class='payee form-control'>
                                            <option value='$payee_id'>$payee_name</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type='text' class='mask-amount form-control' value='$unit_cost'>
                                        <input type='hidden' class='unit_cost  main-amount form-control' name='noPoItems[$no_po_item_row][unit_cost]' value='$unit_cost'>
                                    </td>
                                    <td class='quantity_col'>
                                        <input type='number' class='no_po_quantity form-control' name='noPoItems[$no_po_item_row][no_po_quantity]' value='$quantity'>
                                    </td>
                                    <td>
                                        <input type='date' class='from_date form-control' name='noPoItems[$no_po_item_row][from_date]' value='$from_date'>
                                    </td>
                                    <td>
                                        <input type='date' class='to_date form-control' name='noPoItems[$no_po_item_row][to_date]' value='$to_date'>
                                    </td>
                                <td>
                                    <div class='btn-group'>
                                        <a class='add_no_po_entry btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                                        <a class='remove_no_po_entry btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                    </div>
                                </td>
                            </tr>";
                        $no_po_item_row++;
                    }
                }
                ?>

            </tbody>
        </table>
        <div class="with_po" style="<?= $with_po_display ?>">
            <table id="entry_table">
                <thead>
                    <tr>
                        <th>Po Number</th>
                        <th>Project Name</th>
                        <th>Stock Name</th>
                        <th>Specification</th>
                        <th>Unit of Measure</th>
                        <th>Payee</th>
                        <th>Balance Quantity</th>
                        <th>Unit Cost</th>
                        <th>Division</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>From Date</th>
                        <th>To Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php


                    if (!empty($items)) {
                        foreach ($items as $val) {
                            $unit_cost = number_format($val['unit_cost'], 2);
                            echo "<tr>
                                    <td style='display:none'><input class='item_id' value='{$val['id']}' name='poItems[$entry_row][item_id]'/></td>
                                    <td style='display:none'><input class='po_aoq_item_id' value='{$val['po_aoq_item_id']}' name='poItems[$entry_row][purchase_order_id]'/></td>
                                    <td>
                                        <span class='activity' >{$val['po_number']}</span>
                                    </td>
                                    <td class='limit-width'>
                                        <span class='activity' >{$val['project_title']}</span>
                                    </td>
                                    <td class='limit-width'>
                                        <span class='activity' >{$val['stock_title']}</span>
                                    </td>
                                    <td class='limit-width'>
                                        <span class='activity' >{$val['specification']}</span>
                                    </td>
                                    <td class='limit-width'>
                                        <span class='activity' >{$val['unit_of_measure']}</span>
                                    </td>
                                    <td>
                                        <span class='activity' >{$val['payee']}</span>
                                    </td>
                                    <td class='center'>
                                        <span class='activity' >{$val['balance_quantity']}</span>
                                    </td>
                                    <td class='center'>
                                        <span class='activity' >{$unit_cost}</span>
                                    </td>
                                    <td>
                                        <span class='activity' >{$val['division']}</span>
                                    </td>
                                    <td>
                                        <span class='activity' >{$val['unit']}</span>
                                    </td>
                                    <td>
                                    <input value='{$val['quantity']}'  class='quantity form-control' type='text' name='poItems[$entry_row][quantity]'/>
                                    </td>
                                    <td>
                                    <input   name='poItems[$entry_row][date_from]' class='date_from form-control' type='date'  value='{$val['date_from']}' required />
                                    </td>
                                    <td>
                                    <input name='poItems[$entry_row][date_to]'  class='date_to form-control' type='date'  value='{$val['date_to']}' required/>
                                    </td>
                                
                                    <td style='float:left;'>
                                        <a class='add_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                                        <a class='remove btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                                    </td>
                            </tr>";
                            $entry_row++;
                        }
                    }

                    ?>
                </tbody>
            </table>
        </div>
        <div class="row justify-content-center">
            <div class="col-sm-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%;margin:3rem 0 4rem 0']); ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>




    <div class="with_po" style="<?= $with_po_display ?>">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'panel' => [
                'type' => Gridview::TYPE_PRIMARY,
                'heading' => "List of PO's"
            ],
            'pjax' => true,
            'pjaxSettings' => [
                'options' => [
                    'id' => 'po_pjax'

                ]
            ],
            'columns' => [
                [
                    'label' => 'Action',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return  Html::button(Icon::show('plus', ['framework' => Icon::FA]), ['class' => 'btn-xs btn-primary  add', 'onClick' => 'addPoItem(this)']);
                    }
                ],
                [
                    'label' => 'Action',
                    'format' => 'raw',
                    'hidden' => true,
                    'value' => function ($model) {
                        return "<input value='{$model->po_aoq_item_id}'  class='po_id' type='hidden'/>";
                    }
                ],
                'po_number',
                'project_title',
                'stock_title',
                'specification',
                'unit_of_measure',
                'payee',
                'quantity',
                'unit_cost',
                'division',
                'unit',
                [
                    'label' => 'Action',
                    'format' => 'raw',
                    'hidden' => true,
                    'value' => function ($model) {
                        return "<input value='{$model->quantity}'  class='quantity form-control' type='hidden'/>";
                    }
                ],
                [
                    'label' => 'Action',
                    'format' => 'raw',
                    'hidden' => true,
                    'value' => function ($model) {
                        return "<input   class='date_from form-control' type='date'/>";
                    }
                ],
                [
                    'label' => 'Action',
                    'format' => 'raw',
                    'hidden' => true,
                    'value' => function ($model) {
                        return "<input  class='date_to form-control' type='date'/>";
                    }
                ],

            ],
        ]); ?>
    </div>




</div>
<style>
    .error {
        color: red;
    }

    .specification {
        display: none;
    }

    .payee_col,
    .stock_col {
        min-width: 20rem;
    }

    .quantity_col {
        max-width: 10rem;
    }

    .center {
        text-align: center;
    }

    textarea {
        max-width: 100%;
    }

    .text_area {
        max-width: 30rem;
    }

    .grid-view td {
        white-space: normal;
        width: 5rem;
        padding: 0;
    }

    .container {
        background-color: white;
        padding: 5px;
    }

    .request-for-inspection-form {
        background-color: white;
        padding: 5px;
    }

    #entry_table {
        width: 100%;
    }

    td,
    th {
        padding: 5px;
    }

    .limit-width {
        max-width: 40rem;
    }
</style>
<?php
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile("@web/js/validate.min.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let entry_row = <?= $entry_row ?>;
    let no_po_item_row = <?= $no_po_item_row ?>;
    let with_error = 0;

    function addPoItem(row) {
        const source = $(row).closest('tr')
        const clone = source.clone()
        clone.find('.quantity').attr('type', 'text')
        clone.find('.quantity').parent().attr('class', '')
        clone.find('.quantity').attr('name', `poItems[${entry_row}][quantity]`)

        clone.find('.date_from').parent().attr('class', '')
        clone.find('.date_from').prop('required', true)
        clone.find('.date_from').attr('name', `poItems[${entry_row}][date_from]`)


        clone.find('.date_to').parent().attr('class', '')
        clone.find('.date_to').prop('required', true)
        clone.find('.date_to').attr('name', `poItems[${entry_row}][date_to]`)

        clone.find('.add').parent().remove()
        clone.find('.po_id').attr('name', `poItems[${entry_row}][purchase_order_id]`)
        clone.append(` <td style='float:left;'>
                            <a class='add_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                            <a class='remove btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </td>`)
        $('#entry_table tbody').append(clone)
        entry_row++
    }

    function addNoPoItem() {
        $('#no_po_entry tbody').append(`<tr>
                    <td class='text_area'>
                        <textarea name="noPoItems[${no_po_item_row}][project_name]" cols="20" rows="1" class="project_name form-control" required></textarea>
                    </td>
                    <td>
                        <select name="noPoItems[${no_po_item_row}][stock_name]" class="stock_name stock form-control" required>
                            <option value="">Select Stock</option>
                        </select>
                    </td>
                    <td class='text_area'>
                    <textarea cols="20" rows="1" class="specification-view form-control"></textarea>
                        <textarea name="noPoItems[${no_po_item_row}][specification]" cols="20" rows="1" class="specification form-control"></textarea>
                    </td>
                    <td>
                        <select name="noPoItems[${no_po_item_row}][unit_of_measure]" class="unit-of-measure form-control" required>
                            <option value="">Select Unit of Measure</option>
                        </select>
                    </td>
                    <td class='payee_col'>
                        <select name="noPoItems[${no_po_item_row}][payee]" class="payee form-control" required>
                            <option value="">Select Payee</option>
                        </select>
                    </td>
                    <td>
                    <input type='text' class='mask-amount form-control' required>
                        <input type="hidden" class="unit_cost main-amount form-control" name="noPoItems[${no_po_item_row}][unit_cost]">
                    </td>
                    <td class='quantity_col'>
                        <input type="number" class="no_po_quantity form-control" name="noPoItems[${no_po_item_row}][no_po_quantity]" required>
                    </td>
                    <td>
                        <input type="date" class="from_date form-control" name="noPoItems[${no_po_item_row}][from_date]" required>
                    </td>
                    <td>
                        <input type="date" class="to_date form-control" name="noPoItems[${no_po_item_row}][to_date]" required>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a class='add_no_po_entry btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                            <a class='remove_no_po_entry btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </div>
                    </td>
                </tr>`)
        payeeSelect()
        stockSelect()
        unitOfMeasureSelect()
        maskAmount()
        no_po_item_row++
    }
    $(document).ready(function() {
        $('#no_po_entry').on('click', '.add_no_po_entry', (e) => {
            e.preventDefault()
            addNoPoItem()
        })
        rfiPurchaseOrderSelect()
        payeeSelect()
        stockSelect()
        unitOfMeasureSelect()
        maskAmount()
        $('#entry_table').on('click', '.remove', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('#no_po_entry').on('click', '.remove_no_po_entry', function(event) {
            event.preventDefault();
            $(this).closest('tr').remove();
        });
        $('#entry_table').on('change', '.purchase-order', function() {
            // console.log($(this).closest('tr').find('.activity_title').text())
            const activity_title = $(this).closest('tr').find('.activity_title')
            const po_date = $(this).closest('tr').find('.po_date')
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=pr-purchase-order/po-details',
                data: {
                    po_id: $(this).val()
                },
                success: function(data) {
                    console.log(data)
                    const res = JSON.parse(data)
                    activity_title.text('')
                    po_date.text('')
                    activity_title.text(res.project_name)
                    po_date.text(res.po_date)
                }
            })

        })
        $('#entry_table').on('click', '.add_row', function(event) {
            const source = $(this).closest('tr');
            source.find('.purchase-order').select2('destroy')
            const clone = source.clone(true);
            clone.find('.purchase-order').val('')
            clone.find('.activity_title').text('')
            clone.find('.po_date').text('')
            clone.find('.po_date').text('')
            clone.find('.item_id').remove()
            clone.find('.purchase-order').attr('name', `purchase_order_id[${entry_row}]`)
            $('#entry_table tbody').append(clone)
            rfiPurchaseOrderSelect()
            entry_row++
        });

        $('#requestforinspection-transaction_type').change(() => {
            const type = $('#requestforinspection-transaction_type').val()

            if (type === 'with_po') {
                $('.with_po').show()
                $('#no_po_entry').hide()
                $('#no_po_entry tbody').html('')
                no_po_item_row = 0
            } else {
                $('.with_po').hide()
                $('#no_po_entry').show()
                addNoPoItem()
            }
        })

        // $("#no_po_entry").on("keyup change", '.mask-amount', () => {
        //     var amount = $(this).maskMoney('unmasked')[0];
        //     var source = $(this).closest('tr');
        //     console.log($(this).val())
        //     source.children('td').eq(0).find('.main-amount').val(amount)

        // });
        $('#no_po_entry').on('change keyup', '.mask-amount', function() {
            $(this).closest('tr').find('.main-amount').val($(this).maskMoney('unmasked')[0])
        })
        $('#no_po_entry').on('change', '.specification-view', function(e) {
            e.preventDefault()
            var specs = $(this).val()
            var main_specs = $(this).closest('tr');
            specs = specs.replace(/\n/g, "[n]");
            specs = specs.replace(/"/g, '\'');
            main_specs.find('.specification').val(specs)
        })

        // $('#save').on('click', function(e) {
        //     e.preventDefault()
        //     const form = $('#RequestForInspection');

        //     $.ajax({
        //         type: 'POST',
        //         url: window.location.pathname + form.attr('action-href'),
        //         data: form.serialize(),
        //         success: function(data) {
        //             const res = JSON.parse(data)
        //             console.log(res.error_message)
        //             console.log(typeof res.error_message)
        //             if (!res.isSuccess) {

        //                 if (typeof res.error_message === 'object') {
        //                     console.log('object error')
        //                 } else if (typeof res.error_message === 'string') {
        //                     swal({
        //                         icon: 'error',
        //                         title: res.error_message,
        //                         type: "error",
        //                         timer: 3000,
        //                         closeOnConfirm: false,
        //                         closeOnCancel: false
        //                     })
        //                 }
        //             }
        //         },

        //     })
        //     return false;
        // })
        const registrationForm = $('#rfi_form');
        if (registrationForm.length) {
            registrationForm.validate({
                rules: {
                    fk_responsibility_center_id: {
                        required: true
                    },
                    transaction_type: {
                        required: true
                    },
                    fk_requested_by: {
                        required: true
                    },
                    fk_chairperson: {
                        required: true
                    },
                    fk_property_unit: {
                        required: true
                    },
                    date: {
                        required: true
                    }
                },
                messages: {
                    date: {
                        required: 'Date is Required!'
                    },
                    fk_responsibility_center_id: {
                        required: '  Responsibility Center is Required'
                    },
                    transaction_type: {
                        required: 'Transaction Type is Required',

                    },
                    fk_requested_by: {
                        required: 'Requested BY is Required'
                    },

                    fk_chairperson: {
                        required: 'Chairperson is Required!'
                    },
                    fk_property_unit: {
                        required: 'Property Unit is Required'
                    },

                },
                errorPlacement: function(error, element) {
                    console.log()
                    if (element.is(":radio")) {
                        error.appendTo(element.parents('.gender'));
                    } else if (element.is(":checkbox")) {
                        error.appendTo(element.parents('.hobbies'));
                    } else if (element.is("select")) {
                        error.appendTo(element.parents('.hobbies'));
                        element.parent().append(error)
                    } else if (element.is('.date_from') || element.is('.date_to')) {
                        error.insertAfter(element);
                    } else {

                        element.parent().parent().append(error)
                    }
                    with_error = 1

                },
                success: function() {
                    with_error = 0
                },
                invalidHandler: function() {
                    with_error = 1
                },


            });
        }


        $('#rfi_form').on('submit', function(e) {
            e.preventDefault()
            const form = $(this)
            if (with_error === 0) {
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
            }
        })


    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
    $("#RequestForInspection").on("beforeSubmit", function (event) {
        event.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: form.serialize(),
            success: function (data) {
                let res = JSON.parse(data)
                swal({
                    icon: 'error',
                    title: res,
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
JS;
$this->registerJs($js);
?>
<script>
    // new Vue({
    //     el: '#app',
    //     data: {
    //         msg: 'Message Ni'
    //     },
    //     mounted() {
    //         const vm = this;

    //         $('#entry_table').on('click', '.jqueryButton', function(event) {
    //             event.preventDefault();
    //             vm.handleClick(this);

    //         })
    //         $('#po_list').on('click', '.addItemBtn', function(event) {
    //             event.preventDefault();
    //             vm.addPoItem(this);

    //         });
    //     },
    //     methods: {
    //         handleClick(event) {

    //             const row = $(event).closest('tr');
    //             console.log(row)
    //             if (row) {
    //                 row.remove();
    //             }
    //         },
    //         addPoItem(ths) {
    //             console.log($(ths).closest('tr'))
    //             const source = $(ths).closest('tr')
    //             const clone = source.clone()
    //             clone.find('.add').parent().remove()
    //             $('#entry_table tbody').append(clone)
    //         },
    //     }
    // });
</script>
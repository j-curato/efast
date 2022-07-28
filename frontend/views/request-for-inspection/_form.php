<?php

use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\RequestForInspection */
/* @var $form yii\widgets\ActiveForm */


$entry_row = 1;
$chairperson = '';
$inspector = '';
$property_unit = '';
$requested_by = '';
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
    $requested_by_query = Yii::$app->db->createCommand("SELECT employee_name,employee_id FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $model->fk_requested_by)->queryAll();
    $requested_by = ArrayHelper::map($requested_by_query, 'employee_id', 'employee_name');
}
?>

<div class="request-for-inspection-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">

        <div class="col-sm-3">
            <?= $form->field($model, 'fk_requested_by')->widget(Select2::class, [
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
        <div class="col-sm-3">


            <?= $form->field($model, 'fk_chairperson')->widget(Select2::class, [
                'data' => $chairperson,
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
            <?= $form->field($model, 'fk_inspector')->widget(Select2::class, [
                'data' => $inspector,
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
            <?= $form->field($model, 'fk_property_unit')->widget(Select2::class, [
                'data' => $property_unit,
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






    <table id="entry_table">
        <thead>
            <tr>
                <th></th>
                <th>Activity/Project</th>
                <th>Purchase Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php

            if (!empty($items)) {

                foreach ($items as $val) {
                    echo "<tr>
                                <td style='display:none'><input class='item_id' value='{$val['id']}' name='item_id[$entry_row]'/></td>
                            <td>
                                <label for='purchase_order_id'> Purchase Order#</label>
                                <select required name='purchase_order_id[$entry_row]' class='purchase-order' style='width: 100%'>
                                <option value='{$val['po_id']}' selected>{$val['po_number']}</option>
                                </select>
                            </td>
                            <td>
                            <label for='activity'> </label>
                            <br>
                                <span class='activity' >{$val['project_title']}</span>
                            </td>
                            <td>
                            <label for='po_date'> </label>
                            <br>
                                 <span class='po_date'>{$val['po_date']}</span>
                            </td>
                            <td style='float:left;'>
                                <a class='add_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                                <a class='remove btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                            </td>
                        </tr>";
                    $entry_row++;
                }
            } else {

            ?>
                <tr>

                    <td>
                        <label for='purchase_order_id'> Purchase Order#</label>
                        <select required name='purchase_order_id[0]' class='purchase-order' style='width: 100%'>
                        </select>
                    </td>
                    <td>
                        <span class="activity_title"></span>
                    </td>
                    <td>
                        <span class="po_date"></span>
                    </td>
                    <td style='float:left;'>
                        <a class='add_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                        <a class='remove btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="form-group" style="margin-top: 1rem;">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    #entry_table {
        width: 100%;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let entry_row = <?= $entry_row ?>;
    $(document).ready(function() {
        rfiPurchaseOrderSelect()
        $('#entry_table').on('click', '.remove', function(event) {
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
            clone.find('.item_id').remove()
            clone.find('.purchase-order').attr('name', `purchase_order_id[${entry_row}]`)
            $('#entry_table tbody').append(clone)
            rfiPurchaseOrderSelect()
            entry_row++
        });
    })
</script>
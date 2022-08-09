<?php

use app\models\PurchaseOrdersForRfiSearch;
use aryelds\sweetalert\SweetAlert;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker as DateDatePicker;
use kartik\grid\GridView;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use yii\bootstrap\Button;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

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
// if (!empty($model->fk_requested_by_division)) {
//     $requested_by_query = Yii::$app->db->createCommand("SELECT UPPER(division) as division,id FROM divisions WHERE id = :id")
//         ->bindValue(':id', $model->fk_requested_by_division)->queryAll();
//     $requested_by = ArrayHelper::map($requested_by_query, 'id', 'division');
// }
if (!Yii::$app->user->can('super-user')) {
    $requested_by = ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,UPPER(CONCAT(division,'-',unit)) as division FROM pr_office WHERE division = :division")
        ->bindValue(':division', Yii::$app->user->identity->division)
        ->queryAll(), 'id', 'division');
} else {
    $requested_by = ArrayHelper::map(Yii::$app->db->createCommand("SELECT id,UPPER(CONCAT(division,'-',unit))  as division FROM pr_office")->queryAll(), 'id', 'division');
}

?>

<div class="request-for-inspection-form">
    <?php $form = ActiveForm::begin([
        'id' => $model->formName(),
        'enableAjaxValidation' => false,
    ]); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'name' => 'date',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]) ?>
        </div>
    </div>
    <div class="row">
        <?php

        // if (YIi::$app->user->can('super-user')) {

        ?>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_pr_office_id')->widget(Select2::class, [
                'data' => $requested_by,
                'options' => ['placeholder' => 'Search for a Division ...'],
                // 'pluginOptions' => [
                //     'allowClear' => true,
                //     'minimumInputLength' => 1,
                //     'language' => [
                //         'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                //     ],
                //     'ajax' => [
                //         'url' => Yii::$app->request->baseUrl . '?r=divisions/search-division',
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
        // }
        ?>
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

        <table id="entry_table">
            <thead>
                <tr>
                    <th>
                        Po Number
                    </th>
                    <th>
                        Project Name
                    </th>
                    <th>
                        Stock Name
                    </th>
                    <th>
                        Specification
                    </th>
                    <th>
                        Unit of Measure
                    </th>
                    <th>
                        Payee
                    </th>
                    <th>
                        Balance Quantity
                    </th>
                    <th>
                        Unit Cost
                    </th>
                    <th>
                        Division
                    </th>
                    <th>
                        Unit
                    </th>
                    <th>
                        Quantity
                    </th>
                    <th>
                        From Date
                    </th>
                    <th>
                        To Date
                    </th>

                </tr>
            </thead>
            <tbody>
                <?php


                if (!empty($items)) {
                    foreach ($items as $val) {
                        echo "<tr>
                                <td style='display:none'><input class='item_id' value='{$val['id']}' name='item_id[$entry_row]'/></td>
                               <td style='display:none'><input class='po_aoq_item_id' value='{$val['po_aoq_item_id']}' name='purchase_order_id[$entry_row]'/></td>
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
                                    <span class='activity' >{$val['unit_cost']}</span>
                                </td>
                                <td>
                                    <span class='activity' >{$val['division']}</span>
                                </td>
                                <td>
                                    <span class='activity' >{$val['unit']}</span>
                                </td>
                                <td>
                                <input value='{$val['quantity']}'  class='quantity form-control' type='text' name='quantity[$entry_row]'/>
                                </td>
                                <td>
                                 <input   name='date_from[$entry_row]' class='date_from form-control' type='date'  value='{$val['date_from']}' required />
                                </td>
                                <td>
                                <input name='date_to[$entry_row]'  class='date_to form-control' type='date'  value='{$val['date_to']}' required/>
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
    <div class="row">
        <div class="col-sm-5"></div>
        <div class="form-group col-sm-2" style="margin-top: 1rem;">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
        <div class="col-sm-5"></div>
    </div>

    <?php ActiveForm::end(); ?>

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
                'id' => 'pjax_advances'

            ]
        ],
        'columns' => [
            [
                'label' => 'Action',
                'format' => 'raw',
                'value' => function ($model) {
                    return Button::widget(['label' => '+', 'options' => ['class' => 'add btn-xs btn-primary', 'onClick' => 'add(this)']]);
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
<style>
    .center {
        text-align: center;
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
        max-width: 8rem;
    }
</style>
<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    let entry_row = <?= $entry_row ?>;

    function add(row) {
        const source = $(row).closest('tr')
        const clone = source.clone()
        clone.find('.quantity').attr('type', 'text')
        clone.find('.quantity').parent().attr('class', '')
        clone.find('.quantity').attr('name', `quantity[${entry_row}]`)

        clone.find('.date_from').parent().attr('class', '')
        clone.find('.date_from').prop('required', true)
        clone.find('.date_from').attr('name', `date_from[${entry_row}]`)


        clone.find('.date_to').parent().attr('class', '')
        clone.find('.date_to').prop('required', true)
        clone.find('.date_to').attr('name', `date_to[${entry_row}]`)




        clone.find('.add').parent().remove()
        clone.find('.po_id').attr('name', `purchase_order_id[${entry_row}]`)
        clone.append(` <td style='float:left;'>
                            <a class='add_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
                            <a class='remove btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
                        </td>`)
        $('#entry_table tbody').append(clone)
        entry_row++
    }
    $(document).ready(function() {
        // $('.add').on('click', function() {
        //     const source = $(this).closest('tr')
        //     const clone = source.clone()
        //     clone.find('.quantity').attr('type', 'text')
        //     clone.find('.quantity').parent().attr('class', '')
        //     clone.find('.quantity').attr('name', `quantity[${entry_row}]`)

        //     clone.find('.date_from').parent().attr('class', '')
        //     clone.find('.date_from').prop('required', true)
        //     clone.find('.date_from').attr('name', `date_from[${entry_row}]`)


        //     clone.find('.date_to').parent().attr('class', '')
        //     clone.find('.date_to').prop('required', true)
        //     clone.find('.date_to').attr('name', `date_to[${entry_row}]`)




        //     clone.find('.add').parent().remove()
        //     clone.find('.po_id').attr('name', `purchase_order_id[${entry_row}]`)
        //     clone.append(` <td style='float:left;'>
        //                     <a class='add_row btn btn-primary btn-xs' type='button'><i class='fa fa-plus fa-fw'></i> </a>
        //                     <a class='remove btn btn-danger btn-xs ' type='button' title='Delete Row'><i class='fa fa-times fa-fw'></i> </a>
        //                 </td>`)
        //     $('#entry_table tbody').append(clone)
        //     entry_row++

        // })
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

            clone.find('.po_date').text('')
            clone.find('.item_id').remove()
            clone.find('.purchase-order').attr('name', `purchase_order_id[${entry_row}]`)
            $('#entry_table tbody').append(clone)
            rfiPurchaseOrderSelect()
            entry_row++
        });
    })
</script>
<?php
SweetAlertAsset::register($this);

$script = <<< JS

    $('#RequestForInspection').on('beforeSubmit',function(e){
        e.preventDefault()
        var \$form = $(this);
        if (\$form.find('.has-error').length) 
            {
                return false;
            }
        $.ajax({
            type:'POST',
            url:\$form.attr("action"),
            data: \$form.serialize(),
            success:function(data){
                const res  =JSON.parse(data)
                 if (!res.isSuccess){
                    swal( {
                        icon: 'error',
                        title: res.error_message,
                        type: "error",
                        timer:3000,
                        closeOnConfirm: false,
                        closeOnCancel: false
                    })
                }
            },

        })
        return false;
    })       

JS;
$this->registerJs($script);
?>
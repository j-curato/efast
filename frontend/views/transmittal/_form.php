<?php

use app\components\helpers\MyHelper;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;
use aryelds\sweetalert\SweetAlertAsset;
use app\models\VwTransmittalFormDvsSearch;


/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */

$rowNum = 0;
?>

<div class="transmittal-form">
    <?php
    $searchModel = new VwTransmittalFormDvsSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $dataProvider->pagination = ['pageSize' => 10];
    $gridColumns = [

        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::button('<id class="fa fa-plus"></id>', ['class' => 'add_btn btn-xs btn-primary', 'onclick' => 'add(this)']);
            },
        ],
        [
            'label' => 'id',
            'format' => 'raw',
            'value' => function ($model) {

                return Html::input('text', '', $model->id, ['class' => 'dv_id']);
            },
            'hidden' => true

        ],
        'reporting_period',
        [
            'label' => 'DV No.',
            'attribute' => 'dv_number',
        ],
        [
            'label' => 'Check No',
            'attribute' => 'check_or_ada_no'

        ],
        [
            'label' => 'ADA No.',
            'attribute' => 'ada_number'

        ],
        [
            'attribute' => 'payee'

        ],
        [
            'attribute' => 'particular'

        ],
        [
            'format' => ['decimal', 2],
            'attribute' => 'amtDisbursed'

        ],
        [
            'format' => ['decimal', 2],
            'attribute' => 'taxWitheld'

        ],

    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            // 'heading' => 'List of Areas',
        ],
        'pjax' => true,
        'export' => false,
        'floatHeaderOptions' => [
            'top' => 50,
            'position' => 'absolute',

        ],
        'toggleDataContainer' => ['class' => 'btn-group mr-2'],
        'columns' => $gridColumns,
    ]); ?>
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-2">
            <?= $form->field($model, 'date')->widget(DatePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',

                ]
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_officer_in_charge')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_officer_in_charge, 'all'), 'employee_id', 'employee_name'),
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
        <div class="col-sm-3">
            <?= $form->field($model, 'fk_approved_by')->widget(Select2::class, [
                'data' => ArrayHelper::map(MyHelper::getEmployee($model->fk_approved_by, 'all'), 'employee_id', 'employee_name'),
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

            ])  ?>
        </div>

    </div>
    <table class="table table-striped" id="transaction_table" style="background-color: white;">
        <thead>
            <th>Reporting Period</th>
            <th>DV Number</th>
            <th>Check Number</th>
            <th>ADA Number</th>
            <th>Payee</th>
            <th>Particular</th>
            <th>Amount Disbursed</th>
            <th>Tax Witheld</th>
        </thead>
        <tbody>
            <?php

            foreach ($model->getItems() as $itm) {

                echo "<tr>
                    <td style='display:none'>
                        <input type='text' name='items[$rowNum][item_id]' value='{$itm['item_id']}'>
                        <input type='text' name='items[$rowNum][dv_id]' value='{$itm['dv_id']}'>
                    </td>
                    <td>{$itm['reporting_period']}</td>
                    <td>{$itm['dv_number']}</td>
                    <td>{$itm['check_or_ada_no']}</td>
                    <td>{$itm['ada_number']}</td>
                    <td>{$itm['payee']}</td>
                    <td>{$itm['particular']}</td>
                    <td>" . number_format($itm['amtDisbursed'], 2) . "</td>
                    <td>" . number_format($itm['taxWitheld'], 2) . "</td>
                    <td><button id='remove' class='btn-xs btn-danger ' onclick='remove(this)'><i class='fa fa-times'></i></button></td>
                </tr>";
                $rowNum++;
            } ?>
        </tbody>

    </table>


    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<script>
    function remove(i) {
        i.closest("tr").remove()
    }

    function add(ths) {
        let source = $(ths).closest('tr')
        let clone = source.clone(true)
        clone.find('.add_btn').closest('td').remove()
        clone.find('.dv_id').prop('name', 'items[][dv_id]');
        let row = `<td><button id='remove' class='btn btn-danger ' onclick='remove(this)'><i class="fa fa-times"></i></button></td>`
        clone.append(row)

        $('#transaction_table tbody').append(clone);

    }
    var payee = undefined;
    var particular = undefined;
    var dv_number = undefined;
    var check_number = undefined;
    var amount = undefined;

    function addDvToTable(result) {
        if ($("#transaction").val() == 'Single') {
            $('#particular').val(result[0]['transaction_particular'])
            $('#payee').val(result[0]['transaction_payee_id']).trigger('change')
            // console.log(result[0]['particulars'])
        }
        for (var i = 0; i < result.length; i++) {
            payee = result[i]['payee']
            particular = result[i]['particular']
            dv_number = result[i]['dv_number']
            check_number = result[i]['check_or_ada_no']
            amount = result[i]['total_disbursed']
            if ($('#transaction').val() == 'Single' && i == 1) {
                break;
            }
            $('#book_id').val(result[0]['book_id'])

            var row = `<tr>
                        <td style='display:none'><input  value='${result[i]['id']}' type='text' name='cash_disbursement_id[]'/></td>
                        <td>${dv_number}</td>
                        <td>${check_number}</td>
                        <td>${payee}</td>
                        <td>${particular}</td>
                        <td>${amount}</td>
                        <td><button  class='btn-xs btn-danger ' onclick='remove(this)'><i class="fa fa-times"></i></button></td></tr>
                    </tr>
                        `
            $('#transaction_table tbody').append(row);


        }



    }
    $(document).ready(function() {
        $("#add").on('click', function(e) {
            e.preventDefault()

            var checkedValue = null;

            $(".checkbox:checked").each(function() {
                checkedValue = $(this).closest('tr');
                checkedValue.closest('.checkbox').removeAttr('checked')
                var row = `<td><button id='remove' class='btn btn-danger ' onclick='remove(this)'><i class="fa fa-times"></i></button></td>`
                var clone = checkedValue.clone();
                clone.children('td').eq(0).find('.checkbox').prop('type', 'text');
                clone.children('td').eq(0).find('.checkbox').prop('name', 'items[][dv_id]');
                clone.children('td').eq(0).prop('style', 'display:none');
                clone.append(row)

                $('#transaction_table tbody').append(clone);


            });
            $('.checkbox').prop('checked', false)

        })
    })
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS
        function enableDisable(checkbox) {
            var isDisable = true
            if (checkbox.checked) {
                isDisable = false
            }
            enableInput(isDisable, checkbox.value)

        }



    $("#save_data").submit(function(e){
        e.preventDefault();
        
        $.ajax({
            type:'POST',
            url:window.location.pathname + "?r=transmittal/insert-transmittal",
            data:$("#save_data").serialize(),
            success:function(data){
                var res= JSON.parse(data)
                if (res.isSuccess){

                    swal({
                        title: "Success",
                        // text: res.error,
                        type: "success",
                        timer: 3000,
                        button: false
                                // confirmButtonText: "Yes, delete it!",
                    },
                    function(){
                        window.location.href  = window.location.pathname + "?r=transmittal/view&id="+res.id
                    }
                    
                    );
                }

            }
        })
    })

JS;
$this->registerJs($script);
?>
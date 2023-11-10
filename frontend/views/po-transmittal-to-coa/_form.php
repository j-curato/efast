<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

use yii\bootstrap4\ActiveForm;
use app\components\helpers\MyHelper;
use aryelds\sweetalert\SweetAlertAsset;
use app\models\VwNotInCoaTransmittalSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */
/* @var $form yii\widgets\ActiveForm */

$itemRow = 0;
?>

<div class="transmittal-form card" style="padding: 1rem;">
    <?php
    $viewSearchModel = new VwNotInCoaTransmittalSearch();
    $viewDataProvider = $viewSearchModel->search(Yii::$app->request->queryParams);
    $viewDataProvider->pagination = ['pageSize' => 10];
    $viewColumn = [

        [
            'label' => 'Action',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::button('<id class="fa fa-plus"></id>', ['class' => 'add_btn btn-xs btn-primary', 'onclick' => 'addItem(this)']);
            },
        ],

        [
            'label' => 'id',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::input('text', '', $model->id, ['class' => 'transmittal_id']);
            },
            'hidden' => true

        ],
        'transmittal_number',


    ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $viewDataProvider,
        'filterModel' => $viewSearchModel,
        'columns' => $viewColumn,
        'pjax' => true,
        'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading' => 'List of Transmittals',
        ],
        'export' => false

    ]); ?>

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
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
            <th>Transmittal Number</th>
        </thead>
        <tbody>
            <?php

            foreach ($items as $itm) {
                echo "<tr>
                    <td style='display:none;'>
                        <input type='text' name='items[$itemRow][item_id]' value='{$itm['item_id']}'>
                        <input type='text' name='items[$itemRow][transmittal_id]' value='{$itm['transmittal_id']}'>
                    </td>
                    <td>{$itm['transmittal_number']}</td>
                    <td><button id='remove' class='btn-xs btn-danger ' onclick='remove(this)'><i class='fa fa-times'></i></button></td>
                </tr>";
                $itemRow++;
            }
            ?>
        </tbody>
    </table>

    <div class="row justify-content-center">

        <div class="form-group col-sm-2">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<style>
    .transmittal-form {
        padding: 2rem;
    }
</style>
<script>
    function remove(i) {
        i.closest("tr").remove()
    }

    function addItem(ths) {
        let source = $(ths).closest('tr')
        let clone = source.clone(true)
        clone.find('.add_btn').closest('td').remove()
        clone.find('.transmittal_id').prop('name', 'items[][transmittal_id]');
        let row = `<td><button id='remove' class='btn-xs btn-danger ' onclick='remove(this)'><i class="fa fa-times"></i></button></td>`
        clone.append(row)
        $('#transaction_table tbody').append(clone);
    }
</script>


<?php
SweetAlertAsset::register($this);
$script = <<< JS
   

   $(document).ready(()=>{
        $("#PoTransmittalToCoa").on("beforeSubmit", function (event) {
            event.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: form.serialize(),
                success: function (data) {
                    let res = JSON.parse(data)
                    console.log(res)
                    swal({
                        icon: 'error',
                        title: res.error,
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
$this->registerJs($script);
?>
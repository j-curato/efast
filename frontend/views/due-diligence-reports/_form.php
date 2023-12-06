<?php

use app\models\Office;
use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\DueDiligenceReports */
/* @var $form yii\widgets\ActiveForm */

$notedBy = [];
if (!empty($model->fk_noted_by)) {
    $notedBy[] = $model->notedBy->getEmployeeDetails();
}
$conductedBy = [];
if (!empty($model->fk_conducted_by)) {
    $conductedBy[] =  $model->conductedBy->getEmployeeDetails();
}
$payee = [];
if (!empty($model->fk_payee_id)) {
    $payee[] =  $model->payee->getPayeeDetailsA();
}
$mgrfr = [];
if (!empty($model->fk_mgrfr_id)) {
    $mgrfr[] = [
        'id' => $model->fk_mgrfr_id,
        'serial_number' => $model->mgrfr->serial_number
    ];
}
$items  = $model->getItemsA();
?>
<div class="due-diligence-reports-form   p-2" id="main">
    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="container p-3">
        <div class="card p-2 m-1">

            <div class="row ">
                <?php if (YIi::$app->user->can('ro_rapid_fma')) : ?>
                    <div class="col-4">
                        <?= $form->field($model, 'fk_office_id')->widget(Select2::class, [
                            'data' => ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                            'options' => [
                                'placeholder' => 'Select Office'
                            ],
                            'pluginOptions' => []
                        ]) ?>
                    </div>
                <?php endif; ?>
                <div class="col-sm-4">
                    <?= $form->field($model, 'fk_mgrfr_id')->widget(Select2::class, [
                        'data' => ArrayHelper::map($mgrfr, 'id', 'serial_number'),
                        'options' => ['placeholder' => 'Search for a MG RFR Serial No. ...'],
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 1,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                            ],
                            'ajax' => [
                                'url' => Yii::$app->request->baseUrl . '?r=mgrfrs/search-mgrfr',
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
                <div class="col-sm-4">
                    <?= $form->field($model, 'supplier_name')->textInput() ?>

                </div>

                <div class="col-sm-4"> <?= $form->field($model, 'supplier_contact_number')->textInput() ?></div>
                <div class="col-sm-4"> <?= $form->field($model, 'supplier_contact_person')->textInput() ?></div>
                <div class="col-sm-4"> <?= $form->field($model, 'supplier_address')->textarea(['rows'=>1]) ?></div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'fk_conducted_by')->widget(Select2::class, [
                        'data' => ArrayHelper::map($conductedBy, 'employee_id', 'fullName'),
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
                <div class="col-sm-6">
                    <?= $form->field($model, 'fk_noted_by')->widget(Select2::class, [
                        'data' => ArrayHelper::map($notedBy, 'employee_id', 'fullName'),
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
        </div>



        <div class="card p-3 m-1">
            <?= $form->field($model, 'supplier_is_registered')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ],
            ) ?>
        </div>

        <div class="card p-3 m-1">
            <?= $form->field($model, 'supplier_has_business_permit')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ],
            ) ?>
        </div>

        <div class="card p-3 m-1">
            <?= $form->field($model, 'supplier_is_bir_registered')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
        </div>


        <div class="card p-3 m-1" style="margin: 3px; ">
            <?= $form->field($model, 'supplier_has_officer_connection')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
        </div>
        <div class="card p-3 m-1">
            <?= $form->field($model, 'supplier_is_financial_capable')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
        </div>

        <div class="card p-3 m-1" style="margin: 3px; ">
            <?= $form->field($model, 'supplier_is_authorized_dealer')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
        </div>
        <div class="card p-3 m-1">
            <?= $form->field($model, 'supplier_has_quality_material')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
            <?= $form->field($model, 'supplier_nursery')->textarea(['rows' => 2]) ?>

        </div>

        <div class="card p-3 " style="margin: 3px; ">
            <?= $form->field($model, 'supplier_can_comply_specs')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
        </div>
        <div class="card p-3 m-1">
            <?= $form->field($model, 'supplier_has_legal_issues')->radioList(
                [
                    '0' => 'No',
                    '1' => 'Yes',
                ]
            ) ?>
        </div>
        <div class="card p-3 m-1">
            <?= $form->field($model, 'comments')->textarea(['rows' => 3]) ?>
        </div>
        <div class="card p-3 m-1">

            <table class="">
                <tr>
                    <th>Clients/Customers</th>
                    <td style="max-width: 1em;" class="text-center">
                        <button type="button" class="btn-xs btn-success" @click='addItem()'><i class="fa fa-plus"></i> Add</button>
                    </td>
                </tr>
                <tbody>
                    <tr v-for="(item,idx) in items" :key='idx'>
                        <td>
                            <input type="hidden" :name="'items['+idx+'][id]'" class="form-control" v-model="item.id" v-if="item.id">
                            <input type="text" :name="'items['+idx+'][customer_name]'" class="form-control" v-model="item.customer_name">
                        </td>
                        <td class="text-center">
                            <button type="button" @click='removeRow(idx)' class=" btn-xs btn-danger"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card pt-2 m-1">
            <div class="row justify-content-center ">
                <div class="form-group col-sm-2   ">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<script>
    $(document).ready(function() {
        new Vue({
            el: '#main',
            data: {
                items: JSON.parse('<?= !empty($items) ? json_encode($items) : json_encode([]) ?>')
            },
            mounted() {
                console.log(this.items)
            },
            methods: {
                addItem() {
                    this.items.push({
                        customer_name: null,
                    })
                },
                removeRow(index) {
                    this.items.splice(index, 1)
                }
            }
        })
    })
</script>
<style>
    td,
    th {
        padding: .2em;
    }

    .flex-container {
        display: flex;
        width: 100%;
        /* Optional: Add a border for visualization */
    }

    .flex-container>div {
        margin: 5px;
        width: 100%;
    }
</style>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#DueDiligenceReports").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
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
JS;
$this->registerJs($js);
?>
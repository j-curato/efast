<?php

use app\models\Books;
use app\models\DvAucs;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */
/* @var $form yii\widgets\ActiveForm */

$dv = [];
if (!empty($model->dv_aucs_id)) {



    $dv = ArrayHelper::map(
        Yii::$app->db->createCommand("SELECT dv_aucs.id,dv_aucs.dv_number FROM dv_aucs WHERE dv_aucs.id = :id")
            ->bindValue(':id', $model->dv_aucs_id)
            ->queryAll(),
        'id',
        'dv_number'
    );
}

?>

<div class="cash-disbursement-form ">

    <div class="panel panel-default container" style="padding:2rem;height:100%">

        <?php $form = ActiveForm::begin([
            'id' => 'cash_disbursement_form',
        ]); ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'readonly' => true,
                    'options' => [
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'minViewMode' => 'months',
                        'autoclose' => true
                    ]
                ]) ?>

            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'issuance_date')->widget(DatePicker::class, [
                    'readonly' => true,
                    'options' => [
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'autoclose' => true
                    ]
                ]) ?>
            </div>
            <!-- <div class="col-sm-4">
                <?= $form->field($model, 'is_cancelled')->widget(Select2::class, [
                    'readonly' => true,
                    'data' => [0 => "Good", 1 => "Cancelled"],
                    'options' => [
                        'style' => 'background-color:white'
                    ],
                    'pluginOptions' => [
                        'placeholder' => ''
                    ]
                ]) ?>
            </div> -->





        </div>
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'book_id')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'),
                    'pluginOptions' => [
                        'placeholder' => 'Select Book'
                    ]
                ]) ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'mode_of_payment')->widget(Select2::class, [
                    'data' => ['lbp check' => "LBP Check", 'ada' => "ADA", 'echeck' => "eCheck"],
                    'pluginOptions' => [
                        'placeholder' => 'Select Mode of Payment'
                    ]
                ]) ?>

            </div>

            <div class="col-sm-3">
                <?= $form->field($model, 'begin_time')->widget(TimePicker::class, []) ?>

            </div>
            <div class="col-sm-3">
                <?= $form->field($model, 'out_time')->widget(TimePicker::class, []) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'dv_aucs_id')->widget(Select2::class, [
                    'data' => $dv,
                    'options' => [
                        'placeholder' => 'Search for a DV ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                        ],
                        'ajax' => [
                            'url' => Yii::$app->request->baseUrl . '?r=dv-aucs/search-dv',
                            'dataType' => 'json',
                            'delay' => 250,
                            'data' => new JsExpression('function(params) { 
                                return {
                                    q:params.term,page: params.page||1,
                                    
                                }; }'),
                            'cache' => true
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                        'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                    ],

                ]) ?>

            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'check_or_ada_no')->textInput(['maxlength' => true]) ?>

            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'ada_number')->textInput(['maxlength' => true]) ?>

            </div>

        </div>

        <table class="table">
            <thead>
                <tr>
                    <th class="disbursed" colspan="3">
                        <span class="danger">
                            DV Already Disbursed:
                        </span>
                        <a href="" class="cash_link btn btn-link">Link</a>
                    </th>
                </tr>
                <th>DV Number</th>
                <th>Payee</th>
                <th>Particular</th>
                <th>Amount</th>
            </thead>
            <tbody>
                <td class="dv_number"> <?= !empty($dv_details['dv_number']) ? $dv_details['dv_number'] : '' ?></td>
                <td class="payee"><?= !empty($dv_details['payee']) ? $dv_details['payee'] : '' ?></td>
                <td class="particular"><?= !empty($dv_details['particular']) ? $dv_details['particular'] : '' ?></td>
                <td class="amount"><?= !empty($dv_details['ttlDisburse']) ? number_format($dv_details['ttlDisburse'], 2) : '' ?></td>
            </tbody>
        </table>

        <div class="row" style="margin-top: 5rem;">
            <div class="col-sm-3 col-sm-offset-4">
                <div class="form-group " style="width:10rem">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success submit_cash', 'style' => 'width:30rem']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
<style>
    .disbursed {
        display: none;
    }

    .danger {
        color: red;
    }
</style>
<?php

$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<script>
    $(document).ready(() => {


        $('#cashdisbursement-dv_aucs_id').on('change', (e) => {
            $(".dv_number").text('')
            $(".payee").text('')
            $(".particular").text('')
            $(".amount").text((''))
            e.preventDefault()
            $.ajax({
                type: 'POST',
                url: window.location.pathname + '?r=cash-disbursement/dv-details',
                data: {
                    id: $('#cashdisbursement-dv_aucs_id').val()
                },
                success: function(data) {
                    const res = JSON.parse(data)
                    $(".dv_number").text(res.dv_number)
                    $(".payee").text(res.payee)
                    $(".particular").text(res.particular)
                    $(".amount").text(thousands_separators(parseFloat(res.ttlDisburse)))
                    if (res.cash_id) {

                        $(".cash_link").attr('href', window.location.pathname + '?r=cash-disbursement/view&id=' + res.cash_id)
                        $('.disbursed').show()
                        $('.submit_cash').attr('disabled', true)
                    } else {
                        $('.disbursed').hide()
                        $('.submit_cash').attr('disabled', false)
                    }

                }

            })
        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS
$("#cash_disbursement_form").on("beforeSubmit", function (event) {
    event.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: form.serialize(),
        success: function (data) {
            let res = JSON.parse(data)
            if (res.error){

                swal({
                    icon: 'error',
                    title: res.error_message,
                    type: "error",
                    timer: 3000,
                    closeOnConfirm: false,
                    closeOnCancel: false
                })
            }

        },
        error: function (data) {
        }
    });
    return false;
});
JS;
$this->registerJs($js);
?>
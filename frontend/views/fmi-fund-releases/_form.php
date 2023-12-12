<?php

use app\models\FmiTranches;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\FmiFundReleases */
/* @var $form yii\widgets\ActiveForm */

$subprojectData = [
    [
        'id' => $model->fk_fmi_subproject_id ?? null,
        'serial_number' => $model->fmiSubproject->serial_number ?? null
    ]
];
$cashDisbursement = [
    [
        'id' => $model->fk_cash_disbursement_id ?? null,
        'check_number' => $model->cashDisbursement->check_or_ada_no ?? null
    ]
];
?>

<div class="fmi-fund-releases-form" id="mainVue">

    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>

    <div class="row">

        <div class="col-4">
            <?= $form->field($model, 'fk_fmi_subproject_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($subprojectData, 'id', 'serial_number'),
                'options' => ['placeholder' => 'Search for a Subproject ...', 'style' => 'height:30em'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['fmi-subprojects/search-subproject']),
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {text:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>

        <div class="col-4">
            <?= $form->field($model, 'fk_cash_disbursement_id')->widget(Select2::class, [
                'data' => ArrayHelper::map($cashDisbursement, 'id', 'check_number'),
                'options' => ['placeholder' => 'Search for a Check No. ...', 'style' => 'height:30em'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['cash-disbursement/search-check-number']),
                        'dataType' => 'json',
                        'delay' => 250,
                        'data' => new JsExpression('function(params) { return {text:params.term,page:params.page}; }'),
                        'cache' => true
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                    'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                ],

            ]) ?>
        </div>
        <div class="col-4">
            <?= $form->field($model, 'fk_tranche_id')->dropDownList(
                ArrayHelper::map(FmiTranches::getAllTranches(), 'id', 'tranche_number'),
                [
                    'prompt' => "Select Tranche"
                ]
            ) ?>
        </div>
    </div>

    <table class="table ">
        <tr class="table-info">
            <th class="text-center" colspan="4"> Project Details</th>
        </tr>
        <tr>
            <td><b>Province: </b>{{subprojectDetails.province_name}}</td>
            <td><b>City/Municipality: </b>{{subprojectDetails.municipality_name}}</td>
            <td><b>Barangay: </b>{{subprojectDetails.barangay_name}}</td>
            <td><b>Purok: </b>{{subprojectDetails.purok}}</td>
        </tr>
        <tr>

            <td><b>Batch: </b>{{subprojectDetails.batch_name}}</td>
            <td><b>Equity: </b>{{formatAmount(subprojectDetails.equity_amount)}}</td>
            <td><b>Grant: </b>{{formatAmount(subprojectDetails.grant_amount)}}</td>
        </tr>
    </table>
    <table class="table">
        <tr class="table-info">
            <th colspan="4" class="text-center">Check Details</th>
        </tr>
        <tr>
            <td><b>Check Number: </b>{{cashDetails.cashDisbursementDetails.check_number}}</td>
            <td><b>Check Date: </b>{{cashDetails.cashDisbursementDetails.issuance_date}}</td>
            <td><b>Book: </b>{{cashDetails.cashDisbursementDetails.book_name}}</td>
        </tr>
        <tr>
            <th>DV No.</th>
            <th>Payee</th>
            <th>Particular</th>
            <th>Amount Disbursed</th>
        </tr>
        <tr v-for="item in cashDetails.cashDisbursementItems ">
            <td>{{item.dv_number}}</td>
            <td>{{item.payee_name}}</td>
            <td>{{item.particular}}</td>
            <td>{{formatAmount(item.total_disbursed)}}</td>
        </tr>
    </table>
    <div class="row justify-content-center">
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

</div>

<script>
    new Vue({
        el: '#mainVue',
        data: {
            cashDetails: {
                cashDisbursementDetails: [],
                cashDisbursementItems: []
            },
            subprojectDetails: []
        },

        mounted() {
            $('#fmifundreleases-fk_cash_disbursement_id').on('change',
                this.getCheckDetails
            )
            $('#fmifundreleases-fk_fmi_subproject_id').on('change',
                this.getSubprojectDetails
            )
        },
        methods: {
            formatAmount(amount) {

                amount = parseFloat(amount)
                if (typeof amount === 'number' && !isNaN(amount)) {
                    return amount.toLocaleString()
                }
                return 0;
            },
            getCheckDetails() {
                const url = "?r=cash-disbursement/get-details";
                const data = {
                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
                    id: $('#fmifundreleases-fk_cash_disbursement_id').val()
                }
                axios.post(url, data)
                    .then(res => {
                        this.cashDetails = res.data
                    })
                    .catch(err => {
                        console.log(err)
                    })
            },
            getSubprojectDetails() {
                const url = "?r=fmi-subprojects/get-details";
                const data = {
                    _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
                    id: $('#fmifundreleases-fk_fmi_subproject_id').val()
                }
                axios.post(url, data)
                    .then(res => {
                        this.subprojectDetails = res.data
                    })
                    .catch(err => {
                        console.log(err)
                    })
            }
        }


    })
</script>
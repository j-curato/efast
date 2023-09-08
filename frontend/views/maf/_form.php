<?php

use app\models\AllotmentType;
use app\models\Books;
use app\models\ChartOfAccounts;
use app\models\Divisions;
use app\models\DocumentRecieve;
use app\models\FundSource;
use app\models\MajorAccounts;
use app\models\MfoPapCode;
use app\models\Office;
use app\models\Payee;
use aryelds\sweetalert\SweetAlertAsset;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AllotmentModificationAdvice */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="allotment-modification-advice-form" id="main">
    <?php $form = ActiveForm::begin([
        'id' => $model->formName()
    ]); ?>
    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="form-group col-sm-2">
                    <?= $form->field($model, 'date_issued')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd',
                            'autoclose' => true,
                        ]
                    ]) ?>
                </div>
                <div class="form-group col-sm-2">
                    <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                        'pluginOptions' => [
                            'format' => 'yyyy-mm',
                            'minViewMode' => 'months',
                            'autoclose' => true
                        ],
                        'options' => [
                            'v-model' => 'reportingPeriod'
                        ]
                    ]) ?>
                </div>
                <div class="col-sm-2 form-group">
                    <?= $form->field($model, 'book_id')->dropDownList(ArrayHelper::map(Books::find()->asArray()->all(), 'id', 'name'), [
                        'prompt' => 'Select Book',
                        'v-model' => 'bookSelect'
                    ]) ?>
                </div>
                <div class="col-sm-3 form-group">
                    <?= $form->field($model, 'allotment_type_id')->dropDownList(ArrayHelper::map(AllotmentType::find()->asArray()->all(), 'id', 'type'), [
                        'prompt' => 'Select Allotment Type ',
                        'v-model' => 'allotmentTypeSelect'
                    ]) ?>
                </div>
                <div class="col-sm-3 form-group">
                    <?= $form->field($model, 'mfo_pap_code_id')->dropDownList(ArrayHelper::map(MfoPapCode::find()->asArray()->all(), 'id', 'name'), [
                        'prompt' => 'Select MFO/PAP',
                        'v-model' => 'mfoPapSelect'
                    ]) ?>
                </div>
            </div>
            <div class="row">

                <div class="col-sm-3 form-group">
                    <?= $form->field($model, 'document_recieve_id')->dropDownList(ArrayHelper::map(DocumentRecieve::find()->asArray()->all(), 'id', 'name'), [
                        'prompt' => 'Select Document Receive ',
                        'v-model' => 'documentReceiveSelect'
                    ]) ?>
                </div>
                <div class="col-sm-3 form-group">

                    <?= $form->field($model, 'fund_source_id')->dropDownList(ArrayHelper::map(FundSource::find()->asArray()->all(), 'id', 'name'), [
                        'prompt' => 'Select Fund Source',
                        'v-model' => 'fundSourceSelect'
                    ]) ?>
                </div>
                <div class="col-sm-3 form-group">

                    <?= empty($model->id) ? $form->field($model, 'fk_major_account_id')->dropDownList(ArrayHelper::map(MajorAccounts::find()->asArray()->all(), 'id', 'name'), [
                        'prompt' => 'Major Account',
                        'v-model' => 'majorAccountSelect',
                        'class' => 'major-account form-control'
                    ]) : '' ?>
                </div>
            </div>

            <div class="form-group">
                <?= $form->field($model, 'particulars')->textarea(['rows' => 4]) ?>
            </div>


            <table class="table">
                <thead>
                    <tr class="success">
                        <th colspan="7" class="ctr">Positive Allotments</th>
                    </tr>
                    <th>Office</th>
                    <th>Division</th>
                    <th>UACS</th>
                    <th>Amount</th>
                    <td style="max-width: 40px;">
                        <button type="button" @click="addMafItem()" class="btn btn-success btn-block btn-xs" style="max-width: 25px;"> <i class=" fa fa-plus"></i></button>
                    </td>
                </thead>
                <tbody>
                    <tr v-for="(mafItm,mafIndex) in mafItems" :key="mafIndex">
                        <td style="display:none">
                            <input type="text" v-if="mafItm.id" :name="'mafItems[' + mafIndex + '][id]'" :value="mafItm.id">
                        </td>
                        <td style="width:200px">
                            <select :name="'mafItems[' + mafIndex + '][fk_office_id]'" class="form-control" v-model='mafItm.fk_office_id'>
                                <option disabled selected>Select Office</option>
                                <option v-for="office in offices" :value="office.id">{{ office.office_name }}</option>
                            </select>
                        </td>
                        <td style="width:200px">
                            <select :name="'mafItems[' + mafIndex + '][fk_division_id]'" class="form-control" v-model="mafItm.fk_division_id">
                                <option disabled selected>Select Division</option>
                                <option v-for="div in divisions" :value="div.id">{{ div.division | uppercase }}</option>
                            </select>
                        </td>
                        <td>
                            <select :name="'mafItems[' + mafIndex + '][chart_of_account_id]'" class="form-control chart-of-accounts" style="width: 100%;">
                                <option disabled selected v-if="!mafItm.chart_of_account_id">Select Chart of Account</option>
                                <option selected v-if="mafItm.chart_of_account_id" :value='mafItm.chart_of_account_id'>{{mafItm.chartOfAcc}}</option>
                            </select>
                        </td>
                        <td style="width: 300px;">
                            <input type="text" class="amt mask-money form-control" v-money="moneyConfig" v-model='mafItm.maskedAmount' @keyup="changeMainAmount($event,mafItm,mafIndex)" />
                            <input type="hidden" :name="'mafItems['+mafIndex+'][amount]'" class="main-amount" :value="mafItm.amount">
                        </td>

                        <td style="max-width: 40px;">
                            <div class="button-container">
                                <a type="button" @click="addMafItem(mafIndex)" class="btn btn-success btn-xs" style="width: 25px;"><i class="fa fa-plus"></i></a>
                                <a type="button" @click="removePositiveAllotmentItem(mafIndex)" class="btn btn-danger btn-xs" style="width: 25px;"><i class="fa fa-times"></i></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total</th>
                        <th class="amt"> ₱ {{formatAmount(mafItemsTotal)}}</th>
                    </tr>
                </tfoot>
            </table>
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr class="danger">
                        <th colspan="7" class="ctr">Source Allotments</th>
                    </tr>
                    <th>Office</th>
                    <th>Division</th>
                    <th>UACS</th>
                    <th>Balance After Obligation</th>
                    <th>Amount</th>
                </thead>
                <tbody>

                    <tr v-for="(adjItm,adjIdx) in allotmentAdjustmentItems">
                        <td style="display: none;">
                            <input type="hidden" :name="'adjustmentItems[' + adjIdx + '][fk_record_allotment_entry_id]'" :value="adjItm.allotment_entry_id">
                            <input type="text" v-if="adjItm.id" :name="'adjustmentItems[' + adjIdx + '][id]'" :value="adjItm.id">
                        </td>
                        <td>{{adjItm.office_name}}</td>
                        <td>{{adjItm.division}}</td>
                        <td>{{adjItm.uacs}} - {{adjItm.account_title}}</td>
                        <td>{{formatAmount(adjItm.balAfterObligation)}}</td>
                        <td>
                            <!-- <input type="text" class="amt negative-mask-money form-control" @keyup="changeMainAmount($event,adjItm,adjIdx)"> -->
                            <input type="text" class="amt mask-money form-control" v-money="moneyConfig" v-model='adjItm.maskedAmount' @keyup="changeMainAmount($event,adjItm,adjIdx)" />
                            <input type="hidden" :name="'adjustmentItems['+adjIdx+'][amount]'" class="main-amount" :value="adjItm.amount">
                        </td>
                        <td>
                            <button type="button" @click="removeNegativeAllotmentItems(adjIdx)" class="btn btn-danger btn-block btn-xs" style="max-width: 25px;"> <i class=" fa fa-times"></i></button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total</th>
                        <th class="amt">₱ {{formatAmount(allotmentAdjustmentItemsTotal)}}</th>
                    </tr>
                </tfoot>
            </table>

        </div>
        <div class="row">
            <div class="form-group col-sm-1 col-sm-offset-5 ">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width:100%;']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
    .amt {
        text-align: right;
    }

    .ctr {
        text-align: center;
    }

    .button-container {
        display: flex;
        /* Use flexbox to place buttons in a row */
        align-items: center;
        /* Vertically center the buttons */
        gap: 4px;
        /* Add some spacing between the buttons */
    }
</style>

<?php
$csrfToken = Yii::$app->request->csrfToken;
$this->registerJsFile("@web/js/maskMoney.js", ['depends' => \yii\web\JqueryAsset::class]);
$this->registerJsFile("@web/frontend/web/js/globalFunctions.js", ['depends' => \yii\web\JqueryAsset::class]);
$this->registerJsFile("@web/js/v-money.min.js");
$this->registerCssFile("https://cdn.jsdelivr.net/npm/vue-select@latest/dist/vue-select.css");
$this->registerJsFile("https://cdn.jsdelivr.net/npm/vue-select@latest/dist/vue-select.js", ['position' => $this::POS_HEAD]);
?>

<script>
    $(document).ready(function() {
        Vue.component('v-select', VueSelect.VueSelect);
        new Vue({
            el: '#main',

            data: {
                allotmentTypeSelect: '<?= $model->allotment_type_id ?>',
                mfoPapSelect: '<?= $model->mfo_pap_code_id ?>',
                bookSelect: '<?= $model->book_id ?>',
                reportingPeriod: '<?= $model->reporting_period ?>',
                documentReceiveSelect: '<?= $model->document_recieve_id ?>',
                fundSourceSelect: '<?= $model->fund_source_id ?>',
                majorAccountSelect: '<?= $model->fk_major_account_id ?>',
                mafItems: <?= json_encode($model->getMafItems()) ?>,
                // [{
                //     fk_office_id: '',
                //     fk_division_id: '',
                //     fk_chart_of_account_id: '',
                //     amount: '',
                // }],
                allotmentAdjustmentItems: <?= json_encode($model->getAdjustmentItems()) ?>,
                moneyConfig: {
                    precision: 2, // Number of decimal places
                    prefix: '₱ ', // Currency symbol
                    thousands: ',', // Thousands separator
                    decimal: '.', // Decimal separator,
                },
                offices: <?= json_encode(Office::find()->asArray()->all()) ?>,
                divisions: <?= json_encode(Divisions::find()->asArray()->all()) ?>,
                chartOfAccounts: <?= json_encode(ChartOfAccounts::find()->asArray()->all()) ?>

            },
            watch: {

                mfoPapSelect() {
                    this.getAllotments()
                },
                bookSelect() {
                    this.getAllotments()
                },
                divisionSelect() {
                    this.getAllotments()
                },
                allotmentTypeSelect() {
                    this.getAllotments()
                },
                documentReceiveSelect() {
                    this.getAllotments()
                },
                fundSourceSelect() {
                    this.getAllotments()
                },
                majorAccountSelect() {
                    this.getAllotments()
                    $('.chart-of-accounts').val('').trigger('change')
                }
            },
            updated() {
                MafChartOfAccountSearchSelect()
            },
            mounted() {},
            computed: {
                mafItemsTotal() {
                    return this.mafItems.reduce((total, item) => total + parseFloat(item.amount), 0);
                },
                allotmentAdjustmentItemsTotal() {
                    return this.allotmentAdjustmentItems.reduce((total, item) => total + parseFloat(item.amount), 0)
                },
                selectOptions() {
                    // Convert the provided data to an array of objects
                    const offices = JSON.parse(JSON.stringify(<?= json_encode(ArrayHelper::map(Payee::find()->asArray()->all(), 'id', 'account_name')) ?>))
                    const optionArray = Object.entries(offices).map(([key, value]) => ({
                        value: key,
                        label: value,
                    }));

                    return optionArray;
                },
            },

            methods: {

                changeMainAmount(event, item, index) {
                    // item.maskMoney = event.target.value
                    item.amount = $(event.target).maskMoney('unmasked')[0]
                },
                maskMoneyAmount() {
                    $('.mask-money').maskMoney({
                        // prefix: '₱ ',
                        thousands: ',',
                        decimal: '.',
                        allowNegative: false
                    });
                    $('.negative-mask-money').maskMoney({
                        prefix: '₱ ',
                        thousands: ',',
                        decimal: '.',
                        allowNegative: true
                    });
                },
                formatAmount(unitCost) {
                    unitCost = parseFloat(unitCost)
                    if (typeof unitCost === 'number' && !isNaN(unitCost)) {
                        return unitCost.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                },
                getAllotments() {
                    this.reportingPeriod = $('#recordallotments-reporting_period').val()
                    console.log('qwe')
                    if (
                        this.documentReceiveSelect !== '' &&
                        this.fundSourceSelect !== '' &&
                        this.allotmentTypeSelect !== '' &&
                        this.mfoPapSelect !== '' &&
                        this.bookSelect !== '' &&
                        this.reportingPeriod &&
                        this.majorAccountSelect !== ''

                    ) {

                        const data = {
                            documentReceiveId: this.documentReceiveSelect,
                            fundSourceId: this.fundSourceSelect,
                            allotmentTypeId: this.allotmentTypeSelect,
                            mfoPapId: this.mfoPapSelect,
                            bookId: this.bookSelect,
                            reportingPeriod: this.reportingPeriod,
                            majorAccountId: this.majorAccountSelect,
                            _csrf: '<?= $csrfToken ?>'
                        }
                        const apiUrl = window.location.pathname + '?r=allotment-modification-advice/get-allotments';
                        axios.post(apiUrl, data)
                            .then(response => {
                                this.allotmentAdjustmentItems = JSON.parse(JSON.stringify(response.data))
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });

                    }
                },

                removePositiveAllotmentItem(index) {
                    this.mafItems.splice(index, 1)
                },
                removeNegativeAllotmentItems(index) {
                    this.allotmentAdjustmentItems.splice(index, 1)
                },
                addMafItem(index) {
                    this.mafItems.push({
                        fk_office_id: '',
                        fk_division_id: '',
                        fk_chart_of_account_id: '',
                        amount: '',
                        maskedAmount: '',
                    })
                },
            },
            filters: {
                uppercase(value) {
                    return value.toUpperCase();
                },
            }




        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$js = <<< JS

    $('#RecordAllotments').on('beforeSubmit',function(e){
        e.preventDefault()
        const form  =$(this)
        $.ajax({
            url:form.attr('action'),
            type:form.attr('method'),
            data:form.serialize(),
            success:function(data){
                swal({
                    icon:'error',
                    title:data,
                    timer: 3000,
                    closeOnConfirm: false,
                    closeOnCancel: false
                })
            }
        })
        return false;
    })
JS;
$this->registerJs($js);
?>
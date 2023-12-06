<?php

use yii\helpers\Html;
use app\models\Office;
use yii\web\JqueryAsset;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MgLiquidations */
/* @var $form yii\widgets\ActiveForm */

$mgrfr = [];
if (!empty($model->fk_mgrfr_id)) {
    $mgrfr[] = [
        'id' => $model->fk_mgrfr_id,
        'serial_number' => $model->mgrfr->serial_number
    ];
}
?>

<div class="mg-liquidations-form" id="main">

    <?php $form = ActiveForm::begin(); ?>
    <div class="card p-3">

        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, ['pluginOptions' => [
                    'format' => 'yyyy-mm',
                    'minViewMode' => 'months',
                    'autoclose' => true
                ]]) ?>
            </div>
            <?php if (Yii::$app->user->can('ro_rapid_fma')) : ?>
                <div class="col-sm-3">
                    <?= $form->field($model, 'fk_office_id')->dropDownList(
                        ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                        ['prompt' => 'Select Office'],
                    ) ?>
                </div>
            <?php endif; ?>
            <?php if ($model->isNewRecord) : ?>
                <div class="col-sm-3">
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
                                'data' => new JsExpression('function(params) { return {q:params.term,page:params.page||1}; }'),
                                'cache' => true
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(fund_source) { return fund_source.text; }'),
                            'templateSelection' => new JsExpression('function (fund_source) { return fund_source.text; }'),
                        ],
                    ]) ?>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="card p-3">
        <table class="table table-hover">
            <thead>
                <th>Date</th>
                <th>DV No.</th>
                <th>Payee</th>
                <th>Comments</th>
                <th>Grant Amount</th>
                <th>LGU Equity Amount</th>
                <th>Other Funds</th>
                <th>Total</th>
                <th></th>

            </thead>
            <tbody>
                <tr v-for="(item,index) in items" :key="index">
                    <td class="d-none">
                        <input type='text' v-model="item.id" v-if="item.id" :name="'items['+index+'][id]'">
                        <input type='text' v-model="item.notification_to_pay_id" :name="'items['+index+'][fk_notification_to_pay_id]'">
                    </td>
                    <td class=""><input type='date' v-model="item.date" :name="'items['+index+'][date]'" class="form-control"></td>
                    <td class="" style="min-width: 5em;"><input style="min-width: 15em;" type='text' v-model="item.dv_number" :name="'items['+index+'][dv_number]'" class="form-control"></td>
                    <td>{{item.payee_name}}</td>
                    <td>{{item.comments}}</td>
                    <td class="">{{formatAmount(item.matching_grant_amount)}}</td>
                    <td class="">{{formatAmount(item.equity_amount)}}</td>
                    <td class="">{{formatAmount(item.other_amount)}}</td>
                    <td class="">
                        {{formatAmount(parseFloat(item.matching_grant_amount) +
                            parseFloat(item.equity_amount) +
                            parseFloat(item.other_amount))}}
                    </td>
                    <td>
                        <button class="btn-xs btn-danger" type="button" @click="removeItem(index)"><i class="fa fa-times"></i></button>
                    </td>
                </tr>
                <tr>
                    <th colspan="3" class="text-center">Total</th>
                    <th class="">{{formatAmount(totalGrant)}}</th>
                    <th class="">{{formatAmount(totalEquity)}}</th>
                    <th class="">{{formatAmount(totalOtherFunds)}}</th>
                    <th class="">{{formatAmount(totalGrant + totalEquity +totalOtherFunds)}}</th>
                </tr>
            </tbody>
        </table>
        <div class="row justify-content-center ">
            <div class="form-group col-2">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success w-100']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<?php
$csrfToken = Yii::$app->request->csrfToken;
?>
<script>
    $(document).ready(function() {
        new Vue({
            el: '#main',
            data: {
                items: <?= !empty($model->getItems()) ? json_encode($model->getItems()) : json_encode([]) ?>
            },
            mounted() {
                $('#mgliquidations-fk_mgrfr_id').on('change', this.getNotifications);

            },
            computed: {
                totalGrant() {
                    return this.items.reduce((total, item) => total + parseFloat(item.matching_grant_amount), 0);
                },
                totalEquity() {
                    return this.items.reduce((total, item) => total + parseFloat(item.equity_amount), 0);
                },
                totalOtherFunds() {
                    return this.items.reduce((total, item) => total + parseFloat(item.other_amount), 0);
                },

            },
            methods: {

                getNotifications() {
                    const url = window.location.pathname + '?r=mg-liquidations/get-notifications-to-pay';
                    const data = {
                        id: $('#mgliquidations-fk_mgrfr_id').val(),
                        _csrf: '<?= $csrfToken ?>'
                    }
                    axios.post(url, data)
                        .then(response => {
                            this.items = response.data;
                        })
                        .catch(error => {
                            console.log(error)
                        })

                },
                removeItem(index) {
                    this.items.splice(index, 1);
                },
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                }
            }
        })
    });
</script>
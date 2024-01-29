<?php

use app\models\Office;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Pmr */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pmr-form" id="mainVue">

    <?php $form = ActiveForm::begin(); ?>
    <div class="card p-2">

        <div class="row">
            <div class="col-3">
                <?= $form->field($model, 'fk_office_id')->dropDownList(
                    ArrayHelper::map(Office::getOfficesA(), 'id', 'office_name'),
                    [
                        'prompt' => 'Select Office',
                        'v-model' => 'officeId'
                    ]
                ) ?>
            </div>
            <div class="col-3">
                <?= $form->field($model, 'reporting_period')->widget(DatePicker::class, [
                    'pluginOptions' => [
                        'format' => 'yyyy-mm',
                        'autoclose' => true,
                        'minViewMode' => 'months'
                    ]
                ]) ?>
            </div>

            <div class="form-group col-3 pt-4 mt-1">
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
                <button class="btn btn-primary" type="button" @click.prevent="generatePmr">Generate</button>
            </div>

        </div>

    </div>


    <?php ActiveForm::end(); ?>

    <div class="card p-2 table-container" style="max-width: 98%;">
        <table>
            <tr>

                <th rowspan="2">Office</th>
                <th rowspan="2">MFO/PAP</th>
                <th rowspan="2">Procurement Program/Project</th>
                <th rowspan="2">PMO/End-User</th>
                <th rowspan="2">Mode of Procurement</th>
                <th rowspan="2">PR Date Received</th>
                <th rowspan="2">PR Number</th>
                <th rowspan="2">RFQ Date</th>
                <th rowspan="2">RFQ Number</th>
                <th colspan="13">Actual Procurement Activity</th>
                <th rowspan="2">Source of Funds</th>
                <th colspan="3">ABC (PhP)</th>
                <th colspan="3">Contract Cost (PhP)</th>
                <th colspan="6">Date of Receipt of Invitation</th>
            </tr>
            <tr>
                <th>Pre-Proc Conference</th>
                <th>Ads/Post of IB Note: for AMP, Posting of RFQ, if applicable</th>
                <th>PhilGEPS Ref. No.</th>
                <th>Pre-bid Conf</th>
                <th>Eligibility Check</th>
                <th>Submission /Opening of Bids</th>
                <th>Bid Evaluation</th>
                <th>Post Qual</th>
                <th>Notice of Award Note: for AMP, PO Date</th>
                <th>Contract Signing Note: for AMP, PO Date</th>
                <th>Notice to Proceed Note: for AMP, PO Date</th>
                <th>Delivery/ Completion</th>
                <th>Inspection & Acceptance</th>
                <th>Total</th>
                <th>MOOE</th>
                <th>CO</th>
                <th>Total</th>
                <th>MOOE</th>
                <th>CO</th>
                <th>Pre-bid Conf</th>
                <th>Eligibility Check</th>
                <th>"Sub/Open of Bids" </th>
                <th>Bid Evaluation </th>
                <th>Post Qual</th>
                <th>"Delivery/ Completion/Acceptance"</th>

            </tr>
            <tr v-for="item in pmrDetails">

                <td>{{item.office_name}}</td>
                <td>{{item.mfo_codes}}</td>
                <td>{{item.purpose}}</td>
                <td>{{item.division_name}}</td>
                <td>{{item.mode_of_procurement_name}}</td>
                <td>{{item.rfq_created_at}}</td>
                <td>{{item.pr_number}}</td>
                <td>{{item.rfq_date}}</td>
                <td>{{item.rfq_number}}</td>
                <td>{{item.pre_proc_conference}}</td>
                <td>{{item.post_of_ib}}</td>
                <td>{{item.philgeps_reference_num}}</td>
                <td>{{item.pre_bid_conf}}</td>
                <td>{{item.eligibility_check}}</td>
                <td>{{item.opening_of_bids}}</td>
                <td>{{item.bid_evaluation}}</td>
                <td>{{item.post_qual}}</td>
                <td>{{item.contract_notice_of_awards}}</td>
                <td>{{item.contract_signing}}</td>
                <td>{{item.notice_to_proceed}}</td>
                <td></td>
                <td>{{item.rfq_iars}}</td>
                <td>{{item.source_of_fund}}</td>
                <td>{{parseFloat(item.mooe_amount) + parseFloat(item.co_amount)}}</td>
                <td>{{item.mooe_amount}}</td>
                <td>{{item.co_amount}}</td>
                <td>{{parseFloat(item.contract_mooe_amount) + parseFloat(item.contract_co_amount)}}</td>
                <td>{{item.contract_mooe_amount}}</td>
                <td>{{item.contract_co_amount}}</td>
                <td>{{item.invitation_pre_bid_conf}}</td>
                <td>{{item.invitation_eligibility_check}}</td>
                <td>{{item.invitation_opening_of_bids}}</td>
                <td>{{item.invitation_bid_evaluation}}</td>
                <td>{{item.invitation_post_qual}}</td>



            </tr>
        </table>
    </div>
</div>
<style>
    th,
    td {
        border: 1px solid black;
        text-align: center;
    }

    .table-container {
        width: calc(100vw - 50px);
        max-width: 100%;
        overflow-x: auto;
        /* Enable horizontal scrollbar when table exceeds screen width */
        margin: 20px;
        /* Add some margin for better appearance */
    }
</style>
<script>
    $(document).ready(function() {

        new Vue({
            el: "#mainVue",
            data: {
                officeId: '<?= $model->fk_office_id ?? "" ?>',
                pmrDetails: []
            },
            methods: {
                generatePmr() {
                    let url = "?r=pmr/generate"
                    let data = {
                        _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                        officeId: this.officeId,
                        reportingPeriod: $('#pmr-reporting_period').val()
                    }
                    axios.post(url, data)
                        .then(res => {
                            console.log(res)
                            this.pmrDetails = res.data
                            console.log(this.pmrDetails)
                        })
                        .catch(err => {
                            console.log(err)
                        })
                }
            }
        })
    })
</script>
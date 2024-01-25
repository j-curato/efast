<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Pmr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pmrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pmr-view" id="mainVue">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <div class="card p-2 table-container w-100" >
        <table >
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
                <td>{{item.purchase_orders}}</td>



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
        width: 100%;
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
                pmrDetails: <?= $pmrDetails ?>
            },
            methods: {

            }
        })
    })
</script>
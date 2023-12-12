<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiFundReleases */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Fund Releases', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-fund-releases-view" id="mainVue">
    <div class="container">
        <div class="card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'lrgModal btn btn-primary']) ?>
            </span>
        </div>
        <div class="card p-3">
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

        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#mainVue',
        data: {
            cashDetails: {
                cashDisbursementDetails: <?= json_encode($model->cashDisbursement->getDetails()) ?>,
                cashDisbursementItems: <?= json_encode($model->cashDisbursement->getItems()) ?>
            },


            subprojectDetails: <?= json_encode($model->fmiSubproject->getDetails()) ?>
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
        }

    })
</script>
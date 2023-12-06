<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Mgrfrs */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Mgrfrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mgrfrs-view" id="main">
    <div class="container ">
        <div class="card p-2">

            <span>
                <?= Yii::$app->user->can('update_rapid_mg_mgrfr') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
            </span>
        </div>
        <div class="card p-3">

            <table>

                <tr>
                    <th colspan="7" class="text-center border-0">
                        <span>Annex E</span> <br>
                        <span> Matching Grant Request for Release Form</span>
                    </th>
                </tr>
                <tr>
                    <th colspan="7" class="border-0">
                        <span>Province:___________________</span><br>
                        <span>Region:_____________________</span><br>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">NAME OF ORGANIZATION</th>
                    <td colspan="5"><?= $model->organization_name ?></td>
                </tr>
                <tr>
                    <th colspan="2">ADDRESS</th>
                    <td colspan="5">
                        <?= $model->purok ?>
                        <?= $model->barangay->barangay_name ?>,
                        <?= $model->municipality->municipality_name ?>,
                        <?= $model->province->province_name ?>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">SAVINGS ACCOUNT NUMBER</th>
                    <td colspan="5"><?= $model->saving_account_number ?></td>
                </tr>
                <tr>
                    <th colspan="2"> ACCOUNT NAME</th>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <th colspan="2"> LANDBANK / DBP BRANCH</th>
                    <td colspan="5"><?= strtoupper($model->bankBranchDetail->bankBranch->bank->name . '- ' . $model->bankBranchDetail->bankBranch->branch_name) ?></td>
                </tr>
                <tr>
                    <th colspan="2">NAME AND CONTRACT NUMBER OF THE AUTHORIZED REPRESENTATIVE</th>
                    <td colspan="5"><?= $model->authorized_personnel ?></td>
                </tr>
                <tr>
                    <th colspan="2">EMAIL ADDRESS</th>
                    <td colspan="5"><?= $model->email_address ?></td>
                </tr>
                <tr>
                    <th colspan="2">TYPE OF PROPOSED INVESTMENT</th>
                    <td colspan="5"><?= $model->investment_type ?></td>
                </tr>
                <tr>
                    <th colspan="2">DESCRIPTION OF INVESTMENT ( TO INCLUDE DESIGN, SPECIFICATIONS)</th>
                    <td colspan="5"><?= $model->investment_description ?></td>
                </tr>
                <tr>
                    <th colspan="2">WHO DID YOU CONSULT WHEN DEVELOPING THE IDEA FOR THE PROJECT?</th>
                    <td colspan="5"><?= $model->project_consultant ?></td>
                </tr>
                <tr>
                    <th colspan="2">OBJECTIVE OF THE PROPOSED PROJECT</th>
                    <td colspan="5"><?= $model->project_objective ?></td>
                </tr>
                <tr>
                    <th colspan="2">WHO WILL BENEFIT FROM THE PROPOSED PROJECT?</th>
                    <td colspan="5"><?= $model->project_beneficiary ?></td>
                </tr>
                <tr>
                    <th colspan="2">TOTAL COST OF PROPOSED PROJECT</th>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <th colspan="2">Amount of Matching Grant</th>
                    <td colspan="5"><?= number_format($model->matching_grant_amount, 2) ?></td>
                </tr>
                <tr>
                    <th colspan="2">Amount of Equity</th>
                    <td colspan="5"><?= number_format($model->equity_amount, 2) ?></td>
                </tr>
                <tr>
                    <th colspan="2">SOURCE OF FUNDING OF THE EQUITY COUNTERPART</th>
                    <td colspan="5"></td>
                </tr>
                <tr>
                    <th colspan="2">HAVE YOU REQUESTED FROM RAPID GROWTH PROJECT OTHER MATCHING GRANT ASSISTANCE FROM OTHER REGIONS FORT THIS PROPOSED PROJECT? IF YES, PLEASE SPECIFY</th>
                    <td colspan="5" class="">
                        <span>_____YES</span><br>
                        <span>_____NO</span><br>
                        <span>SPECIFY:</span>
                    </td>
                </tr>
                <tr>
                    <th colspan="2">HAVE YOU REQUESTED ASSISTANCE FROM OTHER SOURCES (AGENCIES AND INSTITUTIONS ) FOR THIS PROPOSED PROJECT? IF YES, PLEASE SPECIFY</th>
                    <td colspan="5">
                        <span>_____YES</span><br>
                        <span>_____NO</span><br>
                        <span>SPECIFY:</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="border-0 pt-3">
                        <span>Compliance Requirements and Attachments:</span><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="border-0 pl-3">1. Copy of Signed Matching Grant Agreement</td>
                </tr>
                <tr>
                    <td></td>
                    <th>DESCRIPTION OF PROPOSED PRODUCTIVE INVESTMENT/S AND NAME OF WINNING SUPPLIER/S</th>
                    <th>QUANTITY</th>
                    <th>UNIT COST</th>
                    <th>TOTAL COST</th>
                    <th>AMOUNT OF MATCHING GRANT</th>
                    <th>EQUITY COUNTER PART</th>
                </tr>
                <tr v-for="x in 10">
                    <td class="p-1"> {{ x }}</td>
                    <td v-for="y in 6"></td>
                </tr>
                <tr>
                    <td></td>
                    <th class="text-center"> GRAND TOTAL</th>
                    <td v-for="y in 5"></td>
                </tr>
                <tr>
                    <th colspan="7" class="border-0 pt-3">SUMMARY OF MATCHING GRANT AVAILMENT</th>
                </tr>
                <tr>
                    <td></td>
                    <th class="text-center">TYPE OF INVESTMENT</th>
                    <th class="text-center">TOTAL MATCHING GRANT UNDER THE AGREEMENT</th>
                    <th class="text-center">PREVIOUS MATCHING GRANT AVAILMENT</th>
                    <th class="text-center">THIS MATCHING GRANT AVAILMENT</th>
                    <th class="text-center">REMAINING MATCHING GRANT BALANCE</th>
                </tr>
                <tr v-for="x in 10">
                    <td class="p-1"> {{ x }}</td>
                    <td v-for="y in 5"></td>
                </tr>
                <tr>
                    <td colspan="7" class="border-0 p-2">
                        <p>
                            I / We hereby waived our rights in accordance with Republic Act No. 10173 or the "Data Privacy Act of 2012 and authority us given to validate and secure of any
                            and all confidential, privileged, persona, and/or sensitive personal information that the parties and their officers, employees, or agents may have access to; and shall store,
                            use, process, and dispose the said information.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th colspan="7" class="border-0">APPLICATION SUBMITTED BY:</th>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td colspan="2">NAME</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td colspan="2">TITLE/DESIGNATION</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td colspan="2" class="pb-3">SIGNATURE </td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td colspan="2">DATE</td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td colspan="7" class="p-3 border-0"></td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <th class="text-center"><u>ACTION TAKEN</u></th>
                    <th class="text-center"><u>UNIT AND NAME</u></th>
                    <th colspan="3" class="text-center"><u>SIGNATURE</u></th>
                </tr>

                <tr>
                    <td class="border-0"></td>
                    <td style="vertical-align: top;">
                        <span class="float-left">ENDORSED BY</span>
                    </td>
                    <td style="vertical-align: top;">PCU/PPC/RPC</td>
                    <td colspan="3" class="text-center pt-3">
                        <br>
                        <span>1___________________________________</span><br>
                        <span>Signature over Printed Name</span><br><br>
                        <span>2___________________________________</span><br>
                        <span>Signature over Printed Name</span><br><br>
                        <span>3___________________________________</span><br>
                        <span>Signature over Printed Name</span><br>
                    </td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td style="vertical-align: top;">RECOMMENDED*</td>
                    <td style="vertical-align: top;">DTI STAFF* PROVINCIAL DIRECTOR**</td>
                    <td colspan="3" class="text-center">
                        <span>___________________________________</span><br>
                        <span>Signature over Printed Name</span><br>

                    </td>
                </tr>
                <tr>
                    <td class="border-0"></td>
                    <td style="vertical-align: top;">APPROVED </td>
                    <td style="vertical-align: top;">PROVINCIAL DIRECTOR / REGIONAL DIRECTOR</td>
                    <td colspan="3" class="text-center">
                        <span>___________________________________</span><br>
                        <span>Signature over Printed Name</span><br>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="border-0">
                        <span>* Recommending DTI REgular if within PD Approving Authority / </span><br>
                        <span>** Recommending PD if within RD Approving Authority</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="border-0">COMMENTS:</td>
                </tr>
            </table>
        </div>

    </div>
</div>
<style>
    td,
    th {
        border: 1px solid black;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }
    }
</style>

<script>
    $(document).ready(function() {

        new Vue({
            el: '#main',

        })
    })
</script>
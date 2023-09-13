<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AllotmentModificationAdvice */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'MAF', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
var_dump(Yii::$app->snowflake->generateId());
// $number = 2; // Replace with your integer

// $q =  (int)(microtime(true) * 1000);
// echo $q;
// $binaryRepresentation = decbin(Yii::$app->snowflake->generateId());
// $numberOfBits = strlen($binaryRepresentation);

// var_dump("Number of bits: $numberOfBits");
?>
<div class="maf-view" id="main">



    <div class="container" style="background-color:white;padding:20px">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
        <table>
            <tr>
                <td colspan="7" class="no-bdr">
                    <b> Department of Trade and Industry</b><br>
                    <span> Agency/Operating Unit: <b>CARAGA</b></span><br>
                    <span>Address: 4th Floor J.C Aquino Avenue, Butuan City</span>

                </td>
            </tr>
            <tr>
                <td colspan="7" class="ctr no-bdr">
                    <br>
                    <b> MODIFICATION ADVICE FORM (MAF) NO.
                        <?php
                        echo  $model->serial_number;
                        echo ' (' . $model->mfoPapCode->name . ')' ?> </b>
                    <p><?= DateTime::createFromFormat('Y-m-d', $model->date_issued)->format('F d, Y') ?></p>

                </td>
            </tr>

            <tr>
                <td colspan="7" class="no-bdr">
                    <br>
                    <p>Funding Source: Regular Agency Fund-New General Appropriations-Specific Budgets of National Government Agencies.</p>
                    <span> Legal Basis: FY 2022 GAA RA 11639</span><br>
                    <b>DEFICIENT ITEMS (TO):</b>
                </td>
            </tr>

            <tr>
                <th>Office</th>
                <th>Division</th>
                <th>PROGRAMS/PROJECTS/ACTIVITIES</th>
                <th>RESPONSIBILITY CENTER</th>
                <th>ALLOTMENT CLASS</th>
                <th>OBJECT OF EXPENDITURES</th>
                <th>AMOUNT</th>
            </tr>
            <tr v-for="(item,index) in mafItems">
                <td class="upper-case">{{item.office_name}}</td>
                <td class="upper-case">{{item.division}}</td>
                <td>{{item.mfo_name}}</td>
                <td class="upper-case">{{item.division}}</td>
                <td>{{item.allotment_class}}</td>
                <td>{{item.chartOfAcc}}</td>
                <td class="amt">{{formatAmount(item.amount)}}</td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;">Total</th>
                <th class="amt">{{mafItemsTotal}}</th>
            </tr>
            <tr>
                <th colspan="7">SOURCE ITEMS (FROM):</th>
            </tr>
            <tr>
                <th>Office</th>
                <th>Division</th>
                <th>PROGRAMS/PROJECTS/ACTIVITIES</th>
                <th>RESPONSIBILITY CENTER</th>
                <th>ALLOTMENT CLASS</th>
                <th>OBJECT OF EXPENDITURES</th>
                <th>AMOUNT</th>
            </tr>
            <tr v-for="(item,index) in adjustItems">
                <td class="upper-case">{{item.office_name}}</td>
                <td class="upper-case">{{item.division}}</td>
                <td>{{item.mfo_name}}</td>
                <td class="upper-case">{{item.division}}</td>
                <td>{{item.allotment_class}}</td>
                <td>{{item.uacs}} - {{item.account_title}}</td>
                <td class="amt">{{formatAmount(item.amount)}}</td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;">Total</th>
                <th class="amt">{{adjustItemsTotal}}</th>
            </tr>
            <tr>
                <td colspan="7" style="text-align: center;" class="no-bdr">
                    <div style="width: 50%;float:left;">
                        <p style="margin-top: 20px;margin-bottom:40px">Prepared By</p>
                        <u><b>JULIETA B. OGOY</b></u>
                        <p>Administrative Officer V</p>
                    </div>
                    <div style="width: 50%; float:left;">
                        <p style="margin-top: 20px;margin-bottom:40px">Recommended By</p>
                        <u><b>JOHN VOLTAIRE S. ANCLA </b></u>
                        <p>Chief Administrative Officer</p>
                    </div>
                    <div style="width: 100%; ">
                        <p style="margin-top: 20px;margin-bottom:40px">Approved By</p>
                        <u><b>GAY A. TIDALGO, CESO IV</b></u>
                        <p>Regional Director</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>
<style>
    th,
    td {
        border: 1px solid black;
        padding: 5px;
    }

    .upper-case {
        text-transform: uppercase;
    }

    .amt {
        text-align: right;
    }

    .ctr {
        text-align: center;
    }

    .no-bdr {
        border: 0;
    }

    @media print {

        .btn,
        .main-footer {
            display: none;
        }

        th,
        td {
            padding: 5px;
            font-size: 10px;
        }
    }
</style>
<script>
    $(document).ready(function() {

        new Vue({
            el: '#main',
            data: {
                mafItems: <?= json_encode($model->getMafItems()) ?>,
                adjustItems: <?= json_encode($model->getAdjustmentItems()) ?>
            },
            computed: {
                mafItemsTotal() {
                    return this.formatAmount(this.mafItems.reduce((total, item) => total + parseFloat(item.amount), 0));
                },
                adjustItemsTotal() {
                    return this.formatAmount(this.adjustItems.reduce((total, item) => total + parseFloat(item.amount), 0));
                }
            },
            methods: {
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString()
                    }
                    return 0;
                }
            }
        })
    })
</script>
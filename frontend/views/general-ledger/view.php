<?php


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;

$this->title = 'General Ledger';
$this->params['breadcrumbs'][] = ['label' => 'General Ledgers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="jev-preparation-index" id="main">
    <?php
    $generalLedger = Yii::$app->db->createCommand("SELECT object_code,account_title FROM accounting_codes WHERE object_code  = :object_code")
        ->bindValue(':object_code', $model->object_code)
        ->queryOne();
    ?>

    <div class="container card">

        <p>
            <?= Yii::$app->user->can('update_general_ledger') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => ' btn btn-primary']) : '' ?>
            <?= Yii::$app->user->can('export_general_ledger') ? Html::button('Export', ['id' => 'export', 'type' => 'button', 'class' => 'btn btn-success']) : '' ?>
            <!-- <button id="export" type='button' class="btn btn-success" style="margin:1rem;">Export</button> -->
        </p>
        <div id='con'>

            <table id="data_table" style="margin-top:30px" v-show="showTable">
                <thead>


                    <tr class="document_header1">
                        <th colspan="2">
                            Entity Name:
                        </th>
                        <th>
                            DEPARTMENT OF TRADE AND INDUSTRY
                        </th>

                        <th colspan="2">
                            Fund Cluster Code:
                        </th>
                        <th colspan="2">
                            <?php
                            echo $model->book->name;
                            ?>
                        </th>
                    </tr>


                    <tr class="document_header1">
                        <th colspan="2">
                            Account Title:
                        </th>
                        <th>
                            <?php
                            if (!empty($generalLedger['account_title'])) {
                                echo $generalLedger['account_title'];
                            }
                            ?>
                        </th>
                        <th colspan="2">
                            UACS Object Code:
                        </th>

                        <th colspan="2">
                            <?php
                            if (!empty($generalLedger['object_code'])) {
                                echo $generalLedger['object_code'];
                            }
                            ?>


                        </th>
                    </tr>
                    <tr class="head">

                        <th rowspan="3">
                            Reporting Period
                        </th>
                        <th rowspan="3">
                            Date
                        </th>
                        <th rowspan="3">
                            Particulars
                        </th>
                        <th rowspan="3">
                            Reference No.
                        </th>
                        <th rowspan="1" colspan="3">
                            Amount
                        </th>

                    </tr>
                    <tr>
                        <th rowspan="1">
                            Debit
                        </th>
                        <th rowspan="1">
                            Credit
                        </th>
                        <th rowspan="1">
                            Balance
                        </th>

                    </tr>
                    <?php

                    ?>
                </thead>
                <tbody id="ledgerTable">
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Beginning Balance</td>
                        <td></td>
                        <td class='amt'>{{formatAmount(beginningBalances.debit)}}</td>
                        <td class='amt'>{{formatAmount(beginningBalances.credit)}}</td>
                        <td class='amt'>{{formatAmount(beginningBalances.balance)}}</td>
                    </tr>
                    <tr v-for="(item,index) in items" :key='index'>
                        <td>{{item.reporting_period}}</td>
                        <td>{{item.date}}</td>
                        <td>{{item.particular}}</td>
                        <td>{{item.jev_number}}</td>
                        <td class='amt'>{{formatAmount(item.debit)}}</td>
                        <td class='amt'>{{formatAmount(item.credit)}}</td>
                        <td class='amt'>{{formatAmount(item.running_balance)}}</td>
                    </tr>

                </tbody>
            </table>


        </div>

    </div>
    <!-- <div class=" center-container" v-show='loading'>
        <pulse-loader :loading="loading" :color="loaderColor" :size="loaderSize"></pulse-loader>
    </div> -->
</div>

<style>
    .amt {
        text-align: right;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
        background-color: white;
        text-align: center;
    }



    table {
        width: 100%;
    }


    .head>th {
        display: table-cell;
        vertical-align: text-top;
        text-align: center;
    }

    thead>tr>td {
        border: 1px solid black;
        padding: 10px;
        font-weight: bold;
    }




    .document_header1>th {

        padding: 10px;
        border: 0;
        text-align: left;
    }

    @media print {
        .actions {
            display: none;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        thead>tr>td {
            border: 1px solid black;
            padding: 5px;
            font-weight: bold;
        }

        @page {
            size: auto;
            margin: 0cm;
            margin-top: 0.5cm;
        }



        .container {
            margin: 0;
            top: 0;

        }

        .entity_name {
            font-size: 5pt;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 5px;
            background-color: white;
        }

        .container {

            border: none;
        }


        table {
            page-break-after: auto
        }

        tr {
            page-break-inside: avoid;
            page-break-after: auto
        }

        td {
            page-break-inside: avoid;
            page-break-after: auto
        }

        /* thead {
                display: table-header-group
            } */

        .main-footer {
            display: none;
        }
    }
</style>

<?php
$this->registerCssFile(yii::$app->request->baseUrl . "/frontend/web/css/site.css", ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/generalLedgerJs.js", ['depends' => [\yii\web\JqueryAsset::class]]);
$csrfToken = Yii::$app->request->csrfToken;
$beginBalance =  $model->getBeginningBalance();
$this->registerJsFile('@web/js/vue-spinner.min.js', ['position' => $this::POS_HEAD]);
?>
<script>
    var PulseLoader = VueSpinner.PulseLoader
    $(document).ready(function() {
        $('#export').click(function(e) {
            e.preventDefault();
            $.ajax({
                container: "#employee",
                type: 'POST',
                url: window.location.pathname + '?r=general-ledger/export',
                data: {
                    reporting_period: reporting_period,
                    object_code: object_code,
                    book_id: book_id
                },
                success: function(data) {
                    var res = JSON.parse(data)
                    console.log(res)
                    window.open(res)
                }

            })
        })
        new Vue({
            el: '#main',
            components: {
                'PulseLoader': PulseLoader,
            },
            data: {
                items: <?= json_encode($model->queryGeneralLedger()) ?>,
                beginningBalances: <?= json_encode(!empty($beginBalance) ? $beginBalance : [])  ?>,
                balance: <?= json_encode(!empty($beginBalance) ? $beginBalance['balance'] : [])  ?>,
                endingBalance: 0,
                loading: true,
                loaderColor: '#03befc',
                loaderSize: '20px',
                showTable: true,
            },
            methods: {

                calculateRunningBalance() {
                    let index = 0
                    let beginningBalance = this.balance
                    for (const item of this.items) {
                        beginningBalance = (parseFloat(beginningBalance) + parseFloat(item.debit)) - parseFloat(item.credit)
                        this.items[index].running_balance = beginningBalance
                        index++
                    }
                    this.items = this.items
                },
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                },

            },
            created() {
                this.calculateRunningBalance()
            },

            computed: {

            },
        })
    })
</script>
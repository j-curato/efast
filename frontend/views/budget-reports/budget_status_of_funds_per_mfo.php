<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Status of Funds per MFO/PAP";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-preparation-index " style="background-color: white;padding:20px" id="main">

    <form id="filter" @submit.prevent="filterForm" style="margin-bottom: 10px;">
        <div class="row">
            <div class="col-sm-3">
                <label for="from_reporting_period"> From Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'reporting_period',
                    'id' => 'reporting_period',
                    'pluginOptions' => [
                        'minViewMode' => 'months',
                        'autoclose' => true,
                        'format' => 'yyyy-mm',
                        'required' => true,
                        'allowClear' => true
                    ]
                ])
                ?>

            </div>

            <div class="col-sm-2" style="margin-top: 2.5rem;">
                <button class="btn btn-success" type="submit">Generate</button>
            </div>

        </div>

    </form>



    <table v-show='showTable'>
        <thead>
            <tr>
                <th>PARTICULARS</th>
                <th>APPROPRIATIONS(GARO)</th>
                <th>FOR LATER RELEASE</th>
                <th>SUB-ALLOTMENT(SAA/Sub-ARO)</th>
                <th>OTHER DOCUMENTS</th>
                <th>ADJUSTMENT</th>
                <th>NET Program</th>
                <th>OBLIGATIONS</th>
                <th>BALANCE</th>
                <th>% UTILIZATION</th>
                <th>REMARKS</th>
            </tr>
        </thead>
        <tr class="info">
            <th colspan="11" style="background-color: #27c3f2;">PERSONNEL SERVICES</th>
        </tr>
        <tr v-for="(item,index) in personelServicesLists">
            <td class='amt'>{{item.mfo_name}}</td>
            <td class='amt'>{{formatAmount(item.ttlGaroAllotment)}}</td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlSubAroAllotment)}}</td>
            <td class='amt'>{{formatAmount(item.ttlOtherDocs)}}</td>
            <td class='amt'>{{formatAmount(item.ttlAdjustment)}}</td>
            <td class='amt'>{{formatAmount(item.grossAmt)}}</td>
            <td class='amt'>{{formatAmount(item.ttlOrs)}}</td>
            <td class='amt'>{{formatAmount(item.balance)}}</td>
            <td class='amt'>{{item.utilizationRate}}%</td>
            <td></td>
        </tr>
        <tr class="success">
            <th colspan="11" style="background-color: #0fd467;">MOOE</th>
        </tr>
        <tr v-for="(item,index) in mooeList">
            <td class='amt'>{{item.mfo_name}}</td>
            <td class='amt'>{{formatAmount(item.ttlGaroAllotment)}}</td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlSubAroAllotment)}}</td>
            <td class='amt'>{{formatAmount(item.ttlOtherDocs)}}</td>
            <td class='amt'>{{formatAmount(item.ttlAdjustment)}}</td>
            <td class='amt'>{{formatAmount(item.grossAmt)}}</td>
            <td class='amt'>{{formatAmount(item.ttlOrs)}}</td>
            <td class='amt'>{{formatAmount(item.balance)}}</td>
            <td class='amt'>{{item.utilizationRate}}%</td>
            <td></td>
        </tr>
        <tr class="warning">
            <th colspan="11" style="background-color:#d6e810;">CAPITAL OUTLAY</th>
        </tr>
        <tr v-for="(item,index) in capitalOutlayList">
            <td class='amt'>{{item.mfo_name}}</td>
            <td class='amt'>{{formatAmount(item.ttlGaroAllotment)}}</td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlSubAroAllotment)}}</td>
            <td class='amt'>{{formatAmount(item.ttlOtherDocs)}}</td>
            <td class='amt'>{{formatAmount(item.ttlAdjustment)}}</td>
            <td class='amt'>{{formatAmount(item.grossAmt)}}</td>
            <td class='amt'>{{formatAmount(item.ttlOrs)}}</td>
            <td class='amt'>{{formatAmount(item.balance)}}</td>
            <td class='amt'>{{item.utilizationRate}}%</td>
            <td></td>
        </tr>
        <tr>
            <th class="ctr">Total</th>
            <th class="amt">{{formatAmount(grandTotals('garo'))}}</th>
            <th></th>
            <th class="amt">{{formatAmount(grandTotals('sub-aro'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('other-docs'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('adjustments'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('grossAmt'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('ors'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('balance'))}}</th>
            <th class="amt">{{calculateUtilization(grandTotals('ors'),grandTotals('grossAmt'))}}%</th>
        </tr>
    </table>
    <div class=" center-container" v-show='loading'>
        <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
    </div>
</div>
<style>
    .center-container {
        display: flex;
        justify-content: center;
        /* Horizontally center */
        align-items: center;
        /* Vertically center */
        height: 70vh;
        /* 100% of the viewport height */
    }


    table,
    th,
    td {
        border: 1px solid black;
        padding: 8px;
    }

    #summary_table {
        margin-top: 30px;
    }

    /* #con {
        display: none;
    } */

    .amt {
        text-align: right;
    }

    @media print {
        #summary_table {
            margin-top: 0;
        }

        table,
        th,
        td {
            padding: 5px;
            font-size: 10px;
        }

        .row {
            display: none
        }

        .main-footer {
            display: none;
        }

        .panel {
            padding: 0;
        }

    }
</style>

<?php
$csrf = YIi::$app->request->csrfToken;

?>
<script src="
https://cdn.jsdelivr.net/npm/vue-spinner@1.0.4/dist/vue-spinner.min.js
"></script>
<script>
    var PulseLoader = VueSpinner.PulseLoader
    var RingLoader = VueSpinner.RingLoader
    $(document).ready(function() {
        // var PulseLoader = '/q/node_modules/vue-spinner/src/PulseLoader.vue';
        new Vue({
            el: '#main',
            components: {
                'PulseLoader': PulseLoader,
                'RingLoader': RingLoader,
            },
            data: {
                year: '',
                personelServicesLists: [],
                mooeList: [],
                capitalOutlayList: [],
                loading: false,
                // color: '#03befc',
                size: '20px',
                showTable: false,
            },
            methods: {
                formatReturnedData(obj) {
                    return Object.keys(obj).map((key) => {
                        const val = obj[key]
                        const ttlGaroAllotment = parseFloat(this.getItemData(val, 'GARO'))
                        const ttlSubAroAllotment = parseFloat(this.getItemData(val, 'SAA/Sub-ARO'))
                        const ttlAdjustment = parseFloat(this.getItemTtls(val, 'adjustment'))
                        const ttlOrs = parseFloat(this.getItemTtls(val, 'ors'))
                        const ttlOtherDocs = parseFloat(this.getItemOtherDocsTtl(val))
                        const grossAmt = ttlGaroAllotment + ttlSubAroAllotment + ttlOtherDocs + ttlAdjustment
                        const balance = grossAmt - ttlOrs
                        const utilizationRate = (ttlOrs / grossAmt) * 100
                        return {
                            mfo_name: key,
                            value: val,
                            ttlGaroAllotment: ttlGaroAllotment,
                            ttlSubAroAllotment: ttlSubAroAllotment,
                            ttlAdjustment: ttlAdjustment,
                            ttlOrs: ttlOrs,
                            ttlOtherDocs: ttlOtherDocs,
                            grossAmt: grossAmt.toFixed(2),
                            balance: balance.toFixed(2),
                            utilizationRate: utilizationRate.toFixed(2),
                        }



                    });

                },
                filterForm() {
                    this.loading = true
                    this.showTable = false
                    const url = window.location.href
                    const data = {
                        reporting_period: $("#reporting_period").val(),
                        _csrf: '<?= $csrf ?>'
                    }
                    const response = axios.post(url, data)
                        .then((response) => {
                            const mooeData = response.data["Maintenance and Other Operating Expenses"];
                            const capitalOutlayData = response.data["Capital Outlays"]
                            const personnelServicesData = response.data["Personnel Services"]
                            if (personnelServicesData) {
                                // this.personelServicesLists = Object.keys(personnelServicesData).map((key) => {
                                //     const val = personnelServicesData[key]
                                //     const ttlGaroAllotment = parseFloat(this.getItemData(val, 'GARO'))
                                //     const ttlSubAroAllotment = parseFloat(this.getItemData(val, 'SAA/Sub-ARO'))
                                //     const ttlAdjustment = parseFloat(this.getItemTtls(val, 'adjustment'))
                                //     const ttlOrs = parseFloat(this.getItemTtls(val, 'ors'))
                                //     const ttlOtherDocs = parseFloat(this.getItemOtherDocsTtl(val))
                                //     const grossAmt = ttlGaroAllotment + ttlSubAroAllotment + ttlOtherDocs + ttlAdjustment
                                //     const balance = grossAmt - ttlOrs
                                //     const utilizationRate = (ttlOrs / grossAmt) * 100
                                //     return {
                                //         mfo_name: key,
                                //         value: val,
                                //         ttlGaroAllotment: ttlGaroAllotment,
                                //         ttlSubAroAllotment: ttlSubAroAllotment,
                                //         ttlAdjustment: ttlAdjustment,
                                //         ttlOrs: ttlOrs,
                                //         ttlOtherDocs: ttlOtherDocs,
                                //         grossAmt: grossAmt.toFixed(2),
                                //         balance: balance.toFixed(2),
                                //         utilizationRate: utilizationRate.toFixed(2),
                                //     }



                                // });
                                this.personelServicesLists = this.formatReturnedData(personnelServicesData)
                            }
                            if (capitalOutlayData) {
                                this.capitalOutlayList = this.formatReturnedData(capitalOutlayData)
                            }
                            if (mooeData) {
                                this.mooeList = this.formatReturnedData(mooeData)
                            }
                            setTimeout(() => {
                                this.loading = false

                                setTimeout(() => {
                                    this.showTable = true
                                }, 100)
                            }, 800)
                        }).catch((error) => {
                            console.log(error)
                        })
                },
                getItemData(data, documentName) {
                    const keys = Object.keys(data)
                    for (let key of keys) {
                        if (key === documentName) {
                            return data[key].ttlAllotment
                            break
                        }
                    }
                    return 0

                },
                getItemTtls(data, amountName) {
                    let total = 0

                    Object.keys(data).map(key => {

                        if (amountName === 'ors') {
                            total += parseFloat(data[key].ttlOrs)
                        } else if (amountName === 'adjustment') {
                            total += parseFloat(data[key].ttlAdjustment)
                        }
                    })
                    return total.toFixed(2)
                },
                getItemOtherDocsTtl(data) {
                    let total = 0
                    Object.keys(data).map(key => {
                        if (key !== 'GARO' && key !== 'SAA/Sub-ARO') {
                            total += parseFloat(data[key].ttlAllotment)
                        }
                    })
                    return total.toFixed(2)
                },
                formatAmount(amount) {

                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString()
                    }
                    return 0;
                },
                calculteItemGrossAllot(item) {
                    let total = 0;

                    total =
                        (item.ttlGaroAllotment +
                            item.ttlSubAroAllotment +
                            item.ttlOtherDocs +
                            item.ttlAdjustment)

                    return total.toFixed(2);
                },
                calculteItemBalance(item) {
                    let total = 0;

                    total =
                        (item.ttlGaroAllotment +
                            item.ttlSubAroAllotment +
                            item.ttlOtherDocs +
                            item.ttlAdjustment) - item.ttlOrs

                    return total.toFixed(2);
                },
                calculateUtilization(numerator, denominator) {
                    return ((numerator / denominator) * 100).toFixed(2) + '%'
                },
                calculateMfoTtl(arr, amtName) {
                    return arr.reduce((total, item) => {
                        if (amtName == 'garo') {
                            return total + parseFloat(item.ttlGaroAllotment)
                        } else if (amtName == 'sub-aro') {
                            return total + parseFloat(item.ttlSubAroAllotment)
                        } else if (amtName == 'other-docs') {
                            return total + parseFloat(item.ttlOtherDocs)
                        } else if (amtName == 'adjustments') {
                            return total + parseFloat(item.ttlAdjustment)
                        } else if (amtName == 'grossAmt') {
                            return total + parseFloat(item.grossAmt)
                        } else if (amtName == 'ors') {
                            return total + parseFloat(item.ttlOrs)
                        } else if (amtName == 'balance') {
                            return total + parseFloat(item.balance)
                        }
                    }, 0)
                },
                grandTotals(amtName) {
                    let psTotal = 0
                    let mooeTotal = 0
                    let coTotal = 0
                    if (this.personelServicesLists.length > 0) {
                        psTotal = this.calculateMfoTtl(this.personelServicesLists, amtName)
                    }
                    if (this.mooeList.length > 0) {
                        mooeTotal = this.calculateMfoTtl(this.mooeList, amtName)
                    }
                    if (this.capitalOutlayList.length > 0) {
                        coTotal = this.calculateMfoTtl(this.capitalOutlayList, amtName)
                    }

                    return (parseFloat(psTotal) + parseFloat(mooeTotal) + parseFloat(coTotal)).toFixed(2)
                },

            },
            computed: {



            }
        })
    })
</script>
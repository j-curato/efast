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
                <th colspan="2">PARTICULARS</th>
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
        <tr class="info" style="background-color: #bef1fa;">
            <th class="" colspan="2">PERSONNEL SERVICES</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'garo'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'flr'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'sub-aro'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'other-docs'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'adjustments'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'grossAmt'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'ors'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(personelServicesLists,'balance'))}}</th>
            <th class="amt">{{calculateUtilization(calculateAllotmentClassTotal(personelServicesLists,'ors'),calculateAllotmentClassTotal(personelServicesLists,'grossAmt'))}}</th>
            <td></td>

        </tr>
        <tbody v-for="(psItem, psIndex) in personelServicesLists">
            <tr class="officeTtl">
                <th class='ctr'>{{ psItem.office_name }}</th>
                <th></th>

                <th class='amt'>{{ formatAmount(calculateTotalPerOffice(psItem,'garo')) }}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'flr'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'sub-aro'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'other-docs'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'adjustments'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'grossAmt'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'ors'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(psItem,'balance'))}}</th>
                <th class="amt">{{calculateUtilization(calculateTotalPerOffice(psItem,'ors'),calculateTotalPerOffice(psItem,'grossAmt'))}}</th>
                <td></td>
            </tr>
            <tr v-for="(officeItem, officeIndex) in psItem.value">
                <td></td>
                <th class='ctr'>{{ officeItem.division_name}}</th>
                <td class='amt'>{{formatAmount(officeItem.ttlGaroAllotment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlFlr)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlSubAroAllotment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlOtherDocs)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlAdjustment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.grossAmt)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlOrs)}}</td>
                <td class='amt'>{{formatAmount(officeItem.balance)}}</td>
                <td class='amt'>{{officeItem.utilizationRate}}%</td>
                <td></td>
            </tr>
        </tbody>


        <tr class="success" style="background-color: #befac6;">
            <th colspan="2" class="ctr">MOOE</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'garo'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'flr'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'sub-aro'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'other-docs'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'adjustments'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'grossAmt'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'ors'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(mooeList,'balance'))}}</th>
            <th class="amt">{{calculateUtilization(calculateAllotmentClassTotal(mooeList,'ors'),calculateAllotmentClassTotal(mooeList,'grossAmt'))}}</th>
            <td></td>
        </tr>
        <tbody v-for="(mooeItem, mooeIndex) in mooeList">
            <tr class="officeTtl">
                <th class='ctr '>{{ mooeItem.office_name }}</th>
                <th></th>
                <th class='amt'>{{ formatAmount(calculateTotalPerOffice(mooeItem,'garo')) }}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'flr'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'sub-aro'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'other-docs'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'adjustments'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'grossAmt'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'ors'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(mooeItem,'balance'))}}</th>
                <th class="amt">{{calculateUtilization(calculateTotalPerOffice(mooeItem,'ors'),calculateTotalPerOffice(mooeItem,'grossAmt'))}}</th>
                <td></td>
            </tr>
            <tr v-for="(officeItem, officeIndex) in mooeItem.value">
                <td></td>
                <th class='ctr'>{{ officeItem.division_name}}</th>
                <td class='amt'>{{formatAmount(officeItem.ttlGaroAllotment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlFlr)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlSubAroAllotment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlOtherDocs)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlAdjustment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.grossAmt)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlOrs)}}</td>
                <td class='amt'>{{formatAmount(officeItem.balance)}}</td>
                <td class='amt'>{{officeItem.utilizationRate}}%</td>
                <td></td>
            </tr>
        </tbody>

        <tr class="warning" style="background-color:#f8fabe;">
            <th colspan="2" class="ctr">CAPITAL OUTLAY</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'garo'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'flr'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'sub-aro'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'other-docs'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'adjustments'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'grossAmt'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'ors'))}}</th>
            <th class="amt">{{formatAmount(calculateAllotmentClassTotal(capitalOutlayList,'balance'))}}</th>
            <th class="amt">{{calculateUtilization(calculateAllotmentClassTotal(capitalOutlayList,'ors'),calculateAllotmentClassTotal(capitalOutlayList,'grossAmt'))}}</th>
            <td></td>
        </tr>

        <tbody v-for="(coItem, coIndex) in capitalOutlayList">
            <tr class="officeTtl">
                <th class='ctr '>{{ coItem.office_name }}</th>
                <th></th>
                <th class='amt'>{{ formatAmount(calculateTotalPerOffice(coItem,'garo')) }}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'flr'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'sub-aro'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'other-docs'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'adjustments'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'grossAmt'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'ors'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerOffice(coItem,'balance'))}}</th>
                <th class="amt">{{calculateUtilization(calculateTotalPerOffice(coItem,'ors'),calculateTotalPerOffice(coItem,'grossAmt'))}}</th>
                <td></td>
            </tr>
            <tr v-for="(officeItem, officeIndex) in coItem.value">
                <td></td>
                <th class='ctr'>{{ officeItem.division_name}}</th>
                <td class='amt'>{{formatAmount(officeItem.ttlGaroAllotment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlFlr)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlSubAroAllotment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlOtherDocs)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlAdjustment)}}</td>
                <td class='amt'>{{formatAmount(officeItem.grossAmt)}}</td>
                <td class='amt'>{{formatAmount(officeItem.ttlOrs)}}</td>
                <td class='amt'>{{formatAmount(officeItem.balance)}}</td>
                <td class='amt'>{{officeItem.utilizationRate}}%</td>
                <td></td>
            </tr>
        </tbody>
        <tr style="background-color:#22cbf5;">
            <th class="ctr" colspan="2">Total</th>
            <th class="amt">{{formatAmount(grandTotals('garo'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('flr'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('sub-aro'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('other-docs'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('adjustments'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('grossAmt'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('ors'))}}</th>
            <th class="amt">{{formatAmount(grandTotals('balance'))}}</th>
            <th class="amt">{{calculateUtilization(grandTotals('ors'),grandTotals('grossAmt'))}}</th>
            <td></td>
        </tr>
        <tr style="background-color:#9ff536;">
            <th class="ctr" colspan="2">MOOE + CO Total</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('garo'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('flr'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('sub-aro'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('other-docs'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('adjustments'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('grossAmt'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('ors'))}}</th>
            <th class="amt">{{formatAmount(coPlusMooeTtl('balance'))}}</th>
            <th class="amt">{{calculateUtilization(coPlusMooeTtl('ors'),coPlusMooeTtl('grossAmt'))}}</th>
            <td></td>
        </tr>
    </table>
    <div class=" center-container" v-show='loading'>
        <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
    </div>
</div>
<style>
    .officeTtl {
        background-color: #e3e6e4;
    }

    .center-container {
        display: flex;
        justify-content: center;
        /* Horizontally center */
        align-items: center;
        /* Vertically center */
        height: 70vh;
        /* 100% of the viewport height */
    }

    .ctr {
        text-align: center;
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
                color: '#03befc',
                size: '20px',
                showTable: true,
            },
            methods: {
                formatReturnedData(data) {
                    return Object.keys(data).map((officeKey) => {
                        const obj = data[officeKey]
                        const divisionDts = Object.keys(obj).map((divisionKey) => {
                            const val = obj[divisionKey]
                            const ttlGaroAllotment = parseFloat(this.getItemData(val, 'GARO'))
                            const ttlSubAroAllotment = parseFloat(this.getItemData(val, 'SAA/Sub-ARO'))
                            const ttlFlr = parseFloat(this.getItemData(val, 'For Later Release'))
                            const ttlAdjustment = parseFloat(this.getItemTtls(val, 'adjustment'))
                            const ttlOrs = parseFloat(this.getItemTtls(val, 'ors'))
                            const ttlOtherDocs = parseFloat(this.getItemOtherDocsTtl(val))
                            const grossAmt = ttlGaroAllotment + ttlFlr + ttlSubAroAllotment + ttlOtherDocs + ttlAdjustment
                            const balance = grossAmt - ttlOrs
                            const utilizationRate = (ttlOrs / grossAmt) * 100

                            return {
                                division_name: divisionKey,
                                value: val,
                                ttlGaroAllotment: ttlGaroAllotment,
                                ttlSubAroAllotment: ttlSubAroAllotment,
                                ttlAdjustment: ttlAdjustment,
                                ttlOrs: ttlOrs,
                                ttlOtherDocs: ttlOtherDocs,
                                grossAmt: grossAmt.toFixed(2),
                                balance: balance.toFixed(2),
                                utilizationRate: utilizationRate.toFixed(2),
                                ttlFlr: ttlFlr.toFixed(2),

                            }
                        })
                        return {
                            office_name: officeKey,
                            value: divisionDts,
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
                            // console.log(personnelServicesData)
                            if (personnelServicesData) {

                                this.personelServicesLists = this.formatReturnedData(personnelServicesData)
                            }
                            if (capitalOutlayData) {
                                this.capitalOutlayList = this.formatReturnedData(capitalOutlayData)
                            }
                            if (mooeData) {
                                this.mooeList = this.formatReturnedData(mooeData)
                                console.log(this.mooeList)
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
                    const ttl = ((numerator / denominator) * 100)
                    return !isNaN(ttl) ? ttl.toFixed(2) + '%' : 0
                },
                getObectTtls(arr, amtName) {

                    return Object.keys(arr).reduce((ttl, key) => {

                        if (amtName == 'garo') {
                            return ttl + parseFloat(arr[key].ttlGaroAllotment)
                        } else if (amtName == 'sub-aro') {
                            return ttl + parseFloat(arr[key].ttlSubAroAllotment)
                        } else if (amtName == 'other-docs') {
                            return ttl + parseFloat(arr[key].ttlOtherDocs)
                        } else if (amtName == 'adjustments') {
                            return ttl + parseFloat(arr[key].ttlAdjustment)
                        } else if (amtName == 'grossAmt') {
                            return ttl + parseFloat(arr[key].grossAmt)
                        } else if (amtName == 'ors') {
                            return ttl + parseFloat(arr[key].ttlOrs)
                        } else if (amtName == 'balance') {
                            return ttl + parseFloat(arr[key].balance)
                        } else if (amtName == 'flr') {
                            return ttl + parseFloat(arr[key].ttlFlr)
                        }
                    }, 0)
                },
                calculateAllotmentClassTotal(arr, amtName) {
                    return arr.reduce((total, item) => {
                        return total + this.getObectTtls(item.value, amtName)
                    }, 0)
                },
                calculate() {

                },
                grandTotals(amtName) {
                    let psTotal = 0
                    let mooeTotal = 0
                    let coTotal = 0
                    if (this.personelServicesLists.length > 0) {
                        psTotal = this.calculateAllotmentClassTotal(this.personelServicesLists, amtName)
                    }
                    if (this.mooeList.length > 0) {
                        mooeTotal = this.calculateAllotmentClassTotal(this.mooeList, amtName)
                    }
                    if (this.capitalOutlayList.length > 0) {
                        coTotal = this.calculateAllotmentClassTotal(this.capitalOutlayList, amtName)
                    }
                    return (parseFloat(psTotal) + parseFloat(mooeTotal) + parseFloat(coTotal)).toFixed(2)
                },
                coPlusMooeTtl(amtName) {
                    let mooeTotal = 0
                    let coTotal = 0
                    if (this.mooeList.length > 0) {
                        mooeTotal = this.calculateAllotmentClassTotal(this.mooeList, amtName)
                    }
                    if (this.capitalOutlayList.length > 0) {
                        coTotal = this.calculateAllotmentClassTotal(this.capitalOutlayList, amtName)
                    }
                    return (parseFloat(mooeTotal) + parseFloat(coTotal)).toFixed(2)
                },

                calculateTotalPerOffice(data, amtName) {
                    return this.getObectTtls(data.value, amtName)
                }

            },
            computed: {



            }
        })
    })
</script>
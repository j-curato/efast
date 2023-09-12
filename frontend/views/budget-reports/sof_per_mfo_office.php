<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Status of Funds per MFO/PAP & Office";
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

    <table v-show='showTable' class="hover-highlight">
        <thead>
            <tr>
                <th colspan="3" class="ctr">PARTICULARS</th>
                <th class="ctr">APPROPRIATIONS(GARO)</th>
                <th class="ctr">FOR LATER RELEASE</th>
                <th class="ctr">SUB-ALLOTMENT(SAA/Sub-ARO)</th>
                <th class="ctr">OTHER DOCUMENTS</th>
                <th class="ctr">ADJUSTMENT</th>
                <th class="ctr">NET Program</th>
                <th class="ctr">OBLIGATIONS</th>
                <th class="ctr">BALANCE</th>
                <th class="ctr">% UTILIZATION</th>
                <th class="ctr">REMARKS</th>
            </tr>
        </thead>
        <tr class="info" style="background-color: #bef1fa;">
            <th class="" colspan="3">PERSONNEL SERVICES</th>
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

        <template v-for="(psItem, psIndex) in personelServicesLists" style="width: 100%;">
            <tr class="officeTtl">
                <th class='ctr '>{{ psItem.mfo_name }}</th>
                <td></td>
                <td></td>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'garo'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'flr'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'sub-aro'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'other-docs'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'adjustments'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'grossAmt'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'ors'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(psItem,'balance'))}}</th>
                <th class="amt">{{calculateUtilization(calculateTotalPerMfo(psItem,'ors'),calculateTotalPerMfo(psItem,'grossAmt'))}}</th>
                <td></td>
            </tr>
            <template v-for="(officeItem, officeIndex) in psItem.value">
                <tr>
                    <td></td>
                    <th class='ctr'>{{ officeItem.office_name}}</th>
                    <th></th>
                    <th class='amt'>{{ formatAmount(calculateTotalPerOffice(officeItem,'garo')) }}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'flr'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'sub-aro'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'other-docs'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'adjustments'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'grossAmt'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'ors'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'balance'))}}</th>
                    <th class="amt">{{calculateUtilization(calculateTotalPerOffice(officeItem,'ors'),calculateTotalPerOffice(officeItem,'grossAmt'))}}</th>
                    <td></td>
                </tr>
                <tr v-for="divisionItem in officeItem.value">
                    <td></td>
                    <td></td>
                    <td class="amt">{{divisionItem.division_name}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlGaroAllotment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlFlr)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlSubAroAllotment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlOtherDocs)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlAdjustment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.grossAmt)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlOrs)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.balance)}}</td>
                    <td class="amt">{{divisionItem.utilizationRate}}%</td>
                    <td></td>
                </tr>
            </template>
        </template>

        <tr class="success" style="background-color: #befac6;">
            <th colspan="3" class="ctr">MOOE</th>
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

        <template v-for="(mooeItem, mooeIndex) in mooeList" style="width: 100%;">
            <tr class="officeTtl">
                <th class='ctr '>{{ mooeItem.mfo_name }}</th>
                <td></td>
                <td></td>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'garo'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'flr'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'sub-aro'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'other-docs'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'adjustments'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'grossAmt'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'ors'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(mooeItem,'balance'))}}</th>
                <th class="amt">{{calculateUtilization(calculateTotalPerMfo(mooeItem,'ors'),calculateTotalPerMfo(mooeItem,'grossAmt'))}}</th>
                <td></td>
            </tr>
            <template v-for="(officeItem, officeIndex) in mooeItem.value">
                <tr>
                    <td></td>
                    <th class='ctr'>{{ officeItem.office_name}}</th>
                    <th></th>
                    <th class='amt'>{{ formatAmount(calculateTotalPerOffice(officeItem,'garo')) }}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'flr'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'sub-aro'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'other-docs'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'adjustments'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'grossAmt'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'ors'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'balance'))}}</th>
                    <th class="amt">{{calculateUtilization(calculateTotalPerOffice(officeItem,'ors'),calculateTotalPerOffice(officeItem,'grossAmt'))}}</th>
                    <td></td>
                </tr>
                <tr v-for="divisionItem in officeItem.value">
                    <td></td>
                    <td></td>
                    <td class="amt">{{divisionItem.division_name}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlGaroAllotment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlFlr)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlSubAroAllotment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlOtherDocs)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlAdjustment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.grossAmt)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlOrs)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.balance)}}</td>
                    <td class="amt">{{divisionItem.utilizationRate}}%</td>
                    <td></td>
                </tr>
            </template>
        </template>


        <tr class="warning" style="background-color:#f8fabe;">
            <th colspan="3" class="ctr">CAPITAL OUTLAY</th>
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



        <template v-for="(coItem, coIndex) in capitalOutlayList" style="width: 100%;">
            <tr class="officeTtl">
                <th class='ctr '>{{ coItem.mfo_name }}</th>
                <td></td>
                <td></td>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'garo'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'flr'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'sub-aro'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'other-docs'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'adjustments'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'grossAmt'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'ors'))}}</th>
                <th class="amt">{{formatAmount(calculateTotalPerMfo(coItem,'balance'))}}</th>
                <th class="amt">{{calculateUtilization(calculateTotalPerMfo(coItem,'ors'),calculateTotalPerMfo(coItem,'grossAmt'))}}</th>
                <td></td>
            </tr>
            <template v-for="(officeItem, officeIndex) in coItem.value">
                <tr>
                    <td></td>
                    <th class='ctr'>{{ officeItem.office_name}}</th>
                    <th></th>
                    <th class='amt'>{{ formatAmount(calculateTotalPerOffice(officeItem,'garo')) }}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'flr'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'sub-aro'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'other-docs'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'adjustments'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'grossAmt'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'ors'))}}</th>
                    <th class="amt">{{formatAmount(calculateTotalPerOffice(officeItem,'balance'))}}</th>
                    <th class="amt">{{calculateUtilization(calculateTotalPerOffice(officeItem,'ors'),calculateTotalPerOffice(officeItem,'grossAmt'))}}</th>
                    <td></td>
                </tr>
                <tr v-for="divisionItem in officeItem.value">
                    <td></td>
                    <td></td>
                    <td class="amt">{{divisionItem.division_name}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlGaroAllotment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlFlr)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlSubAroAllotment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlOtherDocs)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlAdjustment)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.grossAmt)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.ttlOrs)}}</td>
                    <td class="amt">{{formatAmount(divisionItem.balance)}}</td>
                    <td class="amt">{{divisionItem.utilizationRate}}%</td>
                    <td></td>
                </tr>
            </template>
        </template>
        <tr style="background-color:#22cbf5;">
            <th class="ctr" colspan="3">Grand Total</th>
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
            <th class="ctr" colspan="3">MOOE + CO Total</th>
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
    table.hover-highlight tbody tr:hover {
        background-color: #ffff66;
        /* Highlight background color on hover */
    }



    ul {
        list-style: none;
    }

    .q {
        display: inline-block;
        margin-right: 10px;
        /* add spacing between items */
    }

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

    /* Style for the table-like structure */
    .table-like {
        display: flex;
        flex-direction: column;
        border: 1px solid #ccc;
        border-collapse: collapse;
        width: 100%;
        /* Adjust the width as needed */
    }

    /* Style for table header row */
    .table-header {
        display: flex;
        background-color: #f2f2f2;
        font-weight: bold;
    }

    /* Style for table rows */
    .table-row {
        display: flex;
        border-top: 1px solid #ccc;
    }

    /* Style for table cells (list items) */
    .table-cell {
        flex: 1;
        padding: 8px;
        border-right: 1px solid #ccc;
        text-align: center;
    }

    /* Remove right border for the last cell in each row */
    .table-row:last-child .table-cell {
        border-right: none;
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


                    return Object.keys(data).map((mfoKey) => {
                        let mfoData = data[mfoKey]
                        let d = Object.keys(mfoData).map((officeKey) => {
                            const obj = data[mfoKey][officeKey]
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
                        })

                        return {
                            mfo_name: mfoKey,
                            value: d,
                        }
                    })


                },
                // Rearrange the display objects make `RO` first in object
                reArrangeObjects(originalObject) {
                    if (originalObject) {

                        let roOfficeKey = '';
                        let objs = {}
                        for (let [index, data] of originalObject.entries()) {
                            if (data.office_name.toLowerCase() == 'ro') {
                                roOfficeKey = index
                            } else {
                                let key = Object.keys(objs).length
                                objs[key] = data
                            }
                        }
                        const rearrangedObject = [];
                        rearrangedObject[0] = originalObject[roOfficeKey];
                        Object.keys(objs).map((index) => {
                            let key = Object.keys(rearrangedObject).length
                            rearrangedObject[key] = objs[index]
                        })

                        return rearrangedObject
                    }
                    return [];

                },
                // get data from database
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

                                this.personelServicesLists = this.formatReturnedData(personnelServicesData)
                            }
                            if (capitalOutlayData) {
                                this.capitalOutlayList = this.formatReturnedData(capitalOutlayData)
                            }
                            if (mooeData) {
                                // this.mooeList = this.reArrangeObjects(this.formatReturnedData(mooeData))
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
                    if (arr.length > 0) {

                        return arr.reduce((total, item) => {
                            return total + item.value.reduce((ttl, it) => {
                                return ttl + this.getObectTtls(it.value, amtName)
                            }, 0)

                        }, 0)

                    }
                    // return arr.reduce((total, item) => {
                    //     return total + this.getObectTtls(item.value, amtName)
                    // }, 0)
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
                },
                calculateTotalPerMfo(data, amtName) {
                    let total = 0
                    Object.keys(data.value).map((key) => {
                        total += this.getObectTtls(data.value[key].value, amtName)
                    })
                    return total
                }

            },
            computed: {



            }
        })
    })
</script>
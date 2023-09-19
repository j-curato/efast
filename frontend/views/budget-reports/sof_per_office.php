<!-- <link href="/frontend/web/css/site.css" rel="stylesheet" /> -->
<?php

use kartik\date\DatePicker;


/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Status of Funds per Office/Division";
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

            <div class="col-sm-2" style="margin-top: 2.05rem;">
                <button class="btn btn-success" type="submit">Generate</button>
            </div>

        </div>

    </form>



    <table v-show='showTable' class="hover-highlight" style="display: none;">
        <thead>
            <tr>
                <th>Book</th>
                <th colspan="3" class="ctr">PARTICULARS</th>
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
        <tbody>

            <template v-for="bookData in finalData">
                <tr style="background-color: #f9fac8;">
                    <th>{{bookData.bookName}}</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class='amt'> {{formatAmount(bookData.bookGaroTtl)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookFlrTtl)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookSaaTtl)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookOtherDocsTtl)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookAdjustmentTtl)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookGrossAmt)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookOrsTtl)}}</th>
                    <th class='amt'> {{formatAmount(bookData.bookBalanceTtl)}}</th>
                    <th class='amt'> {{bookData.bookUtilization}}%</th>
                    <th></th>
                </tr>
                <template v-for="allotmentData in bookData.value">

                    <tr style="background-color: #bef1fa;">
                        <td></td>
                        <th> {{allotmentData.allotmentClassName}}</th>
                        <th></th>
                        <th></th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassGaroTtl)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassFlrTtl)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassSaaTtl)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassOtherDocsTtl)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassAdjustmentTtl)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassGrossAmt)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassOrsTtl)}}</th>
                        <th class='amt'> {{formatAmount(allotmentData.allotmentClassBalanceTtl)}}</th>
                        <th class='amt'> {{allotmentData.allotmentClassUtilization}}%</th>
                        <th></th>
                    </tr>

                    <template v-for="officeData in allotmentData.value">
                        <tr style="background-color: #ffe1d1;">
                            <th></th>
                            <th></th>
                            <th>{{officeData.officeName}}</th>
                            <th></th>
                            <th class='amt'> {{formatAmount(officeData.officeGaroTtl)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeFlrTtl)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeSaaTtl)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeOtherDocsTtl)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeAdjustmentTtl)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeGrossAmt)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeOrsTtl)}}</th>
                            <th class='amt'> {{formatAmount(officeData.officeBalanceTtl)}}</th>
                            <th class='amt'> {{officeData.officeUtilization}}%</th>
                            <th></th>
                        </tr>

                        <tr v-for="divisionsData in officeData.value">
                            <td></td>
                            <td></td>
                            <td></td>
                            <th>{{divisionsData.mfo_name}}</th>
                            <td class='amt'>{{formatAmount(divisionsData.ttlGaroAllotment)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.ttlFlr)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.ttlSubAroAllotment)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.ttlOtherDocs)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.ttlAdjustment)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.grossAmt)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.ttlOrs)}}</td>
                            <td class='amt'>{{formatAmount(divisionsData.balance)}}</td>
                            <td class='amt'>{{divisionsData.utilizationRate}}%</td>
                            <td></td>
                        </tr>


                    </template>



                </template>
            </template>
            <tr style="background-color: #fcdc9d;">

                <th colspan="4" class="ctr">MOOE + CO </th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoGaroTtl)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoFlrTtl)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoSaaTtl)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoOtherDocsTtl)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoAdjustmentTtl)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoGrossAmt)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoOrsTtl)}}</th>
                <th class='amt'> {{formatAmount(mooeCoTotal.mooeCoBalanceTtl)}}</th>
                <th class='amt'> {{mooeCoTotal.mooeCoUtilization}}%</th>
                <th></th>
            </tr>
            <tr style="background-color: #9dfcb6;">

                <th colspan="4" class="ctr">GRAND TOTAL </th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalGaroTtl)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalFlrTtl)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalSaaTtl)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalOtherDocsTtl)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalAdjustmentTtl)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalGrossAmt)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalOrsTtl)}}</th>
                <th class='amt'> {{formatAmount(grandTotals.grandTotalBalanceTtl)}}</th>
                <th class='amt'> {{grandTotals.grandTotalUtilization}}%</th>
                <th></th>
            </tr>
        </tbody>


    </table>
    <div class=" center-container" v-show='loading'>
        <pulse-loader :loading="loading" :color="color" :size="size"></pulse-loader>
    </div>
</div>
<style>
    table.hover-highlight tbody tr:hover {
        background-color: #edebeb;
        /* Highlight background color on hover */
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
$this->registerJsFile('@web/js/vue-spinner.min.js', ['position' => $this::POS_HEAD]);
?>
<script>
    var PulseLoader = VueSpinner.PulseLoader
    var RingLoader = VueSpinner.RingLoader
    $(document).ready(function() {
        new Vue({
            el: '#main',
            components: {
                'PulseLoader': PulseLoader,
                'RingLoader': RingLoader,
            },
            data: {
                finalData: [],
                year: '',
                personelServicesLists: [],
                mooeList: [],
                capitalOutlayList: [],
                loading: false,
                color: '#03befc',
                size: '20px',
                showTable: false,
                grandTotals: [],
                mooeCoTotal: [],


            },
            computed: {},
            methods: {
                formatReturnedData(obj) {
                    return Object.keys(obj).map((key) => {
                        const val = obj[key]
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
                            mfo_name: key,
                            value: val,
                            ttlGaroAllotment: ttlGaroAllotment,
                            ttlSubAroAllotment: ttlSubAroAllotment,
                            ttlAdjustment: ttlAdjustment,
                            ttlOrs: ttlOrs,
                            ttlOtherDocs: ttlOtherDocs,
                            grossAmt: grossAmt.toFixed(2),
                            balance: balance.toFixed(2),
                            utilizationRate: !isNaN(utilizationRate) ? utilizationRate.toFixed(2) : 0,
                            ttlFlr: ttlFlr.toFixed(2),

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
                            Object.keys(response.data).map((bookName) => {
                                let arr = []
                                let perAllotmentclassValue = Object.keys(response.data[bookName]).map((allotmentClassName) => {


                                    let perofficeValue = Object.keys(response.data[bookName][allotmentClassName]).map((officeName) => {

                                        let newOfficeObj = {
                                            officeName: officeName,
                                            value: this.formatReturnedData(response.data[bookName][allotmentClassName][officeName]),
                                        }
                                        return {
                                            ...newOfficeObj,
                                            ...this.calculateOfficeTotals(response.data[bookName][allotmentClassName][officeName])
                                        }
                                    })
                                    let newAllotmentObj = {
                                        allotmentClassName: allotmentClassName,
                                        value: perofficeValue,
                                    }
                                    return {
                                        ...newAllotmentObj,
                                        ...this.calculateTotalPerAllotmentClass(perofficeValue)
                                    }

                                })

                                let bookObj = {
                                    bookName: bookName,
                                    value: perAllotmentclassValue

                                }

                                this.finalData.push({

                                        ...bookObj,
                                        ...this.calculateTotalPerBook(perAllotmentclassValue)
                                    }

                                )
                            })
                            // console.log(this.finalData)
                            this.grandTotals = this.calculateGrandTotal()
                            this.mooeCoTotal = this.calculateSumOfCoAndMooe()
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
                        if (key.toLowerCase() !== 'garo' && key.toLowerCase() !== 'saa/sub-aro') {
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



                calculateOfficeTotals(obj) {
                    let garoTtl = 0
                    let saaTtl = 0
                    let flrTtl = 0
                    let otherDocsTtl = 0
                    let adjustmentTtl = 0
                    let orsTtl = 0
                    let balanceTtl = 0
                    Object.keys(obj).map((key) => {

                        Object.keys(obj[key]).map((key2) => {
                            if (obj[key][key2].document_recieve.toLowerCase() === 'garo') {
                                garoTtl += parseFloat(obj[key][key2].ttlAllotment)
                            } else if (obj[key][key2].document_recieve.toLowerCase() === "saa/sub-aro") {
                                saaTtl += parseFloat(obj[key][key2].ttlAllotment)
                            } else if (obj[key][key2].document_recieve.toLowerCase() === "for later release") {
                                flrTtl += parseFloat(obj[key][key2].ttlAllotment)
                            } else {
                                otherDocsTtl += parseFloat(obj[key][key2].ttlAllotment)
                            }
                            adjustmentTtl += parseFloat(obj[key][key2].ttlAdjustment)
                            orsTtl += parseFloat(obj[key][key2].ttlOrs)
                            balanceTtl += parseFloat(obj[key][key2].ttlBalance)
                        })

                    })
                    let gross =
                        garoTtl +
                        saaTtl +
                        flrTtl +
                        otherDocsTtl +
                        adjustmentTtl
                    let utilization = (orsTtl / gross) * 100
                    return {
                        officeGaroTtl: garoTtl.toFixed(2),
                        officeSaaTtl: saaTtl.toFixed(2),
                        officeOtherDocsTtl: otherDocsTtl.toFixed(2),
                        officeFlrTtl: flrTtl.toFixed(2),
                        officeAdjustmentTtl: adjustmentTtl.toFixed(2),
                        officeOrsTtl: orsTtl.toFixed(2),
                        officeBalanceTtl: balanceTtl.toFixed(2),
                        officeGrossAmt: gross.toFixed(2),
                        officeUtilization: !isNaN(utilization) ? utilization.toFixed(2) : 0

                    }
                },
                calculateTotalPerAllotmentClass(data) {
                    let allotmentClassAdjustmentTtl = 0
                    let allotmentClassBalanceTtl = 0
                    let allotmentClassFlrTtl = 0
                    let allotmentClassGaroTtl = 0
                    let allotmentClassGrossAmt = 0
                    let allotmentClassOrsTtl = 0
                    let allotmentClassOtherDocsTtl = 0
                    let allotmentClassSaaTtl = 0
                    data.map((item) => {
                        allotmentClassAdjustmentTtl += parseFloat(item.officeAdjustmentTtl)
                        allotmentClassBalanceTtl += parseFloat(item.officeBalanceTtl)
                        allotmentClassFlrTtl += parseFloat(item.officeFlrTtl)
                        allotmentClassGaroTtl += parseFloat(item.officeGaroTtl)
                        allotmentClassGrossAmt += parseFloat(item.officeGrossAmt)
                        allotmentClassOrsTtl += parseFloat(item.officeOrsTtl)
                        allotmentClassOtherDocsTtl += parseFloat(item.officeOtherDocsTtl)
                        allotmentClassSaaTtl += parseFloat(item.officeSaaTtl)
                    })
                    let utilization = (allotmentClassOrsTtl / allotmentClassGrossAmt) * 100
                    return {
                        allotmentClassAdjustmentTtl: allotmentClassAdjustmentTtl.toFixed(2),
                        allotmentClassBalanceTtl: allotmentClassBalanceTtl.toFixed(2),
                        allotmentClassFlrTtl: allotmentClassFlrTtl.toFixed(2),
                        allotmentClassGaroTtl: allotmentClassGaroTtl.toFixed(2),
                        allotmentClassGrossAmt: allotmentClassGrossAmt.toFixed(2),
                        allotmentClassOrsTtl: allotmentClassOrsTtl.toFixed(2),
                        allotmentClassOtherDocsTtl: allotmentClassOtherDocsTtl.toFixed(2),
                        allotmentClassSaaTtl: allotmentClassSaaTtl.toFixed(2),
                        allotmentClassUtilization: !isNaN(utilization) ? utilization.toFixed(2) : 0
                    }
                },
                calculateTotalPerBook(data) {
                    let bookAdjustmentTtl = 0
                    let bookBalanceTtl = 0
                    let bookFlrTtl = 0
                    let bookGaroTtl = 0
                    let bookGrossAmt = 0
                    let bookOrsTtl = 0
                    let bookOtherDocsTtl = 0
                    let bookSaaTtl = 0
                    data.map((item) => {
                        bookAdjustmentTtl += parseFloat(item.allotmentClassAdjustmentTtl)
                        bookBalanceTtl += parseFloat(item.allotmentClassBalanceTtl)
                        bookFlrTtl += parseFloat(item.allotmentClassFlrTtl)
                        bookGaroTtl += parseFloat(item.allotmentClassGaroTtl)
                        bookGrossAmt += parseFloat(item.allotmentClassGrossAmt)
                        bookOrsTtl += parseFloat(item.allotmentClassOrsTtl)
                        bookOtherDocsTtl += parseFloat(item.allotmentClassOtherDocsTtl)
                        bookSaaTtl += parseFloat(item.allotmentClassSaaTtl)
                    })
                    let utilization = (bookOrsTtl / bookGrossAmt) * 100
                    return {
                        bookAdjustmentTtl: bookAdjustmentTtl.toFixed(2),
                        bookBalanceTtl: bookBalanceTtl.toFixed(2),
                        bookFlrTtl: bookFlrTtl.toFixed(2),
                        bookGaroTtl: bookGaroTtl.toFixed(2),
                        bookGrossAmt: bookGrossAmt.toFixed(2),
                        bookOrsTtl: bookOrsTtl.toFixed(2),
                        bookOtherDocsTtl: bookOtherDocsTtl.toFixed(2),
                        bookSaaTtl: bookSaaTtl.toFixed(2),
                        bookUtilization: !isNaN(utilization) ? utilization.toFixed(2) : 0
                    }
                },
                calculateGrandTotal(amtName) {

                    let grandTotalAdjustmentTtl = 0
                    let grandTotalBalanceTtl = 0
                    let grandTotalFlrTtl = 0
                    let grandTotalGaroTtl = 0
                    let grandTotalGrossAmt = 0
                    let grandTotalName = 0
                    let grandTotalOrsTtl = 0
                    let grandTotalOtherDocsTtl = 0
                    let grandTotalSaaTtl = 0
                    this.finalData.map((item) => {
                        grandTotalAdjustmentTtl += parseFloat(item.bookAdjustmentTtl)
                        grandTotalBalanceTtl += parseFloat(item.bookBalanceTtl)
                        grandTotalFlrTtl += parseFloat(item.bookFlrTtl)
                        grandTotalGaroTtl += parseFloat(item.bookGaroTtl)
                        grandTotalGrossAmt += parseFloat(item.bookGrossAmt)
                        grandTotalName += parseFloat(item.bookName)
                        grandTotalOrsTtl += parseFloat(item.bookOrsTtl)
                        grandTotalOtherDocsTtl += parseFloat(item.bookOtherDocsTtl)
                        grandTotalSaaTtl += parseFloat(item.bookSaaTtl)
                    })
                    let utilization = (grandTotalOrsTtl / grandTotalGrossAmt) * 100
                    return {
                        grandTotalAdjustmentTtl: grandTotalAdjustmentTtl,
                        grandTotalBalanceTtl: grandTotalBalanceTtl,
                        grandTotalFlrTtl: grandTotalFlrTtl,
                        grandTotalGaroTtl: grandTotalGaroTtl,
                        grandTotalGrossAmt: grandTotalGrossAmt,
                        grandTotalName: grandTotalName,
                        grandTotalOrsTtl: grandTotalOrsTtl,
                        grandTotalOtherDocsTtl: grandTotalOtherDocsTtl,
                        grandTotalSaaTtl: grandTotalSaaTtl,
                        grandTotalUtilization: !isNaN(utilization) ? utilization.toFixed(2) : 0,
                    }
                },
                calculateSumOfCoAndMooe() {
                    let mooeCoAdjustmentTtl = 0
                    let mooeCoBalanceTtl = 0
                    let mooeCoFlrTtl = 0
                    let mooeCoGaroTtl = 0
                    let mooeCoGrossAmt = 0
                    let mooeCoOrsTtl = 0
                    let mooeCoOtherDocsTtl = 0
                    let mooeCoSaaTtl = 0
                    this.finalData.map((bookItem) => {
                        bookItem.value.map((allotmentClassItem) => {

                            if (allotmentClassItem.allotmentClassName.toLowerCase() === "maintenance and other operating expenses" || allotmentClassItem.allotmentClassName.toLowerCase() === "capital outlays") {
                                mooeCoAdjustmentTtl += parseFloat(allotmentClassItem.allotmentClassAdjustmentTtl)
                                mooeCoBalanceTtl += parseFloat(allotmentClassItem.allotmentClassBalanceTtl)
                                mooeCoFlrTtl += parseFloat(allotmentClassItem.allotmentClassFlrTtl)
                                mooeCoGaroTtl += parseFloat(allotmentClassItem.allotmentClassGaroTtl)
                                mooeCoGrossAmt += parseFloat(allotmentClassItem.allotmentClassGrossAmt)
                                mooeCoOrsTtl += parseFloat(allotmentClassItem.allotmentClassOrsTtl)
                                mooeCoOtherDocsTtl += parseFloat(allotmentClassItem.allotmentClassOtherDocsTtl)
                                mooeCoSaaTtl += parseFloat(allotmentClassItem.allotmentClassSaaTtl)
                            }
                        })
                    })
                    let utilization = (mooeCoOrsTtl / mooeCoGrossAmt) * 100
                    return {
                        mooeCoAdjustmentTtl: mooeCoAdjustmentTtl,
                        mooeCoBalanceTtl: mooeCoBalanceTtl,
                        mooeCoFlrTtl: mooeCoFlrTtl,
                        mooeCoGaroTtl: mooeCoGaroTtl,
                        mooeCoGrossAmt: mooeCoGrossAmt,
                        mooeCoOrsTtl: mooeCoOrsTtl,
                        mooeCoOtherDocsTtl: mooeCoOtherDocsTtl,
                        mooeCoSaaTtl: mooeCoSaaTtl,
                        mooeCoUtilization: !isNaN(utilization) ? utilization.toFixed(2) : 0,
                    }
                }

            },

        })
    })
</script>
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



    <form id="filter" @submit.prevent="filterForm">
        <div class="row">
            <div class="col-sm-3">
                <label for="from_reporting_period"> From Reporting Peiod</label>
                <?php

                echo DatePicker::widget([
                    'name' => 'yr',
                    'id' => 'yr',
                    'pluginOptions' => [
                        'minViewMode' => 'years',
                        'autoclose' => true,
                        'format' => 'yyyy',
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

    <!-- <div id="con"> -->

    <table>
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
            <th colspan="11">PERSONNEL SERVICES</th>
        </tr>
        <tr v-for="(item,index) in personelServicesLists">
            <td>{{item.mfo_name}}</td>
            <td class='amt'>{{formatAmount(item.ttlAllotment)}}</td>
            <td></td>
            <td class='amt'></td>
            <td class='amt'></td>
            <td class='amt'>{{formatAmount(item.ttlAdjustment)}}</td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlOrs)}}</td>
            <td class='amt'>{{formatAmount(item.ttlBalance)}}</td>
            <td></td>
            <td></td>
        </tr>
        <tr class="success">
            <th colspan="11">MOOE</th>
        </tr>
        <tr v-for="(item,index) in mooeList">
            <td>{{item.mfo_name}}</td>
            <td class='amt'>{{formatAmount(item.ttlAllotment)}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlAdjustment)}}</td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlOrs)}}</td>
            <td class='amt'>{{formatAmount(item.ttlBalance)}}</td>
            <td></td>
            <td></td>
        </tr>
        <tr class="warning">
            <th colspan="11">CAPITAL OUTLAY</th>
        </tr>
        <tr v-for="(item,index) in capitalOutlayList">
            <td>{{item.mfo_name}}</td>
            <td class='amt'>{{formatAmount(item.ttlAllotment)}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlAdjustment)}}</td>
            <td></td>
            <td class='amt'>{{formatAmount(item.ttlOrs)}}</td>
            <td class='amt'>{{formatAmount(item.ttlBalance)}}</td>
            <td></td>
            <td></td>
        </tr>
    </table>
</div>
<style>
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
<script>
    $(document).ready(function() {
        new Vue({
            el: '#main',
            data: {
                year: '',
                personelServicesLists: [],
                mooeList: [],
                capitalOutlayList: []
            },
            methods: {

                filterForm() {
                    const url = window.location.href
                    const data = {
                        year: $("#yr").val(),
                        _csrf: '<?= $csrf ?>'
                    }
                    const response = axios.post(url, data)
                        .then((response) => {
                            const mooeData = response.data["Maintenance and Other Operating Expenses"];
                            const capitalOutlayData = response.data["Capital Outlays"]
                            const personnelServicesData = response.data["Personnel Services"]
                            if (personnelServicesData) {
                                this.personelServicesLists = Object.keys(personnelServicesData).map(key => personnelServicesData[key]);
                            }
                            if (capitalOutlayData) {
                                this.capitalOutlayList = Object.keys(capitalOutlayData).map(key => capitalOutlayData[key]);
                            }
                            if (mooeData) {
                                this.mooeList = Object.keys(mooeData).map(key => mooeData[key]);
                            }


                        }).catch((error) => {
                            console.log(error)
                        })
                },
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
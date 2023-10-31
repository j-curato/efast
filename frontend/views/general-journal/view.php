<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JevPreparationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$period = DateTime::createFromFormat('Y-m', $model->reporting_period)->format('F Y');
$this->title = 'General Journal ' . $model->book->name . ' As of ' . $period;

$this->params['breadcrumbs'][] = ['label' => 'General Journals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="jev-preparation-index" id="main">
    <div class="container card">
        <p>
            <?= Yii::$app->user->can('update_ro_general_journal') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
            <button @click="exportFile" type='button' class="btn btn btn-success" style="margin:1rem;">Export</button>
        </p>
        <div id="con">
            <table style="margin-top:30px" id='data-table'>
                <thead>
                    <tr>
                        <th colspan="6" class="text-center">
                            <h5 class="font-weight-bold">GENERAL JOURNAL</h5>
                            <h6><?= !empty($period) ? $period : '' ?></h6>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <span>
                                Entity Name:
                            </span>
                            <span>
                                DEPARTMENT OF TRADE AND INDUSTRY - CARAGA
                            </span>
                        </th>
                        <th colspan="3">
                            <span>
                                Book:
                            </span>
                            <span>
                                <?= $model->book->name ?>
                            </span>
                        </th>

                    </tr>
                    <tr>
                        <th rowspan=" 2" class="text-center">Date</th>
                        <th rowspan="2" class="text-center">JEV No.</th>
                        <th rowspan="2" class="text-center">Particulars</th>
                        <th rowspan="2" class="text-center">UACS Object Code</th>
                        <th colspan="3" class="text-center">Amount</th>
                    </tr>
                    <tr>
                        <th class='text-center'>Debit</th>
                        <th class='text-center'>Credit</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item,index) in formattedItems">
                        <tr>
                            <td>{{item.date}}</td>
                            <td>{{item.jevNum}}</td>
                            <td>{{item.particular}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr v-for="subItem in item.subItems">
                            <td></td>
                            <td></td>
                            <td>{{subItem.account_title}}</td>
                            <td>{{subItem.object_code}}</td>
                            <td class="text-right">{{formatAmount(subItem.debit)}}</td>
                            <td class="text-right">{{formatAmount(subItem.credit)}}</td>
                        </tr>
                    </template>
                </tbody>
                <tr>
                    <td colspan="3" class="border-0 "></td>
                    <td colspan="3" class="text-center pt-5 border-0">
                        <u> <b class="text-uppercase mt-1">CHARLIE C. DECHOS, CPA</b></u>
                        <p>Accountant III</p>
                    </td>
                </tr>
            </table>

        </div>


    </div>

</div>

<style>
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }


    table {
        border: 1px solid black;
        width: 100%;
    }

    .container {
        margin-top: 5px;
        position: relative;
        padding: 10px;

    }



    @media print {

        table,
        th,
        td {
            padding: 8px;
            background-color: white;
        }

        table {
            margin-top: 0px;
        }

        .btn,
        .main-footer {
            display: none;
        }
    }
</style>

<?php
$csrfToken = Yii::$app->request->csrfToken;
$items = $model->getItems();
?>
<script>
    $(document).ready(function() {
        new Vue({
            el: '#main',

            data: {
                items: <?= !empty($items) ? json_encode($items) : [] ?>,
            },
            mounted() {},
            computed: {
                formattedItems() {
                    return this.formatItems()
                }
            },
            methods: {
                formatItems() {
                    return Object.keys(this.items).map((key) => {
                        let obj = this.items[key]
                        let particular = ''
                        let jevNum = ''
                        let date = ''
                        if (obj.length > 0) {
                            particular = obj[0].explaination
                            jevNum = obj[0].jev_number
                            date = obj[0].date
                        }
                        return {
                            particular: particular,
                            jevNum: jevNum,
                            date: date,
                            subItems: obj
                        }
                    })
                },
                formatAmount(amount) {
                    amount = parseFloat(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString()
                    }
                    return 0;
                },
                exportFile() {
                    const apiUrl = window.location.pathname + '?r=general-journal/export'
                    const data = {
                        _csrf: '<?= $csrfToken ?>',
                        id: <?= $model->id ?>
                    }
                    axios.post(apiUrl, data)
                        .then(response => {
                            window.open(response.data)
                        })
                        .catch(error => {
                            console.log(error)
                        })
                }

            },

        })
    })
</script>
<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

SweetAlertAsset::register($this);
$this->title = $model->aoq_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-aoq-view">

    <div class="card p-2">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

            <?php
            // if (Yii::$app->user->can('po_procurement_admin') || Yii::$app->user->can('ro_procurement_admin')) {
            $btn_color = $model->is_cancelled ? 'btn btn-success' : 'btn btn-danger';
            $cncl_txt = $model->is_cancelled ? 'UnCancel' : 'Cancel';
            if (!$model->is_cancelled) {
                echo  Html::a($cncl_txt, ['cancel', 'id' => $model->id], [
                    'class' => $btn_color,
                    'id' => 'cancel',
                    'type' => 'button'

                ]);
            }
            // }
            echo   Html::a('RFQ Link ', ['pr-rfq/view', 'id' => $model->pr_rfq_id], ['class' => 'btn btn-link ', 'style' => 'margin:3px'])
            ?>
        </p>
    </div>

    <div id="main">
        <div class="card p-2">


            <?php
            $for_print = ArrayHelper::index($aoq_items_query, null, 'rfq_item_id');
            $result = ArrayHelper::index($aoq_items_query, null, 'rfq_item_id');
            $qqq = ArrayHelper::index($aoq_items_query, 'payee', [function ($element) {
                return $element['rfq_item_id'];
            }]);
            $aoq_items_array  = ArrayHelper::index($aoq_items_query, 'payee');
            $header_count = count($aoq_items_array) * 2 + 6;



            ?>
            <div>

                <table>
                    <thead>
                        <tr>
                            <th colspan="<?= $header_count ?>" style='text-align:center;border:none;'>
                                <span>
                                    Department of Trade and Industry - Caraga
                                </span>
                                <br>
                                <span>
                                    Regional Office XIII
                                </span>
                                <br>
                                <span>
                                    Butuan City
                                </span>

                            </th>
                        </tr>
                        <tr>
                            <th colspan="<?= $header_count ?>" style='text-align:center;border:none;'>
                                <span>
                                    ABSTRACT OF CANVASS AND ACTION OF AWARDS
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th colspan="<?= $header_count ?>" style='padding:0;border:none;'>
                                <?php
                                $nopToDate = $model->rfq->getNopToDate();
                                $unformatDate =  !empty($nopToDate) ? $nopToDate : $model->rfq->deadline;
                                $date =   DateTime::createFromFormat('Y-m-d H:i:s', $unformatDate);
                                echo $date->format('F d, Y');
                                ?>
                                <span style="float: right;">
                                    <?= $model->aoq_number ?>
                                </span>
                            </th>
                        </tr>
                        <tr>
                            <th rowspan="2" class="center">Item No.</th>
                            <th rowspan="2" class="center">Qty</th>
                            <th rowspan="2" class="center">Unit</th>
                            <th rowspan="2" class="center">Description</th>
                            <th rowspan="2" class="center">Abc</th>

                            <th v-for="payee in payees" colspan="2" class="center">
                                {{payee}}
                            </th>
                            <th>Lowest</th>
                        </tr>
                        <tr>
                            <template v-for="payee in payees">
                                <th class='center'>Unit Cost</th>
                                <th class='center'>Gross Amount</th>
                            </template>
                            <td></td>
                        </tr>
                    </thead>
                    <tr v-for="(item,idx) in displayItems">

                        <td>{{idx +1}}</td>
                        <td>{{item.quantity}}</td>
                        <td>{{item.unit_of_measure}}</td>
                        <td> <b>{{item.description}} </b><br> {{item.specification}}</td>
                        <td>{{formatAmount(item.unit_cost)}}</td>
                        <template v-for="payee in payees">
                            <td class="center">
                                <p v-if="getPayeeAmtPerItem(item,payee)!=0">
                                    {{formatAmount(getPayeeAmtPerItem(item,payee))}}
                                </p>
                                <p v-else=>-</p>
                            </td>
                            <td class="center">
                                <p v-if="getPayeeAmtPerItem(item,payee)!=0">
                                    {{formatAmount(getPayeeAmtPerItem(item,payee) *item.quantity)}}
                                </p>
                                <p v-else=>-</p>
                            </td>
                        </template>

                        <td>
                            <p v-for="rm in item.lowests">

                                {{rm}}
                            </p>
                        </td>

                    </tr>
                    <tr>
                        <th colspan=""></th>
                        <th colspan=""></th>
                        <th colspan=""></th>
                        <th colspan=""></th>
                        <th colspan="" class="center">
                            Total
                        </th>
                        <template v-for="payee in payees">
                            <td class="center">
                                <!-- {{payee}} -->
                            </td>
                            <td class="center">
                                {{ formatAmount(calculatePayeeGrossTotal(payee))}}
                            </td>
                        </template>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5">

                        </td>
                        <template v-for="payee in payees">
                            <td colspan="2" class="center">
                                <span v-for="itm in getPayeeRemarks(payee)">{{itm}} <br></span>
                            </td>
                        </template>
                        <td></td>

                    </tr>
                    <tr>
                        <td colspan="<?= $header_count ?>" style='padding:6px'> <?= $model->rfq->purchaseRequest->purpose ?? ''; ?></td>
                    </tr>
                    <tfoot>
                        <tr>
                            <td colspan="<?= $header_count ?>" style='border:none;padding-top:0'>
                                Based on the above abstract of canvass, it is recommended that the award be made to the Lowest Calculated and Responsive Bidder,
                            </td>
                        </tr>
                        <tr>
                            <td colspan="<?= $header_count ?>" class='no-border' style='padding-top:2em'>

                                <div class="bac-members">

                                    <?php
                                    $i = 1;
                                    foreach ($bac_compositions as $val) {
                                        if (strtolower($val['position']) === 'member') {
                                            $member_name =  strtoupper($val['employee_name']);
                                            $member_position = ucwords($val['position']);
                                            echo "<div style='text-align:center'>
                                            <span style='text-decoration:underline;font-weight:bold'>$member_name</span>
                                            <br>
                                            <span >$member_position</span>
                                    </div>";
                                        }
                                    };
                                    ?>
                                </div>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="<?= $header_count  ?>" class='no-border'>
                                <div style="float: left;margin-left:20%;text-align:center;margin-top:2em">
                                    <?php
                                    $search_vice =   array_search('vice-chairperson', array_column($bac_compositions, 'position'));;
                                    // var_dump($bac_compositions);
                                    // var_dump($search_vice);
                                    // $vice_chairperson = '';
                                    // if (!empty($search_vice)) {
                                    //     $vice_chairperson =   strtoupper($bac_compositions[$search_vice]['employee_name']);
                                    // }
                                    $vice_chairperson =   strtoupper($bac_compositions[$search_vice]['employee_name'] ?? '');
                                    echo  "<span style='text-decoration:underline;font-weight:bold'>{$vice_chairperson}</span>";
                                    echo '<br>';
                                    echo 'Vice-Chairperson';
                                    ?>
                                </div>
                                <div style="float: right;margin-right:20%;text-align:center;margin-top:2em">
                                    <?php
                                    $search_chairperson =  array_search('chairperson', array_column($bac_compositions, 'position'));
                                    $chairperson =  '';
                                    // var_dump(array_column($bac_compositions, 'position'));
                                    // var_dump($search_chairperson);

                                    // if (!empty($search_chairperson)) {
                                    $chairperson =   strtoupper($bac_compositions[$search_chairperson]['employee_name'] ?? '');
                                    // }
                                    echo  "<span style='text-decoration:underline;font-weight:bold'>{$chairperson}</span>";
                                    echo '<br>';
                                    echo 'Chairperson';
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
        <div class="card p-2">
            <table class="links_table table table-hover">
                <thead>
                    <tr class="table-info">
                        <th colspan="3" class="text-center">PO Links</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    foreach ($model->getPoLinks() as $val) {
                        $isCancelled = $val['is_cancelled'] ? 'Cancelled' : '';
                        echo "<tr>
                                <td>{$val['po_number']}</td>
                                <td>" . Html::a('PO Link ', ['pr-purchase-order/view', 'id' => $val['id']], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) . "</td>
                                <td>$isCancelled</td>
                            </tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    .no-border {
        border: 0;
    }

    td.fooed {
        position: relative;

        display: table-cell;
        vertical-align: text-bottom;
    }

    .foo {
        position: relative;
        top: 2rem;
        left: 0px;
        right: 0px;
        bottom: 0px;
        display: table-cell;
        text-align: center;
        height: 100%;


    }

    .center {
        text-align: center;
    }


    .amount {
        text-align: center;
    }

    .bac-members {
        display: flex;
        justify-content: space-evenly;

    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 2rem;
        border: 1px solid black;
    }

    .container {
        padding: 3em;
    }

    .links_table td,
    .links_table th {
        border: none;
    }

    tfoot {
        display: table-row-group
    }

    @media print {
        .links_table {
            display: none;
        }

        .main-footer {
            display: none;
        }

        .btn {
            display: none;
        }

        .container {
            padding: 0;
            min-width: 90%;
        }

        table {
            width: 100%;
        }

        th,
        td {
            padding: 6px;
        }

        .main-footer {
            display: none;
        }

        @page {
            size: landscape;
        }
    }
</style>

<?php
$this->registerJsFile(yii::$app->request->baseUrl . "/frontend/web/js/globalFunctions.js", ['depends' => [\yii\web\JqueryAsset::class]]);
SweetAlertAsset::register($this);
?>
<script>
    $(document).ready(function() {
        $("#cancel").on('click', (e) => {

            e.preventDefault();
            console.log('qwe')
            let ths = $(e.target)
            let link = ths.attr('href');
            swal({
                title: "Are you sure you want to " + ths.text() + " this AOQ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Confirm',
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
                closeOnCancel: true,
                width: "500px",
                height: "500px",
            }, function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: link,
                        method: 'POST',
                        data: {
                            _csrf: "<?= Yii::$app->request->getCsrfToken() ?>"
                        },
                        success: function(response) {
                            const res = JSON.parse(response)
                            if (!res.error) {
                                swal({
                                    title: 'Success',
                                    type: 'success',
                                    button: false,
                                    timer: 3000,
                                }, function() {
                                    location.reload(true)
                                })
                            } else {
                                swal({
                                    title: 'Error',
                                    type: 'error',
                                    text: res.message,
                                    button: false,
                                    timer: 5000,
                                })
                            }
                        },
                        error: function(error) {
                            console.error('Cancel failed:', error);
                        }
                    });
                }
            })

        });
    })
</script>
<script>
    $(document).ready(function() {
        new Vue({
            el: "#main",
            data: {
                items: <?= json_encode($model->getViewItems()) ?>,
                payees: <?= json_encode(ArrayHelper::getColumn($model->getItemPayees(), 'payee')) ?>,
                displayItems: []
            },
            mounted() {
                this.reformatItems()
                console.log(this.items)
            },
            methods: {

                reformatItems() {
                    let remarks = []
                    this.displayItems = Object.keys(this.items).map((item, q) => {
                        let payeeKeys = Object.keys(this.items[item])
                        let lowests = []
                        Object.keys(this.items[item]).map((payeeName) => {
                            if (parseInt(this.items[item][payeeName].is_lowest) === 1) {
                                lowests.push(payeeName)
                            }
                            if (this.items[item][payeeName].remark) {
                                // remarks[q].push(this.items[item][q].remark)
                            }

                        })

                        let r = {
                            description: this.items[item][payeeKeys[0]].description,
                            specification: this.items[item][payeeKeys[0]].specification.replace(/<br\s*\/?>/g, '\n'),
                            quantity: this.items[item][payeeKeys[0]].quantity,
                            unit_of_measure: this.items[item][payeeKeys[0]].unit_of_measure,
                            unit_cost: this.items[item][payeeKeys[0]].unit_cost,

                            lowests: lowests
                        }
                        return {
                            ...r,
                            ...this.items[item],
                        }
                    })
                    // console.log(this.displayItems)
                },
                getPayeeAmtPerItem(item, payee) {


                    if (item[payee]) {
                        if (parseFloat(item[payee].amount) !== 0) {
                            return item[payee].amount
                        }
                    }
                    return 0

                },
                calculatePayeeGrossTotal(payeeName) {
                    return this.displayItems.reduce((total, item) => {

                        let x = 0
                        if (item[payeeName]) {
                            x = parseFloat(item[payeeName].amount) * parseFloat(item[payeeName].quantity)
                        }
                        return total + x
                    }, 0);

                },
                getPayeeRemarks(payeeName) {
                    let remarks = []
                    this.displayItems.reduce((total, item, y) => {
                        if (item[payeeName]) {
                            if (item[payeeName].remark) {
                                remarks.push(`${item[payeeName].remark} Item No. ${y+1}`)
                            }
                        }
                    }, []);

                    // console.log(remarks)
                    return remarks

                },
                formatAmount(amount) {
                    amount = parseInt(amount)
                    if (typeof amount === 'number' && !isNaN(amount)) {
                        return amount.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                },

            }

        })
    })
</script>
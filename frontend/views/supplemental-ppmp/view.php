<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SupplementalPpmp */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Supplemental Ppmps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
function GetEmployeeData($id)
{
    return Yii::$app->db->createCommand("SELECT employee_id,employee_name,position FROM employee_search_view WHERE employee_id = :id")
        ->bindValue(':id', $id)
        ->queryOne();
}
$prepared_by = '';
$reviewed_by = '';
$approved_by = '';
$certified_funds_available_by = '';
if (!empty($model->fk_prepared_by)) {

    $prepared_by = GetEmployeeData($model->fk_prepared_by);
}
if (!empty($model->fk_reviewed_by)) {

    $reviewed_by = GetEmployeeData($model->fk_reviewed_by);
}
if (!empty($model->fk_approved_by)) {

    $approved_by = GetEmployeeData($model->fk_approved_by);
}
if (!empty($model->fk_certified_funds_available_by)) {

    $certified_funds_available_by = GetEmployeeData($model->fk_certified_funds_available_by);
}

?>
<div class="supplemental-ppmp-view" id="mainVue">

    <div class="card" style="background-color: white;padding:1rem;">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>


        <table class="table" style="max-width: 70%;" id="head_table">

            <tbody>
                <tr>

                    <th>Serial Number:</th>
                    <td><?= $model->serial_number ?></td>

                    <th>Budget Year :</th>
                    <td><?= $model->budget_year ?></td>

                    <th>CSE/NON-CSE:</th>
                    <td><?= strtoupper(str_replace('_', '-', $model->cse_type)) ?></td>
                </tr>
                <tr>

                    <th>Office:</th>
                    <td><?= strtoupper($model->office->office_name) ?></td>




                    <th>Division :</th>
                    <td><?= strtoupper($model->divisionName->division) ?></td>

                    <th>Division/Program/Unit:</th>
                    <td><?= strtoupper($model->divisionProgramUnit->name) ?></td>
                </tr>

            </tbody>




        </table>



        <table class="">
            <tbody>
                <?php
                $colspan = 1;
                if ($model->cse_type === 'cse') {
                    $total = 0;
                    $colspan = 10;
                    echo "<tr class='head'>
                            <th>Stock</th>
                            <th>Unit of Measure</th>
                            <th>Amount</th>
                            <th>Total Qty</th>
                            <th>Gross Amount</th>
                            <th>January Qty</th>
                            <th>February Qty</th>
                            <th>March Qty</th>
                            <th>Q1 Qty</th>
                            <th>April Qty</th>
                            <th>May Qty</th>
                            <th>June Qty</th>
                            <th>Q2 Qty</th>
                            <th>July Qty</th>
                            <th>August Qty</th>
                            <th>September Qty</th>
                            <th>Q3 Qty</th>
                            <th>October Qty</th>
                            <th>November Qty</th>
                            <th>December Qty</th>
                            <th>Q4 Qty</th>
                        </tr>";

                    $cseGrandTtl = 0;
                    foreach ($items as $item) {
                        $amt_dsp = number_format($item['amount'], 2);
                        $total += floatval($item['amount']);
                        $q1_qty =
                            intval($item['jan_qty']) +
                            intval($item['feb_qty']) +
                            intval($item['mar_qty']);
                        $q2_qty = intval($item['apr_qty']) +
                            intval($item['may_qty']) +
                            intval($item['jun_qty']);
                        $q3_qty = intval($item['jul_qty']) +
                            intval($item['aug_qty']) +
                            intval($item['sep_qty']);
                        $q4_qty = intval($item['oct_qty']) +
                            intval($item['nov_qty']) +
                            intval($item['dec_qty']);
                        $total_qty =  $q1_qty + $q2_qty + $q3_qty + $q4_qty;
                        $gross_amount  = $total_qty * floatval($item['amount']);
                        $cseGrandTtl += $gross_amount;
                        echo "<tr class='r'>
                                <td>{$item['stock_title']}</td>
                                <td>{$item['unit_of_measure']}</td>
                                <td>{$amt_dsp}</td>
                                <td>{$total_qty}</td>
                                <td>" . number_format($gross_amount, 2) . "</td>
                                <td>{$item['jan_qty']}</td>
                                <td>{$item['feb_qty']}</td>
                                <td>{$item['mar_qty']}</td>
                                <td>{$q1_qty}</td>
                                <td>{$item['apr_qty']}</td>
                                <td>{$item['may_qty']}</td>
                                <td>{$item['jun_qty']}</td>
                                <td>{$q2_qty}</td>
                                <td>{$item['jul_qty']}</td>
                                <td>{$item['aug_qty']}</td>
                                <td>{$item['sep_qty']}</td>
                                <td>{$q3_qty}</td>
                                <td>{$item['oct_qty']}</td>
                                <td>{$item['nov_qty']}</td>
                                <td>{$item['dec_qty']}</td>
                                <td>{$q4_qty}</td>
                           </tr>";
                    }
                    echo "   <tr class='ttl'> <th colspan='2'>Total</th>
                <td >" . number_format($total, 2) . "</td> 
                <td></td>
                <td >" . number_format($cseGrandTtl, 2) . "</td> 
                </tr>
          ";
                } else if ($model->cse_type === 'non_cse') {
                    $colspan = 5;
                    $grand_total = 0;
                    echo "<tr  class='head'>
                        <th>Budget Year</th>
                        <th>MFO/PAP Code</th>
                        <th>Activity Name</th>
                        <th>Item Code</th>
                        <th>Stock Name</th>
                        <th>Specification</th>
                        <th>Unit of Measure</th>
                        <th>Quantity</th>
                        <th>Mode of Procurement</th>
                        <th>Early Procurement?</th>
                        <th style='text-align:right'>Amount</th>
                    </tr>";
                    foreach ($items as $item) {
                        $budget_year = $item['budget_year'];
                        $cse_type = $item['cse_type'];
                        $mfo_code = $item['mfo_code'];
                        $mfo_name = $item['mfo_name'];
                        $activity_name = $item['activity_name'];
                        $bac_code = $item['bac_code'];
                        $stock_title = $item['stock_title'];
                        $description = $item['description'];
                        $early_procurement = $item['early_procurement'];
                        $unit_of_measure = $item['unit_of_measure'];
                        $quantity = $item['quantity'];
                        $mode_of_procurement_name = !empty($item['mode_name']) ? $item['mode_name'] : '';
                        $amount = number_format($item['amount'], 2);
                        $grand_total += floatval($item['amount']);

                        echo "<tr class='r'>
                            <td>$budget_year</td>
                            <td>$mfo_code - $mfo_name</td>
                            <td>$activity_name</td>
                            <td>$bac_code</td>
                            <td>$stock_title</td>
                            <td>$description</td>
                            <td>$unit_of_measure</td>
                            <td>$quantity</td>
                            <td>$mode_of_procurement_name</td>
                            <td>$early_procurement</td>
                            <td style='text-align:right'>$amount</td>
                            </tr>";
                    }
                    echo "<tr class='ttl'>
                            <th colspan='9' class='center'>Total</th>
                            <th  style='text-align:right'>" . number_format($grand_total, 2) . "</th>
                        </tr>";
                }
                ?>



                <tr>
                    <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">


                        <u class="font-weight-bold text-uppercase "><?= !empty($prepared_by) ? $prepared_by['employee_name'] : ''  ?></u>
                        <br>
                        <span><?= !empty($prepared_by) ? $prepared_by['position'] : ''  ?></span>
                        <br>
                        <span>Prepared By</span>

                    </td>
                    <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">
                        <u class="font-weight-bold text-uppercase"><?= !empty($reviewed_by) ? $reviewed_by['employee_name'] : ''  ?></u>
                        <br>
                        <span><?= !empty($reviewed_by) ? $reviewed_by['position'] : ''  ?></span>
                        <br>
                        <span>Reviewed By</span>
                    </td>

                </tr>

                <tr>
                    <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">
                        <u class="font-weight-bold text-uppercase"><?= !empty($certified_funds_available_by) ? $certified_funds_available_by['employee_name'] : ''  ?></u>
                        <br>
                        <span><?= !empty($certified_funds_available_by) ? $certified_funds_available_by['position'] : ''  ?></span>
                        <br>
                        <span>Certified Funds Available By</span>

                    </td>
                    <td colspan="<?= $colspan ?>" style="text-align: center;padding-top:7rem">
                        <u class="font-weight-bold text-uppercase"><?= !empty($approved_by) ? $approved_by['employee_name'] : ''  ?></u>
                        <br>
                        <span><?= !empty($approved_by) ? $approved_by['position'] : ''  ?></span>
                        <br>
                        <span>Approved By</span>

                    </td>
                </tr>
                <?= "</tbody>" ?>
        </table>
    </div>

    <div class="card links">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="3" class="text-center table-primary">Items Purchase Requests</th>
                </tr>
                <tr>
                    <th>Action</th>
                    <th>Stock</th>
                    <th>Specification</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item,idx) in items">
                    <tr>
                        <td>
                            <button @click="getPurchaseRequests(item)" class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#collapse'+idx" aria-expanded="true" aria-controls="collapseExample">
                                Show Purchase Requests
                            </button>
                        </td>
                        <td>{{item.stock_title}}</td>
                        <td>{{item.description}}</td>
                    </tr>
                    <tr>
                        <td colspan="3">

                            <div class="collapse" :id="'collapse'+idx">
                                <div class="card p-2">
                                    <table>
                                        <thead>
                                            <tr class="table-info">
                                                <th colspan="10" class="text-center">Purchase Requests</th>
                                            </tr>
                                            <tr>

                                                <th class="text-center">PR No.</th>
                                                <th class="text-center">Specification</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-center">Unit Cost</th>
                                                <th class="text-center">Link</th>






                                            </tr>

                                        </thead>
                                        <tbody>

                                            <tr v-for="pr in  itemPrs[item.id]">
                                                <td class="text-center">{{pr.pr_number}}</td>
                                                <td class="text-center">{{pr.specification}}</td>
                                                <td class="text-center">{{pr.quantity}}</td>
                                                <td class="text-center">{{formatAmount(pr.unit_cost)}}</td>
                                                <td class="text-center">
                                                    <a :href="'?r=pr-purchase-request/view&id='+pr.id" class="btn btn-link">Link</a>
                                                </td>

                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

    </div>
</div>

<style>
    #head_table th {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    .center {
        text-align: center;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 1rem;

    }

    .head {
        border-top: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .r {
        border-left: 1px solid black;
        border-right: 1px solid black;
    }

    .ttl {
        border-bottom: 1px solid black;
        border-left: 1px solid black;
        border-right: 1px solid black;
    }


    @media print {

        .links,
        .btn,
        .main-footer {
            display: none;
        }


    }
</style>

<script>
    $(document).ready(function() {


        new Vue({
            el: "#mainVue",
            data: {
                items: <?= json_encode($items) ?>,
                itemPrs: {}
            },
            mounted() {
                console.log(this.items)
            },
            methods: {
                getPurchaseRequests(item) {
                    if (this.itemPrs[item.id]) {
                        return

                    }

                    let url = "?r=supplemental-ppmp/item-prs"
                    const data = {
                        _csrf: "<?= Yii::$app->request->getCsrfToken() ?>",
                        id: item.id,
                        type: '<?= $model->cse_type ?>'
                    }
                    axios.post(url, data)
                        .then(res => {
                            Vue.set(this.itemPrs, item.id, res.data);

                        })
                        .catch(err => {
                            console.log(err)
                        })
                },
                formatAmount(unitCost) {
                    unitCost = parseFloat(unitCost)
                    if (typeof unitCost === 'number' && !isNaN(unitCost)) {
                        return unitCost.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                },
            }
        })
    })
</script>
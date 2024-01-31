<?php

use aryelds\sweetalert\SweetAlertAsset;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */

$this->title = $model->check_or_ada_no;
$this->params['breadcrumbs'][] = ['label' => 'Cash Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="cash-disbursement-view d-none" id="mainVue">

    <div class="card p-2">

        <span>
            <?= Html::a('<i class="fa fa-plus"></i> Create ', ['create'], ['class' => 'btn btn-success']) ?>
            <?php
            if ($model->is_cancelled != true) {
                echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
            }
            echo "<input type='text' id='cancel_id' value='$model->id' style='display:none;'/>";
            $t = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/view&id=$model->dv_aucs_id";
            // echo  Html::a('DV Link', $t, ['class' => 'btn btn-info ', 'style' => 'margin:3px']);
            if (!empty($model->jevPreparation)) {
                $jev_link = yii::$app->request->baseUrl . "/index.php?r=jev-preparation/view&id={$model->jevPreparation->id}";
                echo  Html::a('JEV ', $jev_link, ['class' => 'btn btn-warning ', 'style' => 'margin:3px']);
            }
            if (!empty($model->transmittal->transmittal_id)) {
                $transmittal_link = yii::$app->request->baseUrl . "/index.php?r=transmittal/view&id={$model->transmittal->transmittal_id}";
                echo  Html::a('Transmittal ', $transmittal_link, ['class' => 'btn btn-link ', 'style' => 'margin:3px']);
            }
            ?>
            <?= !empty($model->sliie->id) ? Html::a('SLIIE ', ['sliies/view', 'id' => $model->sliie->id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) : '' ?>
            <?= !empty($model->lddapAda->id) ? Html::a('LDDAP-ADA ', ['lddap-adas/view', 'id' => $model->lddapAda->id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) : '' ?>
            <?= !empty($acic_id) ? Html::a('ACIC ', ['acics/view', 'id' => $acic_id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) : '' ?>
            <?= !empty($rci_id) ? Html::a('RCI ', ['rci/view', 'id' => $rci_id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) : '' ?>

        </span>
    </div>
    <div class="card p-2">

        <div class=" row">
            <div class="col-5">
                <table id="check_details_tbl" class="table">
                    <th colspan="4" class="text-center">
                        Disbursement Details
                    </th>
                    <tr>
                        <th>Reporting Period:</th>
                        <th><?= $model->reporting_period ?></th>
                        <th>Book:</th>
                        <th><?= $model->book->name ?></th>

                    </tr>
                    <tr>
                        <th>Mode of Payment:</th>
                        <th><?= $model->modeOfPayment->name ?? '' ?></th>
                        <th>Check No.:</th>
                        <th><?= $model->check_or_ada_no ?></th>
                    </tr>
                    <tr>
                        <th>Issuance Date: </th>
                        <th><?= $model->issuance_date ?></th>
                        <th>ADA No.:</th>
                        <th><?= $model->ada_number ?></th>
                    </tr>
                    <tr>
                        <th>Begin timer:</th>
                        <th><?= date('h:i A', strtotime($model->begin_time)) ?></th>
                        <th>Out Time:</th>
                        <th><?= date('h:i A', strtotime($model->out_time)) ?></th>
                    </tr>
                </table>
            </div>
            <div class="col-3">
                <table id="summary_tbl">
                    <th colspan="3" class="text-center">
                        Summary per UACS
                    </th>
                    <tr>

                        <?php
                        foreach ($summary as $sum) {
                            echo "<tr>
                                <th>{$sum['general_ledger']}</th>
                                <th class='amt'>" . number_format($sum['total'], 2) . "</th>
                            </tr>";
                        }
                        ?>
                    </tr>

                </table>
            </div>

        </div>



    </div>
    <div class="card p-2">
        <table class="  table table-hover">
            <thead>
                <tr class="table-info">
                    <th colspan="11" class="text-center">DV'S</th>
                </tr>
                <th>Book</th>
                <th>DV No.</th>
                <th>Particular</th>
                <th>Payee</th>
                <th>Bank & Account No.</th>
                <th>ORS</th>
                <th>UACS</th>
                <th>Amount Disbursed</th>
                <th>Withholding Tax</th>
                <th>Gross Amount</th>
                <th class="hdn">Link</th>
            </thead>
            <tbody>


                <?php
                //     $grndTtlAmtDisbursed = 0;
                //     $grndTtlTax = 0;
                //     $grndGrossAmt = 0;
                //     foreach ($items as $itm) {
                //         $grndTtlAmtDisbursed += floatval($itm['ttlAmtDisbursed']);
                //         $grndTtlTax += floatval($itm['ttlTax']);
                //         $grndGrossAmt += floatval($itm['grossAmt']);
                //         echo "<tr>
                // <td>{$itm['book_name']}</td>
                //     <td>{$itm['dv_number']}</td>
                //     <td>{$itm['particular']}</td>
                //     <td>{$itm['payee']}</td>
                //     <td>{$itm['bank_name']} :#{$itm['account_num']}</td>
                //     <td>{$itm['orsNums']}</td>
                //     <td >
                //         {$itm['chart_of_acc']}
                //     </td>

                //     <td>" . number_format($itm['ttlAmtDisbursed'], 2) . "</td>
                //     <td>" . number_format($itm['ttlTax'], 2) . "</td>
                //     <td>" . number_format($itm['grossAmt'], 2) . "</td>
                //     <td>" . Html::a('link', ['dv-aucs/view', 'id' => $itm['dv_id']], ['class' => 'btn btn-link']) . "</td>


                //         </tr>";
                //     }
                //     echo "<tr class='warning'>

                //     <th colspan='7' style='text-align:center'>Total</th>
                //     <th>" . number_format($grndTtlAmtDisbursed, 2) . "</th>
                //     <th>" . number_format($grndTtlTax, 2) . "</th>
                //     <th>" . number_format($grndGrossAmt, 2) . "</th>
                //     <td></td>

                //     </tr>";
                ?>
                <template v-for="(item,idx) in disbursementItems">

                    <tr>

                        <td>{{item.book_name}}</td>
                        <td>{{item.dv_number}}</td>
                        <td>{{item.particular}}</td>
                        <td>{{item.payee}}</td>
                        <td>{{item.bank_name}} :#{{item.account_num}}</td>
                        <td>{{item.orsNums}}</td>
                        <td>{{item.chart_of_acc}}</td>
                        <td>{{formatAmount(item.ttlAmtDisbursed)}}</td>
                        <td>{{formatAmount(item.ttlTax)}}</td>
                        <td>{{formatAmount(item.grossAmt)}}</td>
                        <td>
                            <a :href="'/q/index.php?r=dv-aucs/view&id='+item.dv_id" class="btn btn-link">Link</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="11">
                            <span>
                                <button @click="getOrsBreakdowns(item)" class="btn btn-link" type="button" data-toggle="collapse" :data-target="'#collapse'+idx" aria-expanded="false" aria-controls="collapseExample">
                                    Show ORS Breakdown
                                </button>
                            </span>
                            <div class="collapse" :id="'collapse'+idx">
                                <div class="card p-2">
                                    <table>
                                        <thead>
                                            <tr class="table-primary">
                                                <th colspan="10" class="text-center">Breakdown</th>
                                            </tr>
                                            <tr>

                                                <th class="text-center">Serial Number</th>
                                                <th class="text-center">UACS</th>
                                                <th class="text-center">General Ledger</th>
                                                <th class="text-center">Amount Disbursed</th>
                                                <th class="text-center">VAT/NON-VAT</th>
                                                <th class="text-center">EWT</th>
                                                <th class="text-center">Compensation</th>
                                                <th class="text-center">Other Trust Liabilities</th>
                                                <th class="text-center">Liquidation Damage</th>
                                                <th class="text-center">Tax Portion of Pos</th>
                                            </tr>

                                        </thead>
                                        <tbody>

                                            <tr v-for="(orsBreakdown,orsIndex) in  orsBreakdowns[item.dv_id]">
                                                <td class="text-center">{{orsBreakdown.serial_number}}</td>
                                                <td class="text-center">{{orsBreakdown.uacs}}</td>
                                                <td class="text-center">{{orsBreakdown.general_ledger}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.amount_disbursed)}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.vat_nonvat)}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.ewt_goods_services)}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.compensation)}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.other_trust_liabilities)}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.liquidation_damage)}}</td>
                                                <td class="text-center">{{formatAmount(orsBreakdown.tax_portion_of_post)}}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-center">Total</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'amount_disbursed')}}</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'vat_nonvat')}}</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'ewt_goods_services')}}</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'compensation')}}</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'other_trust_liabilities')}}</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'liquidation_damage')}}</th>
                                                <th class="text-center">{{orsBreakdownTotal(orsBreakdowns[item.dv_id],'tax_portion_of_post')}}</th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>


            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-center"> Total</th>
                    <th>{{total('ttlAmtDisbursed')}}</th>
                    <th>{{total('ttlTax')}}</th>
                    <th>{{total('grossAmt')}}</th>
                    <td></td>
                </tr>
            </tfoot>
        </table>


    </div>

</div>


<style>
    .amt {
        text-align: right;
    }

    .panel {
        padding: 2rem;
    }

    .ctr {
        text-align: center;
    }

    .items_tbl>th,
    .items_tbl>td {
        text-align: center;
    }

    .ctr {
        text-align: center;
    }

    #summary_tbl th,
    #check_details_tbl th {
        padding: 1rem;
        border: 1px solid black;
    }

    @media print {

        .hdn,
        .main-footer,
        .btn {
            display: none;
        }

        th,
        td {
            padding: 4px;
            font-size: 10px;
        }
    }
</style>

<script>
    $(document).ready(function() {

        $("#mainVue").removeClass("d-none")
        new Vue({
            el: "#mainVue",
            data: {
                disbursementItems: <?= json_encode($items) ?? json_encode([]) ?>,
                orsBreakdowns: {}
            },
            mounted() {
                // console.log(this.disbursementItems)

            },
            methods: {
                formatAmount(unitCost) {
                    unitCost = parseFloat(unitCost)
                    if (typeof unitCost === 'number' && !isNaN(unitCost)) {
                        return unitCost.toLocaleString(); // Formats with commas based on user's locale
                    }
                    return 0; // If unitCost is not a number, return it as is
                },
                total(attrib) {
                    const total = this.disbursementItems.reduce((total, item) => total + parseFloat(item[attrib]), 0);
                    return this.formatAmount(total)
                },
                async getOrsBreakdowns(item) {
                    if (!this.orsBreakdowns[item.dv_id]) {
                        const url = "?r=cash-disbursement/get-dv-ors-breakdowns"
                        const data = {
                            _csrf: '<?= Yii::$app->request->getCsrfToken() ?>',
                            id: item.dv_id

                        }
                        await axios.post(url, data)
                            .then(res => {
                                Vue.set(this.orsBreakdowns, item.dv_id, res.data);
                            })
                            .catch(err => {
                                console.log(err)
                            })

                    }
                },
                orsBreakdownTotal(item, attrib) {

                    if (item) {
                        let total = item.reduce((total, item) => {
                            return total + parseFloat(item[attrib])
                        }, 0)
                        return this.formatAmount(total)
                    }
                }


            }

        })
    })
</script>
<?php
SweetAlertAsset::register($this);
$script = <<<JS
    $("#cancel").click(function(){
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: true
         },
         function(isConfirm){

           if (isConfirm){
                    $.ajax({
                        type:"POST",
                        url:window.location.pathname + "?r=cash-disbursement/cancel",
                        data:{
                            id:$("#cancel_id").val()
                        },
                        success:function(data){
                            var res = JSON.parse(data)
                            var cancelled = res.cancelled?"Successfully Cancelled":"Successfully Activated";
                            if(res.isSuccess){
                                swal({
                                        title:cancelled,
                                        type:'success',
                                        button:false,
                                        timer:3000,
                                    },function(){
                                        location.reload(true)
                                    })
                            }
                            else{
                                swal({
                                        title:"Error Cannot Cancel",
                                        text:"Dili Ma  Cancel ang Disbursment Niya",
                                        type:'error',
                                        button:false,
                                        timer:3000,
                                    })
                            }

                        }
                    })


            } 
        })

    

    })
JS;
$this->registerJs($script);
?>
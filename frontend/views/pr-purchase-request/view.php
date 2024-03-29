<?php

use yii\helpers\Html;
use common\models\User;
use yii\widgets\DetailView;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseRequest */

$this->title = $model->pr_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Purchase Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
SweetAlertAsset::register($this);


?>
<div class="pr-purchase-request-view">


    <div class="container ">
        <div class="card p-2">

            <span>
                <?php
                if (!$model->hasRfq() || Yii::$app->user->can('super-user')) {
                    echo Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                }
                ?>
                <button type="button" class="print btn btn-warning">Print</button>
                <?php
                // if (Yii::$app->user->can('ro_procurement_admin') || Yii::$app->user->can('po_procurement_admin')) {
                $btn_color = $model->is_cancelled ? 'btn btn-success' : 'btn btn-danger';
                $cncl_txt = $model->is_cancelled ? 'UnCancel' : 'Cancel';
                if (!$model->is_cancelled) {
                    echo Html::a($cncl_txt, ['cancel', 'id' => $model->id], [
                        'class' => $btn_color,
                        'id' => 'cancel'

                    ]);
                } else {
                    echo "<h5 style='color:red;' class='notes'>This PR is Cancelled.</h5>";
                }
                // }
                ?>
            </span>
        </div>

        <div class="card p-2">

            <table id="main_table">
                <thead>
                    <tr>
                        <th colspan="6" style="text-align: center;">
                            <h4 style="font-weight:bold;">PURCHASE REQUEST</h4>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3">Entity Name: Department of Trade and Industry</th>
                        <th colspan="3">

                            <span> Fund Cluster:</span>
                            <span><?= $model->book->name ?></span>

                        </th>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <span>
                                Office/Section:
                            </span>
                            <span>
                                <?php

                                $office_name = !empty($office_division_unit_purpose['office_name']) ? strtoupper($office_division_unit_purpose['office_name']) : '';
                                $division = !empty($office_division_unit_purpose['division']) ? strtoupper($office_division_unit_purpose['division']) : '';
                                $division_program_unit = !empty($office_division_unit_purpose['division_program_unit']) ? '-' . strtoupper($office_division_unit_purpose['division_program_unit']) : '';


                                echo $office_name . '-' . $division . $division_program_unit;
                                ?>
                            </span>

                        </th>
                        <th colspan="2">
                            <span>PR No:</span>
                            <span><?= $model->pr_number ?></span>
                            <br>
                            <span>Responsibility Center Code:</span>
                            <span><?= $division ?></span>

                        </th>
                        <th colspan="2">
                            <span>
                                Date:
                            </span>
                            <span><?= DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y') ?></span>

                        </th>
                    </tr>
                    <tr>

                        <th class="center">Stock/ Property No.</th>
                        <th class="center">Unit</th>
                        <th class="center">Item Description</th>
                        <th class="center">Quantity</th>
                        <th class="center">Unit Cost</th>
                        <th class="center">Total Cost</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($model->getPrItems() as $i => $val) :
                        $unit_cost = $val['unit_cost'];
                        $quantity = $val['quantity'];
                        $total_cost = intval($quantity) * floatval($unit_cost);
                        $total += $total_cost;
                        $stock_title = $val['stock_title'];
                        $unit_of_measure = $val['unit_of_measure'];
                        $bac_code = $val['bac_code'];
                        $is_supplemental = intval($val['is_supplemental']) === 1 ? '*' : '';
                        $specification  = preg_replace('#\[n\]#', "<br>",  $val['specification']);
                    ?>
                        <tr>
                            <td><?= "$is_supplemental $bac_code" ?></td>
                            <td class='text-center text-capitalize'><?= $unit_of_measure ?></td>
                            <td>
                                <b>
                                    <?= $stock_title ?>
                                </b>
                                <br>
                                <span>
                                    <?= $specification ?>
                                </span>
                            </td>
                            <td class='text-center'><?= $quantity ?></td>
                            <td class='text-right'><?= number_format($unit_cost, 2) ?></td>
                            <td class='text-right'><?= number_format($total_cost, 2) ?></td>
                            <td class='text-right link  border-0'>
                                <?= Html::a(
                                    "Link",
                                    ['supplemental-ppmp/view', 'id' => $val['ppmp_id']],
                                    ['class' => 'btn btn-link']
                                ) ?>
                            </td>
                        </tr>

                    <?php endforeach; ?>
                    <?php
                    for ($i = 0; $i < 3; $i++) {
                        echo "<tr>
                            <td style='height: 3rem;'></td>
                            <td></td>
                            <td>
                            </td>
                            <td></td>
                            <td></td>
                            <td class='amount'></td>
                        </tr>";
                    }
                    ?>



                    <tr>
                        <td colspan="5" class="total">GRAND TOTAL</td>
                        <td class="amount" style="font-weight: bold;"><?= number_format($total, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <span style="font-weight:bold">Purpose: </span>

                            <span><?= $model->purpose ?></span>
                            <span>
                                <hr>
                                <hr>
                            </span>
                        </td>
                    </tr>


                    <tr>
                        <td colspan="6" style="border-bottom: none;font-size:10px">

                            <span>
                                Project Title:
                            </span>
                            <span>

                                <?php
                                // $model->projectProcurement->title 
                                ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="border-top: none;">
                            <table id="footer_table">
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td style="font-weight:bold;padding-bottom:3rem">Requested By</td>
                                        <td style="font-weight:bold;padding-bottom:3rem;">Approved By</td>
                                    </tr>
                                    <tr>

                                        <td>
                                            <span> Signature:</span>
                                            <br>
                                            <span> Printed Name:</span>
                                            <br>
                                            <span> Designation</span>

                                        </td>
                                        <td class="center">
                                            <span class="center"></span>
                                            <br>
                                            <span style="text-decoration:underline;font-weight: bold;">
                                                <?php
                                                $reqMname = !empty($model->requestedBy->m_name[0]) ? $model->requestedBy->m_name[0] . '.' : '';
                                                $name = $model->requestedBy->f_name
                                                    . ' ' . $reqMname . $model->requestedBy->l_name;
                                                echo strtoupper($name);
                                                ?>
                                            </span>
                                            <br>
                                            <span><?php
                                                    echo $name = $model->requestedBy->position;
                                                    ?></span>
                                        </td>
                                        <td class="center">
                                            <span class="center"></span>
                                            <br>

                                            <span style="text-decoration:underline;font-weight: bold;">
                                                <?php
                                                $name = $model->approvedBy->f_name
                                                    . ' ' . $model->approvedBy->m_name[0]
                                                    . '. ' . $model->approvedBy->l_name;
                                                echo strtoupper($name);
                                                ?>
                                            </span>
                                            <br>
                                            <span>
                                                <?php
                                                echo $name = $model->approvedBy->position
                                                ?>
                                            </span>

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </td>
                    </tr>


                </tbody>
            </table>
        </div>


        <?php
        $user_data = User::getUserDetails();
        if (strtolower($user_data->employee->office->office_name) === 'ro') : ?>
            <div class="card p-2">
                <table class="table table-hover allotment">
                    <thead>
                        <tr class="table-info">
                            <th colspan="4" class="center">
                                Allotments
                            </th>
                        </tr>
                        <tr>
                            <th>MFO/PAP Code</th>
                            <th> Fund Source</th>
                            <th> General Ledger</th>
                            <th> Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($model->getPrAllotments() as $item) {
                            echo "<tr>
                    <td>{$item['mfo_name']}</td>
                    <td>{$item['fund_source_name']}</td>
                    <td>{$item['account_title']}</td>
                    <td>" . number_format($item['gross_amount'], 2) . "</td>
                    </tr>";
                        }
                        ?>
                    </tbody>
                </table>

            </div>
            <div class="card p-2">

                <table class="table table-hover tbl-transaction-links">
                    <thead>
                        <tr class="table-info">
                            <th class="text-center">Transaction Links</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($model->getTxnLinks() as $txn) {
                        echo "<tr>";
                        echo "<td>" . Html::a($txn['txn_num'], ['transaction/view', 'id' => $txn['txn_id']], ['class' => 'btn btn-link']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->user->can('po_procurement_admin') || Yii::$app->user->can('ro_procurement_admin')) : ?>
            <div class="card p-2">
                <table id="link_table" class="table table-hover">
                    <thead>
                        <tr class="table-info">
                            <th colspan="3" class="text-center">RFQ LINKS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($model->getRfqLinks() as $val) {
                            $isCancelled = $val['is_cancelled'] == 1 ? 'Cancelled' : '';
                            echo "<tr>
                                <td>{$val['rfq_number']}</td>
                                <td>" . Html::a('RFQ Link ', ['pr-rfq/view', 'id' => $val['id']], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) . "</td>
                                <td>$isCancelled</td>
                            </tr>";
                        }
                        ?> </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>


</div>
<style>
    .center {
        text-align: center;
    }

    .description {

        font-weight: bold;
    }

    .center {
        text-align: center;
    }

    .amount {
        text-align: right;
    }

    .specs {
        font-style: italic;
    }


    #main_table th,
    #main_table td {
        border: 1px solid black;
        padding: 1rem;
    }

    #footer_table {
        width: 100%;
    }

    #footer_table td {
        border: none;
    }

    .container {
        padding: 3rem;
        /* background-color: white; */
    }

    .total {
        text-align: center;
        font-weight: bold;
    }

    table {
        width: 100%;
    }


    @media print {

        .link,
        .main-footer,
        .allotment,
        .tbl-transaction-links,
        .notes {
            display: none;
        }

        #link_table {
            display: none;
        }

        @page {
            margin: 0;
        }

        .btn {
            display: none;
        }

        th,
        td {
            padding: 8px;
            font-size: 13px;
        }
    }
</style>

<script>
    $(document).ready(function() {
        $('.print').click(function() {
            window.print()
        })
        $("#cancel").click((e) => {
            e.preventDefault();
            let ths = $(e.target)
            let link = ths.attr('href');
            swal({
                title: "Are you sure you want to " + ths.text() + " this PR?",
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
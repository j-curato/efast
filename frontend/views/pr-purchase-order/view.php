<?php

use yii\helpers\Url;
use yii\helpers\Html;
use aryelds\sweetalert\SweetAlertAsset;
use app\components\helpers\SweetAlertHelper;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseOrder */

$this->title = $model->po_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Purchase Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
SweetAlertAsset::register($this);

$purpose =  $model->aoq->rfq->purchaseRequest->purpose;
$auth_personel = strtoupper($model->authorizedOfficial->f_name . ' ' . $model->authorizedOfficial->m_name[0] . '. ' . $model->authorizedOfficial->l_name);
$auth_personel_position =  $model->authorizedOfficial->position;
$accountant = strtoupper($model->accountingUnit->f_name . ' ' . $model->accountingUnit->m_name[0] . '. ' . $model->accountingUnit->l_name);
$accountant_position = $model->accountingUnit->position;
$requested_by = '';
$requested_by_position = '';
$inspected_by = '';
$inspected_by_position = '';


if (!empty($model->requestedBy->f_name)) {
    $requested_by = strtoupper($model->requestedBy->f_name . ' ' . $model->requestedBy->m_name[0] . '. ' . $model->requestedBy->l_name);
    $requested_by_position = $model->requestedBy->position;
}
if (!empty($model->inspectedBy->f_name)) {
    $inspected_by = strtoupper($model->inspectedBy->f_name . ' ' . $model->inspectedBy->m_name[0] . '. ' . $model->inspectedBy->l_name);
    $inspected_by_position = $model->inspectedBy->position;
}
$po_date = '';
if (!empty($model->po_date)) {
    $po_date =  DateTime::createFromFormat('Y-m-d', $model->po_date)->format('F d, Y');
}

$date_begun = '';
$date_completed = '';

if (!empty($model->date_work_begun)) {
    $date_begun  = DateTime::createFromFormat('Y-m-d', $model->date_work_begun)->format('F d, Y');
}

if (!empty($model->date_completed)) {
    $date_completed  = DateTime::createFromFormat('Y-m-d', $model->date_completed)->format('F d, Y');
}
// echo json_encode($model->getItems());
?>
<div class="pr-purchase-order-view">

    <div class="container">
        <div class="card p-2">

            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('AOQ Link ', ['pr-aoq/view', 'id' => $model->fk_pr_aoq_id], ['class' => 'btn btn-link ', 'style' => 'margin:3px']) ?>

                <?= !$model->is_cancelled ?
                    Html::a('Cancel', ['cancel', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'id' => 'cancel'
                    ]) : '<span class="text-danger">This Purchase Order is Cancelled</span>' ?>
            </span>
        </div>

        <?php
        $row_number = 0;
        foreach ($model->getItems() as $index => $val) {
        ?>
            <div class="card p-2">

                <?php
                $po_number = $index;
                $payee =  $val[0]['payee'];
                $poItemId =  $val[0]['id'];
                $is_cancelled_item =  $val[0]['is_cancelled_item'];
                $payee_address =   !empty($val[0]['address']) ? $val[0]['address'] : '';
                $payee_tin_number =   !empty($val[0]['tin_number']) ? $val[0]['tin_number'] : '';
                $total_amount = intval($val[0]['quantity']) * floatval($val[0]['unit_cost']);
                $unit_of_measure = $val[0]['unit_of_measure'];
                $description = $val[0]['description'];
                $specification = $val[0]['specification'];
                $quantity = $val[0]['quantity'];

                if (strtolower($model->contractType->contract_name) === 'jo') {
                ?>
                    <table>
                        <thead>

                            <tr>
                                <th colspan="4" class="no-border">
                                    <span>Republic of the Philippines</span>
                                    <br>
                                    <span>DEPARTMENT OF TRADE & INDUSTRY</span>
                                    <br>
                                    <span>Regional Office, Butuan City</span>
                                </th>
                            </tr>
                            <tr>
                                <th colspan="4" class="no-border">
                                    JOB ORDER
                                </th>
                            </tr>
                            <tr>
                                <td colspan="3" class="no-border"></td>
                                <th colspan="1" class="no-border left">
                                    <span>
                                        Job Order No.:
                                    </span>
                                    <span>
                                        <?php echo $po_number ?>
                                    </span>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="3" class="no-border"></td>
                                <th colspan="1" class="no-border left">
                                    <span>

                                        Date:
                                    </span>
                                    <span><?php echo $po_date ?></span>
                                </th>
                            </tr>
                            <tr>
                                <th class="no-border left">To</th>
                                <th colspan="3" class="no-border left">
                                    <div class="greeting">
                                        <div style="padding:0;">
                                            : <?php echo $payee ?>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th class="no-border left">ADDRESS</th>
                                <th colspan="3" class="no-border left">
                                    <div class="greeting">
                                        <div style="padding:0;">
                                            : <?php echo $payee_address ?>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                            <tr>
                                <th class="no-border left">DESCRIPTION OF WORK</th>
                                <th colspan="3" class="no-border"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="no-border">
                                    <div class="greeting">
                                        <div style="font-weight: bold;"><?php echo $description ?></div>
                                        <?php
                                        foreach ($val as $val2) {
                                            $total_amount = intval($val2['quantity']) * floatval($val2['unit_cost']);
                                            // $total_amount = 0;
                                            $unit_of_measure = $val2['unit_of_measure'];
                                            $description = $val2['description'];
                                            $specification = $val2['specification'];
                                            $quantity = $val2['quantity'];
                                            $spec =  explode('<br>', $specification);
                                            foreach ($spec as $specs_val) {

                                                echo "<div style='font-style:italic;'>
                                            {$specs_val}
                                        </div>";
                                            }
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border" style="border-bottom: 1px solid black;"></td>
                                <td colspan="3" class="no-border" style="border-bottom: 1px solid black; font-weight:bold;"><?php echo $purpose ?></td>
                            </tr>
                            <tr>
                                <td class="no-border">
                                    Estimated Cost
                                </td>
                                <td colspan="2" class="no-border ">
                                    <div class="greeting">
                                        <div style="padding: 0;">
                                            :<?php echo number_format($total_amount, 2) ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border">
                                    Project Charge
                                </td>
                                <td colspan="2" class="no-border ">

                                    <div class="greeting">
                                        <div style="padding: 0;">
                                            :<?php echo number_format($total_amount, 2) ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border">
                                    Date & Time Work Begun
                                </td>
                                <td colspan="2" class="no-border">

                                    <div class="greeting">
                                        <div style='padding:0;'>
                                            : <?php echo $date_begun ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="no-border"></td>
                            </tr>
                            <tr>
                                <td class="no-border">
                                    Date & Time Completed
                                </td>
                                <td colspan="2" class="no-border">
                                    <div class="greeting">
                                        <div style='padding:0;'>
                                            : <?php echo $date_completed ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="no-border"></td>
                            </tr>
                            <tr>
                                <td class="no-border" style="vertical-align:top;">
                                    Conforme
                                </td>
                                <td colspan="2" class="no-border" style="font-weight:bold;">
                                    <br>
                                    <br>
                                    <br>

                                    <div class="greeting">
                                        <div style="text-align: center;padding:0;"> <?php echo $payee ?></div>
                                    </div>
                                    <br>
                                    <br>
                                </td>
                                <td class='no-border'></td>
                            </tr>
                            <tr>
                                <td class="no-border">Requested by</td>
                                <td class="no-border">Authorized by</td>
                                <td class="no-border">Inspected by</td>
                                <td class="no-border">Funds Availability </td>
                            </tr>
                            <tr>
                                <td class=" signatories center no-border" style="min-width: 200px;">
                                    <span class="personel" style="text-decoration: underline;font-size:12px">
                                        <?= $requested_by ?>
                                    </span>
                                    <br>
                                    <span>
                                        <?= $requested_by_position ?>
                                    </span>
                                </td>
                                <td class=" signatories center no-border" style="min-width: 200px;">
                                    <span class="personel" style="text-decoration: underline;font-size:12px">
                                        <?= $auth_personel ?>

                                    </span>
                                    <br>
                                    <span>
                                        <?= $auth_personel_position ?>


                                    </span>
                                </td>
                                <td class=" signatories center no-border" style="min-width: 200px;">
                                    <span class="personel" style="text-decoration: underline;font-size:12px">
                                        <?= $inspected_by ?>

                                    </span>
                                    <br>
                                    <span>
                                        <?= $inspected_by_position ?>

                                    </span>
                                </td>
                                <td class=" signatories center no-border" style="min-width: 200px;">
                                    <span class="personel" style="text-decoration: underline;font-size:12px">
                                        <?= $accountant ?>

                                    </span>
                                    <br>
                                    <span>
                                        <?= $accountant_position ?>

                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php
                } else {
                ?>
                    <table id="purchase_order">
                        <tbody>
                            <tr>
                                <?php if (!$model->is_cancelled) : ?>
                                    <th colspan="6" class="text-left text-danger border-0 p-1">
                                        <?= !$is_cancelled_item ? Html::a('Cancel Item', ['cancel', 'id' => $model->id], [
                                            'class' => 'btn btn-danger',
                                            'onclick' => SweetAlertHelper::getCancelConfirmation(Url::to(['cancel-item', 'id' => $poItemId]))
                                        ]) : "$po_number is  Cancelled" ?>
                                    </th>
                                <?php endif ?>
                            </tr>
                            <tr>
                                <th class="text-center" colspan="6">
                                    <span>PURCHASE ORDER</span>
                                    <br>
                                    <span>Department of Trade and Industry</span>
                                    <br>
                                    <span>Entity Name</span>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span>Supplier:</span>
                                    <span><?= $payee ?></span>
                                    <br>
                                    <span>Address:</span>
                                    <span><?= $payee_address ?></span>
                                    <br>
                                    <span>TIN:</span>
                                    <span><?= $payee_tin_number ?></span>
                                </td>
                                <td colspan="3">
                                    <span>Division: </span>
                                    <span class="text-uppercase"><?= $model->getDivision() ?></span>
                                    <br>
                                    <span>P.O No.:</span>
                                    <span><?= $po_number ?></span>
                                    <br>
                                    <span>Date:</span>
                                    <span><?= $po_date ?></span>
                                    <br>
                                    <span>Mode of Procurement:</span>
                                    <span><?= $model->modeOfProcurement->mode_name ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <span>Gentlemen:</span>
                                    <span> Please furnish this Office the following articles subject to the terms and conditions contained herein:</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span>Place of Delivery:</span>
                                    <span><?= $model->place_of_delivery ?></span>
                                    <br>
                                    <span>Date of Delivery:</span>
                                    <span><?= $model->delivery_date ?></span>

                                </td>
                                <td colspan="3">
                                    <span>Delivery Term:</span>
                                    <span><?= $model->delivery_term ?></span>


                                    <br>
                                    <span>Payment Term:</span>
                                    <span><?= $model->payment_term ?></span>


                                </td>
                            </tr>
                            <tr>
                                <th>Stock/ Property No.</th>
                                <th>Unit</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Unit Cost</th>
                                <th>Amount</th>

                            </tr>
                            <?php
                            $grand_total = 0;
                            foreach ($val as $val2) {

                                $total_amount = intval($val2['quantity']) * floatval($val2['unit_cost']);
                                $unit_cost =  !empty($val2['unit_cost']) ? number_format($val2['unit_cost'], 2) : 0;
                                echo "<tr>
                                <td>{$val2['bac_code']}</td>
                                <td>{$val2['unit_of_measure']}</td>
                                <td>
                                
                                    <span class='font-weight-bold'>  {$val2['description']}</span>
                                    <br>
                                    <span style='font-style:italic'>
                                    {$val2['specification']}
                                    </span>
                                </td>
                                <td class='text-center'> {$val2['quantity']}</td>
                                <td class='amount'> {$unit_cost} </td>
                                <td class='amount'>" . number_format($total_amount, 2) . " </td>
                            </tr>";
                                $grand_total += $total_amount;
                            }

                            ?>
                            <tr>
                                <th colspan="5">Grand Total</th>
                                <th class="text-right"><?php echo number_format($grand_total, 2) ?></th>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <span>Purpose: </span>
                                    <span class="font-weight-bold">
                                        <?= $purpose ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <span>(Total Amount in Words): </span>
                                    <span class="font-weight-bold">
                                        <?= strtoupper(Yii::$app->memem->convertNumberToWord($grand_total)) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent for every day of delay shall be imposed on the undelivered item/s.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center  border-right-0">
                                    <span class="float-left">Conforme:</span>
                                    <br>
                                    <br>
                                    <br>
                                    <u class="font-weight-bold"><?= $payee ?></u>
                                    <br>
                                    <span>Signature over Printed Name of Supplier</span>
                                    <br>
                                    <br>
                                    <span>______________________</span>
                                    <br>
                                    <span>Date</span>
                                </td>
                                <td colspan="3" class="text-center border-left-0">
                                    <span class="float-left">Very truly yours,</span>
                                    <br>
                                    <br>
                                    <br>
                                    <u class=" font-weight-bold">
                                        <?= strtoupper($model->authorizedOfficial->f_name . ' ' . $model->authorizedOfficial->m_name[0] . '. ' . $model->authorizedOfficial->l_name) ?>
                                    </u>
                                    <br>
                                    <span>Signature over Printed Name of Authorized Official</span>
                                    <br>
                                    <br>
                                    <span><?= $model->authorizedOfficial->position ?></span>
                                    <br>
                                    <span>Designation</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-center">
                                    <span class="float-left">Fund Cluster:</span>
                                    <span class="float-left">_______________________</span>
                                    <br>
                                    <span class="float-left">Funds Available:</span>
                                    <span class="float-left">_______________________</span>
                                    <br>
                                    <br>
                                    <br>
                                    <u class=" font-weight-bold">
                                        <?= mb_strtoupper($model->accountingUnit->f_name . ' ' . $model->accountingUnit->m_name[0] . '. ' . $model->accountingUnit->l_name, 'UTF-8') ?>
                                    </u>
                                    <br>
                                    <span style="margin-left:auto;width:100%">Signature over Printed Name of Chief Accountant/Head of </span>
                                    <br>
                                    </span> Accounting Division/Unit</span>
                                </td>
                                <td colspan="3" style="width: 300px;">
                                    <span>ORS/BURS No.:</span>
                                    <span>__________________</span>
                                    <br>
                                    <span>Date of the ORS/BURS:</span>
                                    <span>______________</span>
                                    <br>
                                    <span>Amount:</span>
                                    <span>__________________</span>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php } ?>
                <p style='page-break-after:always;'></p>
            </div>
        <?php } ?>

        <div class="card p-2">
            <table id="rfi_links" class="table table-hover">
                <thead>
                    <tr class="table-info">
                        <th colspan="5">RFI LINKS</th>
                    </tr>
                    <th>PO No.</th>
                    <th>Stock Name</th>
                    <th>RFI Number</th>
                    <th>Quantity</th>
                    <th>Link</th>
                </thead>
                <tbody>
                    <?php
                    foreach ($model->getRfiLinks() as $rfi) {
                        $stock_title = $rfi['stock_title'];
                        $quantity = $rfi['quantity'];
                        $id = $rfi['fk_request_for_inspection_id'];
                        $rfi_number = $rfi['rfi_number'];
                        $po_number = $rfi['po_number'];
                        echo "<tr>
                                <td>$po_number</td>
                                <td>$stock_title</td>
                                <td>$rfi_number</td>
                                <td>$quantity</td>
                                <td>";
                        echo    HTMl::a('Link', ['request-for-inspection/view', 'id' => $id], ['class' => 'btn btn-link']);
                        echo "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>



</div>

<style>
    .greeting {
        width: 100%;

    }

    .center {
        text-align: center;
    }

    .personel {
        text-align: center;
        font-weight: bold;
        border: none;

    }

    .right {
        text-align: right;
    }

    .left {
        text-align: left;
    }

    .greeting div {

        text-align: left;
        padding-left: 35%;
        border-bottom: 1px solid black;
    }

    table {
        width: 100%;
    }

    .no-border {
        border: none;
    }



    td,
    th {
        border: 1px solid black;
        padding: 2rem;
    }

    .amount {
        text-align: right;
    }

    th {
        text-align: center;
    }

    #rfi_links td,
    #rfi_links th {
        border: none;
        text-align: center;
    }

    @media print {

        .btn,
        .main-footer,
        #rfi_links {
            display: none;
        }

        .container {
            padding: 2rem;
        }

        td,
        th {
            padding: 1rem;
        }

        .table {
            page-break-after: auto;
        }

        .signatories {
            padding-left: 0;
            padding-right: 0;
            padding-bottom: 0;
        }




    }
</style>
<script>
    $(document).ready(function() {
        $("#cancel").click((e) => {
            e.preventDefault();
            let ths = $(e.target)
            let link = ths.attr('href');
            swal({
                title: "Are you sure you want to " + ths.text() + " this item?",
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
                                console.log(res)
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
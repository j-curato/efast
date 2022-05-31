<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseOrder */

$this->title = $model->po_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Purchase Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="pr-purchase-order-view">


    <div class="container">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
        <?php foreach ($aoq_lowest as $val) {
        ?>

            <?php
            $payee =  $val['payee'];
            $payee_address =   $val['address'];
            $total_amount = intval($val['quantity']) * floatval($val['unit_cost']);
            $unit_of_measure = $val['unit_of_measure'];
            $description = $val['description'];
            $specification = $val['specification'];
            $quantity = $val['quantity'];
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
                                    <?php echo $model->po_number ?>
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

                                    $spec =  explode('<br>', $specification);
                                    foreach ($spec as $val) {

                                        echo "<div style='font-style:italic;'>
                                            {$val}
                                        </div>";
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
                            <td class="center no-border">
                                <span class="personel" style="text-decoration: underline;">
                                    <?php echo $requested_by ?>
                                </span>
                                <br>
                                <span>
                                    <?php echo $requested_by_position ?>
                                </span>
                            </td>
                            <td class="center no-border">
                                <span class="personel" style="text-decoration: underline;">
                                    <?php echo $auth_personel ?>

                                </span>
                                <br>
                                <span>
                                    <?php echo $auth_personel_position ?>


                                </span>
                            </td>
                            <td class="center no-border">
                                <span class="personel" style="text-decoration: underline;">
                                    <?php echo $inspected_by ?>

                                </span>
                                <br>
                                <span>
                                    <?php echo $requested_by_position ?>

                                </span>
                            </td>
                            <td class="center no-border">
                                <span class="personel" style="text-decoration: underline;">
                                    <?php echo $accountant ?>

                                </span>
                                <br>
                                <span>
                                    <?php echo $accountant_position ?>

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
                            <th style="text-align: center;" colspan="6">
                                <span>PURCHASE ORDER</span>
                                <br>
                                <span>Department of Trade and Industy</span>
                                <br>
                                <span>Entity Name</span>
                            </th>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span>Supplier:</span>
                                <span><?php
                                        echo $payee;

                                        ?></span>
                                <br>
                                <span>Address:</span>
                                <span><?php echo $val['address'] ?></span>
                                <br>
                                <span>TIN:</span>
                                <span><?php echo $val['tin_number'] ?></span>
                            </td>
                            <td colspan="3">
                                <span>P.O No.:</span>
                                <span><?php echo $model->po_number ?></span>
                                <br>
                                <span>Date:</span>
                                <span><?php
                                        if (!empty($model->po_date)) {

                                            echo  DateTime::createFromFormat('Y-m-d', $model->po_date)->format('F d, Y');
                                        }
                                        ?></span>
                                <br>
                                <span>Mode of Procurement:</span>
                                <span><?php echo $model->modeOfProcurement->mode_name ?></span>
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
                                <span><?php echo $model->place_of_delivery ?></span>
                                <br>
                                <span>Date of Delivery:</span>
                                <span><?php echo $model->delivery_date ?></span>

                            </td>
                            <td colspan="3">
                                <span>Delivery Term:</span>
                                <span><?php echo $model->delivery_term ?></span>


                                <br>
                                <span>Payment Term:</span>
                                <span><?php echo $model->payment_term ?></span>


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
                        $total_amount = intval($val['quantity']) * floatval($val['unit_cost']);
                        $unit_cost = number_format($val['unit_cost'], 2);
                        echo "<tr>
                        <td>{$val['bac_code']}</td>
                        <td>{$val['unit_of_measure']}</td>
                        <td>
                          
                            <span style='font-weight:bold'>  {$val['description']}</span>
                            <br>
                            <span style='font-style:italic'>
                            {$val['specification']}
                            </span>
                        </td>
                        <td> {$val['quantity']}</td>
                        <td class='amount'> {$unit_cost} </td>
                        <td class='amount'>" . number_format($total_amount, 2) . " </td>
                    </tr>";
                        ?>
                        <tr>
                            <td colspan="6">
                                <span>Purpose: </span>
                                <span style="font-weight: bold;">
                                    <?php
                                    echo $purpose;
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <span>(Total Amount in Words): </span>
                                <span style="font-weight: bold;">
                                    <?php
                                    echo strtoupper(Yii::$app->memem->convertNumberToWord($total_amount));
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent for every day of delay shall be imposed on the undelivered item/s.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center; border-right:none">
                                <span style="float: left;">Conforme:</span>
                                <br>
                                <br>
                                <br>
                                <span style="text-decoration:underline;font-weight:bold"><?= $payee ?></span>

                                <br>
                                <span>Signature over Printed Name of Supplier</span>
                                <br>
                                <br>
                                <span>______________________</span>
                                <br>
                                <span>Date</span>
                            </td>
                            <td colspan="3" style="text-align: center; border-left:none">
                                <span style="float: left;">Very truly yours,</span>
                                <br>
                                <br>
                                <br>
                                <span style="text-decoration: underline; font-weight:bold">
                                    <?php
                                    echo strtoupper($model->authorizedOfficial->f_name . ' ' . $model->authorizedOfficial->m_name[0] . '. ' . $model->authorizedOfficial->l_name)
                                    ?>
                                </span>
                                <br>
                                <span>Signature over Printed Name of Authorized Official</span>
                                <br>
                                <br>
                                <span><?php echo $model->authorizedOfficial->position ?></span>
                                <br>
                                <span>Designation</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center;">
                                <span style="float: left;">Fund Cluster:</span>
                                <span style="float: left;">_______________________</span>
                                <br>
                                <span style="float: left;">Funds Available:</span>
                                <span style="float: left;">_______________________</span>
                                <br>
                                <br>
                                <br>
                                <span style="text-decoration:underline; font-weight:bold;"> <?php
                                                                                            echo strtoupper($model->accountingUnit->f_name . ' ' . $model->accountingUnit->m_name[0] . '. ' . $model->accountingUnit->l_name)
                                                                                            ?></span>
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
        <?php } ?>

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

    .container {
        padding: 3em;
        background-color: white;
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

    @media print {
        .btn {
            display: none;
        }

        .container {
            padding: 0;
        }

        td,
        th {
            padding: 1rem;
        }

        .main-footer {
            display: none;
        }

        .table {
            page-break-after: auto;
        }


    }
</style>
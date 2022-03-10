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
            <table>
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
                            <span><?php echo $val['payee'] ?></span>
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
                            <span>_________________</span>
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

                    echo "<tr>
                        <td>{$val['bac_code']}</td>
                        <td>{$val['unit_of_measure']}</td>
                        <td>
                            {$val['description']}
                            {$val['specification']}
                        </td>
                        <td> {$val['quantity']}</td>
                        <td class='amount'> {$val['unit_cost']} </td>
                        <td class='amount'>" . intval($val['quantity']) * floatval($val['unit_cost']) . " </td>
                    </tr>";
                    ?>

                    <tr>
                        <td colspan="6"> <span>(Total Amount in Words)</span></td>
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
                            <span>________________________________</span>

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
                            <span style="text-decoration: underline;">
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
                            <span>________________________________________</span>
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
            <p style='page-break-after:always;'></p>
        <?php } ?>
    </div>

</div>

<style>
    table {
        width: 100%;
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
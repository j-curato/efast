<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrPurchaseRequest */

$this->title = $model->pr_number;
$this->params['breadcrumbs'][] = ['label' => 'Pr Purchase Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-purchase-request-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>



    <div class="container ">


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

                        <span> Fund CLuster:</span>
                        <span><?= $model->book->name ?></span>

                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>
                            Office/Section:
                        </span>
                        <span>
                            <?php echo $model->projectProcurement->office->office . '-' . $model->projectProcurement->office->division . '-' . $model->projectProcurement->office->unit ?>
                        </span>

                    </th>
                    <th colspan="2">
                        <span>PR No:</span>
                        <span><?= $model->pr_number ?></span>
                        <br>
                        <span>Responsibility Center Code:</span>
                        <span><?= $model->projectProcurement->office->responsibility_code ?></span>

                    </th>
                    <th colspan="2">
                        <span>
                            Date:
                        </span>
                        <span><?php
                                echo  DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y')
                                ?></span>

                    </th>
                </tr>
                <tr>

                    <th>Stock/ Property No.</th>
                    <th>Unit</th>
                    <th>Item Description</th>
                    <th>Quantity</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                </tr>

            </thead>
            <tbody>

                <?php
                $total = 0;
                foreach ($model->prItem as $val) {

                    $total_cost = intval($val->quantity) * floatval($val->unit_cost);
                    $total += $total_cost;
                    $specs = preg_replace('#\[n\]#', "<br>", $val->specification);
                    echo "<tr>
                        <td>{$val->stock->stock_number}</td>
                        <td>{$val->stock->unitOfMeasure->unit_of_measure}</td>
                        <td><span class='description'>" . $val->stock->description . "</span>" .
                        "<br><span class='specs'>"

                        . $specs
                        . "</specs></td>
                        <td>{$val->quantity}</td>
                        <td class='amount'>{$val->unit_cost}</td>
                        <td class='amount'>$total_cost</td>
                    </tr>";
                }
                ?>
                <tr>
                    <td style="height: 3rem;"></td>
                    <td></td>
                    <td>
                    </td>
                    <td></td>
                    <td></td>
                    <td class='amount'></td>
                </tr>
                <tr>
                    <td style="height: 3rem;"></td>
                    <td></td>
                    <td>
                    </td>
                    <td></td>
                    <td></td>
                    <td class='amount'></td>
                </tr>


                <tr>
                    <td colspan="5" class="total">GRAND TOTAL</td>
                    <td class="amount" style="font-weight: bold;"><?= $total ?></td>
                </tr>
                <tr>
                    <td colspan="6">
                        <span style="font-weight:bold">Purpose: </span>

                        <span><?= $model->purpose ?></span>
                    </td>
                </tr>


                <tr>
                    <td colspan="6">
                        <table id="footer_table">
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td style="font-weight:bold;padding-bottom:3rem">Requested By</td>
                                    <td style="font-weight:bold;padding-bottom:3rem;">Approved By</td>
                                </tr>
                                <tr>

                                    <td>Signature:</td>
                                    <td class="center">
                                        <span class="center">__________________________________</span>
                                    </td>
                                    <td class="center">
                                        <span class="center">__________________________________</span>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Printed Name:</td>
                                    <td class="center">
                                        <span style="text-decoration:underline;font-weight: bold;">
                                            <?php
                                            $name = $model->requestedBy->f_name
                                                . ' ' . $model->requestedBy->m_name[0]
                                                . '. ' . $model->requestedBy->l_name;
                                            echo strtoupper($name);
                                            ?>
                                        </span>
                                    </td>
                                    <td class="center">
                                        <span style="text-decoration:underline;font-weight: bold;">
                                            <?php
                                            $name = $model->approvedBy->f_name
                                                . ' ' . $model->approvedBy->m_name[0]
                                                . '. ' . $model->approvedBy->l_name;
                                            echo strtoupper($name);
                                            ?>
                                        </span>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Designation:</td>
                                    <td class="center">
                                        <span>__________________________________</span>
                                    </td>
                                    <td class="center">
                                        <span>__________________________________</span>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>


            </tbody>
        </table>
    </div>

</div>
<style>
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
        background-color: white;
    }

    .total {
        text-align: center;
        font-weight: bold;
    }

    table {
        width: 100%;
    }


    @media print {
        .main-footer {
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
            font-size: 12px;
        }
    }
</style>
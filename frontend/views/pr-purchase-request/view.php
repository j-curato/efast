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






    <div class="container ">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
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
                foreach ($model->prItem as $val) {

                    $total_cost = intval($val->quantity) * floatval($val->unit_cost);
                    $total += $total_cost;
                    $specs = preg_replace('#\[n\]#', "<br>", $val->specification);

                    echo "<tr>
                        <td>{$val->stock->bac_code}</td>
                        <td class='center'>{$val->unitOfMeasure->unit_of_measure}</td>
                        <td><span class='description'>" . $val->stock->stock_title . "</span>" .
                        "<br><span class='specs'>"

                        . $specs
                        . "</specs></td>
                        <td class='center'>{$val->quantity}</td>
                        <td class='amount'>" . number_format($val->unit_cost, 2) . "</td>
                        <td class='amount'>" . number_format($total_cost, 2) . "</td>
                    </tr>";
                }
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

                            <?php echo $model->projectProcurement->title ?>
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
                                        <span class="center">__________________________________</span>
                                        <br>
                                        <span style="text-decoration:underline;font-weight: bold;">
                                            <?php
                                            $name = $model->requestedBy->f_name
                                                . ' ' . $model->requestedBy->m_name[0]
                                                . '. ' . $model->requestedBy->l_name;
                                            echo strtoupper($name);
                                            ?>
                                        </span>
                                        <br>
                                        <span><?php
                                                echo $name = $model->requestedBy->position;
                                                ?></span>
                                    </td>
                                    <td class="center">
                                        <span class="center">__________________________________</span>
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
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

            <?php

            if (!$model->is_final && !Yii::$app->user->can('super-user')) {
                $check_rfqs = YIi::$app->db->createCommand("SELECT id FROM pr_rfq WHERE pr_purchase_request_id = :id")
                    ->bindValue(':id', $model->id)
                    ->queryAll();
                if (empty($check_rfqs) && !Yii::$app->user->can('super-user')) {
                    echo     Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                }
            }

            ?>
            <button type="button" class="print btn btn-warning">Print</button>
            <?php

            if (Yii::$app->user->can('super-user')) {


                $btn_color = 'btn btn-danger';
                if ($model->is_final) {
                    $btn_color = 'btn btn-success';
                }
                echo     Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . ' ';
                echo  Html::a('Final', ['final', 'id' => $model->id], [
                    'class' => $btn_color,
                    'data' => [
                        'confirm' => 'Are you sure you want to Final this item?',
                        'method' => 'post',
                    ],
                ]);
            }
            ?>
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
                        <span><?php
                                echo  DateTime::createFromFormat('Y-m-d', $model->date)->format('F d, Y')
                                ?></span>

                    </th>
                </tr>
                <tr>

                    <th class="center">

                        Stock/ Property No.</th>
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

                foreach ($items as $i => $val) {
                    $unit_cost = $val['unit_cost'];
                    $quantity = $val['quantity'];

                    $total_cost = intval($quantity) * floatval($unit_cost);
                    $total += $total_cost;
                    $stock_title = $val['stock_title'];
                    $unit_of_measure = $val['unit_of_measure'];
                    $bac_code = $val['bac_code'];

                    $specification  = preg_replace('#\[n\]#', "<br>",  $val['specification']);
                    echo "<tr>
                    <td>{$bac_code}</td>
                    <td class='center'>{$unit_of_measure}</td>
                    <td><span class='description'>" .  $stock_title . "</span>" .
                        "<br><span class='specs'>"

                        . $specification
                        . "</specs></td>
                    <td class='center'>{$quantity}</td>
                    <td class='amount'>" . number_format($unit_cost, 2) . "</td>
                    <td class='amount'>" . number_format($total_cost, 2) . "</td>
                </tr>";
                }
                // foreach ($model->prItem as $val) {

                //     $total_cost = intval($val->quantity) * floatval($val->unit_cost);
                //     $total += $total_cost;
                //     $specs = preg_replace('#\[n\]#', "<br>", $val->specification);
                //     $bac_code = !empty($val->stock->bac_code) ? $val->stock->bac_code : '';
                //     $stock_title = !empty($val->stock->stock_title) ? $val->stock->stock_title : '';
                //     echo "<tr>
                //         <td>{$bac_code}</td>
                //         <td class='center'>{$val->unitOfMeasure->unit_of_measure}</td>
                //         <td><span class='description'>" .  $stock_title . "</span>" .
                //         "<br><span class='specs'>"

                //         . $specs
                //         . "</specs></td>
                //         <td class='center'>{$val->quantity}</td>
                //         <td class='amount'>" . number_format($val->unit_cost, 2) . "</td>
                //         <td class='amount'>" . number_format($total_cost, 2) . "</td>
                //     </tr>";
                // }
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
        <table class="table table-stripe allotment" style="margin-top: 3rem;">
            <thead>
                <tr class="info">
                    <th colspan="4" class="center">
                        <h4>

                            Allotment
                    </th>
                    </h4>
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
                foreach ($allotment_items as $item) {
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
        <?php

        $rfqs = Yii::$app->db->createCommand("SELECT id, rfq_number FROM pr_rfq WHERE pr_purchase_request_id = :id")
            ->bindValue(':id', $model->id)
            ->queryAll();

        if (Yii::$app->user->can('super-user')) {

        ?>
            <table id="link_table" class="table table-striped" style="margin-top:3rem">

                <tbody>
                    <tr class="danger">
                        <th colspan="2" style="text-align: center;">RFQ LINKS</th>
                    </tr>

                    <?php

                    foreach ($rfqs as $val) {
                        $link = yii::$app->request->baseUrl . "/index.php?r=pr-rfq/view&id={$val['id']}";

                        echo "<tr>
                    <td>{$val['rfq_number']}</td>
                    <td>" . Html::a('RFQ Link ', $link, ['class' => 'btn btn-warning ', 'style' => 'margin:3px']) . "</td>
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>


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

        .main-footer,
        .allotment {
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
            font-size: 12px;
        }
    }
</style>

<script>
    $(document).ready(function() {

        $('.print').click(function() {
            window.print()
        })
    })
</script>
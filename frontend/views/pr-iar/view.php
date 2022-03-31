<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$items = Yii::$app->db->createCommand("SELECT 

pr_stock.bac_code,
pr_stock.stock_title as `description`,
REPLACE(pr_purchase_request_item.specification,'[n]','<br>') as specification,
unit_of_measure.unit_of_measure,
pr_iar_item.quantity
FROM `pr_iar_item`
LEFT JOIN pr_aoq_entries ON pr_iar_item.fk_pr_aoq_entry_id = pr_aoq_entries.id
LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
LEFT JOIN pr_stock ON pr_purchase_request_item.pr_stock_id = pr_stock.id
LEFT JOIN unit_of_measure ON pr_purchase_request_item.unit_of_measure_id = unit_of_measure.id
WHERE pr_iar_item.fk_pr_iar_id = :id
")
    ->bindValue(':id', $model->id)
    ->queryAll();
?>
<div class="pr-iar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>


    <div class="container">
        <table>

            <tbody>
                <tr>
                    <th colspan="4" style="text-align: center;"> INSPECTION AND ACCEPTANCE REPORT</th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>Entity Name:</span>
                    </th>
                    <th colspan="2">
                        <span>Fund CLuster:</span>
                    </th>
                </tr>
                <tr>
                    <th colspan="2">
                        <span>Supplier:</span>
                        <span>_________________________</span>
                        <br>
                        <span>PO No./Date:</span>
                        <span>_________________________</span>
                        <br>
                        <span> Requisitioning Office/Dept:</span>
                        <span>_________________________</span>
                        <br>
                        <span> Responsibility Center Code:</span>
                        <span>_________________________</span>
                    </th>
                    <th colspan="2">
                        <span> IAR No.:</span>
                        <span>_________________________</span>
                        <br>
                        <span> Date:</span>
                        <span><?php echo DateTime::createFromFormat('Y-m-d', $model->_date)->format('F d, Y') ?></span>
                        <br>
                        <span> Invoice No.:</span>
                        <span><?php echo $model->invoice_number ?></span>
                        <br>
                        <span> Date:</span>
                        <span><?php echo DateTime::createFromFormat('Y-m-d', $model->invoice_date)->format('F d, Y') ?></span>
                    </th>
                </tr>
                <tr>
                    <th>Stock/Property No.</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                </tr>

                <?php

                foreach ($items as $val) {
                    $description = $val['description'];
                    $specification = $val['specification'];
                    $bac_code = $val['bac_code'];
                    $quantity = $val['quantity'];
                    $unit_of_measure = $val['unit_of_measure'];

                    echo "<tr>
                    
                    <td>$bac_code</td>
                    <td>
                    <span style='font-weight:bold'>$description</span>
                    <br>
                    <span style='font-style:italic'>$specification</span>
                    </td>
                    <td>$unit_of_measure</td>
                    <td>$quantity</td>
                    </tr>";
                }
                ?>

                <tr>
                    <th style="text-align: center;" colspan="2">INSEPECTION</th>
                    <th style="text-align: center;" colspan="2">ACCEPTANCE</th>
                </tr>
                <tr>
                    <td style="text-align: center;" colspan="2">
                        <span style="float:left">Date Inspected:</span>
                        <span style="float:left">______________________</span>
                        <br>
                        <br>
                        <br>
                        <div style="border:1px solid black;width:2.5rem;height:2.5rem;float:left;margin-right:1rem;"> </div>
                        <span style="text-align: left;">
                            Inspected, verified and found in order as to quantity and specifications
                        </span>
                        <br>
                        <br>
                        <br>
                        <br>
                        <span class='employee'>
                            <?php
                            echo Yii::$app->db->createCommand("SELECT employee_name FROM employee_search_view WHERE employee_id = :id")
                                ->bindValue(':id', $model->fk_inspection_officer)
                                ->queryScalar()
                            ?>
                        </span>
                        <br>
                        <span>Inspection Officer/Inspection Committee</span>
                    </td>
                    <td style="text-align: center;" colspan="2">
                        <span style="float: left;">Date Recieved:</span>
                        <span style="float: left;">__________________</span>
                        <br>
                        <br>
                        <br>
                        <div style="border:1px solid black;width:2.5rem;height:2.5rem;float:left;margin-right:1rem;"> </div>
                        <span style="float: left;">
                            Complete
                        </span>
                        <br>
                        <br>
                        <div style="border:1px solid black;width:2.5rem;height:2.5rem;float:left;margin-right:1rem;"> </div>
                        <span style="float: left;">
                            partial (pls. specify quantity)
                        </span>
                        <br>
                        <br>
                        <br>
                        <span class='employee'><?php
                                                echo Yii::$app->db->createCommand("SELECT employee_name FROM employee_search_view WHERE employee_id = :id")
                                                    ->bindValue(':id', $model->fk_property_custodian)
                                                    ->queryScalar()
                                                ?></span>
                        <br>
                        <span style="float:center">Supply and/or Property Custodian</span>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

</div>

<style>
    .container {
        background-color: white;
        padding: 3rem;
    }

    .employee {
        text-decoration: underline;
    }

    th,
    td {
        border: 1px solid black;
        padding: 2rem;
    }

    table {
        width: 100%;
    }

    @media print {
        .btn {
            display: none;
        }

        th,
        td {
            padding: 12px;
        }

        .container {
            padding: 5px;
        }

        .main-footer {
            display: none;
        }
    }
</style>
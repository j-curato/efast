<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-iar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
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
                        <span>
                            Inspected, verified and found in order as to quantity and specifications
                        </span>
                        <br>
                        <br>
                        <br>
                        <br>
                        <span>
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
                        <span style="float: left;">___________________</span>
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
                        <span><?php
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

    th,
    td {
        border: 1px solid black;
        padding: 2rem;
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
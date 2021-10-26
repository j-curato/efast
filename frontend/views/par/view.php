<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

$this->title = $model->property_number;
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="par-view">


    <p class=''>
        <?= Html::a('Update', ['update', 'id' => $model->property_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->property_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <div class="con">

        <table>
            <thead>
                <tr>
                    <th colspan="6" style="text-align: center;">PROPERTY ACKNOWLEDGEMENT RECIEPT</th>
                </tr>
                <tr>
                    <th colspan="6">
                        <span>
                            Entity Name:
                        </span>
                        <span>Department of Trade and Industry - Caraga</span>

                    </th>

                </tr>
                <tr>
                    <th colspan="3">
                        <span>Fund Cluster:</span>
                        <span><?php echo $model->property->book->name; ?></span>
                    </th>
                    <th colspan="3">
                        <span>PAR No:</span>
                        <span><?php echo $model->par_number; ?></span>
                    </th>
                </tr>
                <tr>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th>Property Number</th>
                    <th>Date Acquired</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>

                <?php
                 $d = new DateTime($model->property->date);
                 $dateAquired = $d->format('F d, Y');
                echo "<tr>
                        <td>{$model->property->quantity}</td>
                        <td>{$model->property->unitOfMeasure->unit_of_measure}</td>
                        <td>{$model->property->article}</td>
                        <td>{$model->property_number}</td>
                        <td>{$dateAquired}</td>
                        <td>{$model->property->acquisition_amount}</td>
                    </tr>";
                for ($i = 0; $i < 10; $i++) {
                    echo "<tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>";
                }
                ?>

            </tbody>
            <tfoot>
                <tr>
                    <th class='foot' colspan="3">Recieved By</th>
                    <th class='foot' colspan="3">Issued By</th>
                </tr>
                <tr>
                    <th class='foot' colspan="3">
                        <span style="text-decoration:underline">
                            <span><?php echo strtoupper($model->employee->f_name); ?> </span>
                            <span><?php echo strtoupper(substr($model->employee->m_name, 0, 1)); ?>. </span>
                            <span><?php echo strtoupper($model->employee->l_name); ?></span>
                        </span>
                        <br>
                        <span> Signatue over Printed Name of End User</span>
                    </th>
                    <th class='foot' colspan="3">
                        <span style="text-decoration:underline">
                            <span><?php echo strtoupper($model->property->employee->f_name); ?> </span>
                            <span><?php echo strtoupper(substr($model->property->employee->m_name, 0, 1)); ?>. </span>
                            <span><?php echo strtoupper($model->property->employee->l_name); ?></span>
                        </span>
                        <br>
                        <span> Signatue over Printed Name of Supply and/or </span>
                        <br>
                        <span>Property Custodian</span>
                    </th>

                </tr>
                <tr>
                    <th class='foot' colspan="3">
                        <span style="text-decoration: underline;"><?php echo strtoupper($model->employee->position); ?></span>
                        <br>
                        <span>Position</span>
                    </th>
                    <th class='foot' colspan="3">

                        <span style="text-decoration: underline;"><?php echo strtoupper($model->property->employee->position); ?></span>
                        <br>
                        <span>Position</span>
                    </th>

                </tr>
                <tr>
                    <th class='foot' colspan="3">

                        <span>___________</span>
                        <br>
                        <span>Date</span>
                    </th>
                    <th class='foot' colspan="3">
                        <span>___________</span>
                        <br>
                        <span>Date</span>
                    </th>

                </tr>
            </tfoot>
        </table>
    </div>

</div>
<style>
    table,
    th,
    td {
        padding: 12px;
        border: 1px solid black;
    }

    .foot {
        text-align: center;
        border-bottom: 0;
        border-top: 0;
    }

    .con {
        background-color: white;
    }

    @media print {
        .btn {
            display: none;
        }

        .main-footer {
            display: none;

        }
    }
</style>
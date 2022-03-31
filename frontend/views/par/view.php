<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

$this->title = $model->property_number;
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="par-view">





    <div class="container">
        <p class=''>
            <?= Html::button('<i class="glyphicon glyphicon-pencil"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=par/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-primary', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

            <?php
            if (!empty($model->propertyCard->pc_number)) {

                $t = Yii::$app->request->baseUrl . "/index.php?r=property-card/view&id={$model->propertyCard->id}";
                echo  Html::a('Property Card Link', $t, ['class' => 'btn btn-link ']);
            }
            ?>
        </p>
        <table>
         
            <tbody>
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
                        <span><?php echo !empty($model->property->book->name) ? $model->property->book->name : ''; ?></span>
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
                <?php
                $d = new DateTime($model->property->date);
                $dateAquired = $d->format('F d, Y');
                $description = preg_replace('#\[n\]#', "<br>", $model->property->description);

                $article = $model->property->article;
                echo "<tr>
                        <td>{$model->property->quantity}</td>
                        <td>{$model->property->unitOfMeasure->unit_of_measure}</td>
                        <td>
                        <span style='font-weight:bold;'>{$article}</span>
                        <br>
                        <span style='font-style:italic;'>$description</span>
                        
                        </td>
                        <td>{$model->property_number}</td>
                        <td>{$dateAquired}</td>
                        <td class='amount'>" . number_format($model->property->acquisition_amount, 2) . "</td>
                    </tr>";
                for ($i = 0; $i < 4; $i++) {
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

                        <span>_______________</span>
                        <br>
                        <span>Date</span>
                    </th>
                    <th class='foot' colspan="3">
                        <span>_______________</span>
                        <br>
                        <span>Date</span>
                    </th>

                </tr>
                <!-- ACTUAL USER -->
                <?php

                if (!empty($model->actual_user)) {
                    $user_name = "{$model->actualUser->f_name} {$model->actualUser->m_name[0]}. {$model->actualUser->l_name} ";
                    echo "        <tr>
                    <th class='foot' colspan='3' style='text-align:center;padding-top:5rem'>
                        <span style='text-decoration:underline'>
                            <span>$user_name </span>
                           
                        </span>
                        <br>
                        <span> Signatue over Printed Name of Actual User</span>
                    </th>
                    <th class='foot' colspan='3' style='text-align:center;padding-top:5rem'>
                
                    </th>


                </tr>";
                }
                ?>




            </tbody>
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

    .amount {
        text-align: right;
    }

    table {
        margin-left: auto;
        margin-right: auto;
        width: 100%;
    }

    .foot {
        text-align: center;
        border-bottom: 0;
        border-top: 0;
    }

    .container {
        background-color: white;
        padding: 20px;
    }

    @media print {
        .btn {
            display: none;
        }

        .container {
            background-color: white;
            padding: 0;
            border: none;
        }


        th,
        td {
            padding: 1rem;
            border: 1px solid black;
        }

        table {
            padding: 0;
        }

        .main-footer {
            display: none;

        }
    }
</style>
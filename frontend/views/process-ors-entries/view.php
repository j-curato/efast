<?php

use app\models\ProcessOrs;
use app\models\Raouds;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrsEntries */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Process Ors Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="process-ors-entries-view">
    <?php
    $ors  = ProcessOrs::findOne($model->process_ors_id);
    ?>
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['cancel', 'id' => $ors->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to cancel this item?',
                    'method' => 'post',
                ],
            ]) ?>
            <?php
            $t = yii::$app->request->baseUrl . "/index.php?r=transaction/view&id=$ors->transaction_id";
            echo  Html::a('Transaction', $t, ['class' => 'btn btn-info']);
            ?>
        </p>
        <table class="table table-striped">

            <thead>
                <th>
                    ID
                </th>
                <th>
                    Reporting Period
                </th>
                <th>
                    Payee
                </th>
                <th>
                    UACS
                </th>
                <th>
                    General Ledger
                </th>
                <th>
                    Amount
                </th>
            </thead>
            <tbody>
                <?php
                foreach ($ors->raouds as $key => $val) {

                    echo "
                    <tr>
                        <td>
                           {$key}
                        </td>
                        <td>
                           {$val->reporting_period}
                        </td>
                        <td>
                           {$val->processOrs->transaction->payee->account_name}
                        </td>
                        <td>
                           {$val->raoudEntries->chartOfAccount->uacs}
                        </td>
                        <td>
                           {$val->raoudEntries->chartOfAccount->general_ledger}
                        </td>
                        <td>" .
                        number_format($val->raoudEntries->amount, 2)
                        . "</td>
         
                    </tr>
                    
                    ";
                }

                ?>
            </tbody>
        </table>
    </div>
    <div class="container">


        <h4>List of DV's Using This ORS</h4>
        <table class="table">
            <thead>
                <th>
                    DV Number
                </th>
                <th>
                    Link
                </th>
            </thead>
            <tbody>

                <?php
                if (!empty($ors->dvAucsEntries)) {
                    $dv_id = 0;
                    foreach ($ors->dvAucsEntries as $val) {
                        $x = yii::$app->request->baseUrl . "/index.php?r=dv-aucs/view&id={$val->dvAucs->id}";
                        echo "<tr>
                        <td>{$val->dvAucs->dv_number}</td>
                        <td>" .
                            Html::a('Dv Link', $x, ['class' => 'btn-xs btn-danger '])
                            . "</td>
                        </tr>";
                    }

                    // http://10.20.17.33/dti-afms-2/frontend/web/index.php?r=dv-aucs%2Fview&id=6878
                    // echo  
                }
                ?>
            </tbody>

        </table>
    </div>

</div>

<style>
    .container {
        background-color: white;
        padding: 12px;
    }
</style>
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

    <?php
    // DetailView::widget([
    //     'model' => $model,
    //     'attributes' => [
    //         'id',
    //         'chart_of_account_id',
    //         'process_ors_id',
    //         'amount',
    //     ],
    // ]) 
    ?>
    <?php
    $ors  = ProcessOrs::findOne($model->process_ors_id);
    ?>
    <div class="container">

        <table class="table table-striped">

            <thead>
                <th>
                    ID
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
                foreach ($ors->raouds as $key =>$val) {

                    echo "
                    <tr>
                        <td>
                           {$key}
                        </td>
                        <td>
                           {$val->id}
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

</div>
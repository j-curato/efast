<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = 'Update Dv Aucs: ' . $model->model1->dv_number;
$this->params['breadcrumbs'][] = ['label' => 'Dv Aucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->model1->id, 'url' => ['view', 'id' => $model->model1->id]];
$this->params['breadcrumbs'][] = 'Update';

$reporting_period = $model->model1->reporting_period;
$nature_of_transaction = $model->model1->nature_of_transaction_id;
$book = $model->model1->book_id;
$transaction_type = strtolower($model->model1->transaction_type);
$mrd_classification = $model->model1->mrd_classification_id;
$particular = $model->model1->particular;

$payee_query   = Yii::$app->db->createCommand("SELECT id,account_name   FROM payee WHERE payee.id = :id")
    ->bindValue(':id', $model->model1->payee_id)
    ->queryAll();

$payee = ArrayHelper::map($payee_query, 'id', 'account_name');


?>
<div class="dv-aucs-update">



    <?= $this->render('_form', [
        // 'searchModel' => $searchModel,
        // 'dataProvider' => $dataProvider,
        'model' => $model,
        // 'reporting_period' => $reporting_period,
        // 'nature_of_transaction' => $nature_of_transaction,
        // 'book' => $book,
        // 'payee' => $payee,
        // 'transaction_type' => $transaction_type,
        // 'mrd_classification' => $mrd_classification,
        // 'particular' => $particular,
        // 'accounting_entries' => $accounting_entries,
        // 'dv_items' => $dv_items,
        'items' => $items,
        'accItems' => $accItems,
        'advancesModel' => $advancesModel,
        'advancesItems' => $advancesItems,
    ]) ?>

</div>
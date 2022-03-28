<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = 'Create Dv Aucs';
$this->params['breadcrumbs'][] = ['label' => 'Dv Aucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$reporting_period = $model->reporting_period;
$nature_of_transaction = $model->nature_of_transaction_id;
$book = $model->book_id;
$payee = $model->payee_id;
$transaction_type = $model->transaction_type;
$mrd_classification = $model->mrd_classification_id;
$particular = $model->particular;
?>
<div class="dv-aucs-create">


  <?= $this->render('_form_new', [
    'dataProvider' => $dataProvider,
    'searchModel' => $searchModel,
    'model' => $model,
    'reporting_period' => $reporting_period,
    'nature_of_transaction' => $nature_of_transaction,
    'book' => $book,
    'payee' => $payee,
    'transaction_type' => $transaction_type,
    'mrd_classification' => $mrd_classification,
    'particular' => $particular,
  ]) ?>

</div>
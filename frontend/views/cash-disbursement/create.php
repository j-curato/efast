<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashDisbursement */

$this->title = 'Create Cash Disbursement';
$this->params['breadcrumbs'][] = ['label' => 'Cash Disbursements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-disbursement-create">


    <?= $this->render('_form', [
        'dvSearchModel' => $dvSearchModel,
        'dvDataProvider' => $dvDataProvider,
        'model' => $model,
        'items' => $items,
    ]) ?>

</div>
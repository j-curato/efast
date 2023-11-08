<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashDeposits */

$this->title = 'Create Cash Deposits';
$this->params['breadcrumbs'][] = ['label' => 'Cash Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-deposits-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

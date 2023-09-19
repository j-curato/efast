<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */

$this->title = 'Update Bank Account: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-account-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

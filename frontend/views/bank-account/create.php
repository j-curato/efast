<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */

$this->title = 'Create Bank Account';
$this->params['breadcrumbs'][] = ['label' => 'Bank Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-account-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

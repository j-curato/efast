<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankAccountClosure */

$this->title = 'Update  Bank Account Closure: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Account Closures', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-bank-account-closure-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

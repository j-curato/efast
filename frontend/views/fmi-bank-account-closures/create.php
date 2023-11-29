<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankAccountClosure */

$this->title = 'Create Fmi Bank Account Closure';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Account Closures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-bank-account-closure-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

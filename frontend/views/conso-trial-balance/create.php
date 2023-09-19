<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConsoTrialBalance */

$this->title = 'Create Conso Trial Balance';
$this->params['breadcrumbs'][] = ['label' => 'Conso Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conso-trial-balance-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

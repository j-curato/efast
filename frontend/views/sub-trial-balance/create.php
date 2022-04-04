<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SubTrialBalance */

$this->title = 'Create Sub Trial Balance';
$this->params['breadcrumbs'][] = ['label' => 'Sub Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sub-trial-balance-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

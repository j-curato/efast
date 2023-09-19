<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrialBalance */

$this->title = 'Create Trial Balance';
$this->params['breadcrumbs'][] = ['label' => 'Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trial-balance-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

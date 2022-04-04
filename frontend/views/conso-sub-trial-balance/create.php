<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ConsoSubTrialBalance */

$this->title = 'Create Conso Sub Trial Balance';
$this->params['breadcrumbs'][] = ['label' => 'Conso Sub Trial Balances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="conso-sub-trial-balance-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

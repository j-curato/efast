<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChartOfAccounts */

$this->title = 'Update Chart Of Accounts: ' . $model->uacs;
$this->params['breadcrumbs'][] = ['label' => 'Chart Of Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="chart-of-accounts-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

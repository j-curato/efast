<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ChartOfAccounts */

$this->title = 'Create Chart Of Accounts';
$this->params['breadcrumbs'][] = ['label' => 'Chart Of Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chart-of-accounts-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

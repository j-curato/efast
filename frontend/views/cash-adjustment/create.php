<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CashAdjustment */

$this->title = 'Create Cash Adjustment';
$this->params['breadcrumbs'][] = ['label' => 'Cash Adjustments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cash-adjustment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

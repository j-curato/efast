<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BudgetEntries */

$this->title = 'Update Budget Entries: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Budget Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="budget-entries-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

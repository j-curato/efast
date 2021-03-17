<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BudgetEntries */

$this->title = 'Create Budget Entries';
$this->params['breadcrumbs'][] = ['label' => 'Budget Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-entries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

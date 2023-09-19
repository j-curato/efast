<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevAccountingEntries */

$this->title = 'Update Jev Accounting Entries: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jev Accounting Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jev-accounting-entries-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

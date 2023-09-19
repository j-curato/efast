<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\recordAllotmentEntries */

$this->title = 'Create Record Allotment Entries';
$this->params['breadcrumbs'][] = ['label' => 'Record Allotment Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotment-entries-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

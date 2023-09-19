<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevAccountingEntries */

$this->title = 'Create Jev Accounting Entries';
$this->params['breadcrumbs'][] = ['label' => 'Jev Accounting Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jev-accounting-entries-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */

$this->title = 'Update Check Range: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="check-range-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

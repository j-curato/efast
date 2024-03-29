<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NoticeOfPostponement */

$this->title = 'Update Notice Of Postponement: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Notice Of Postponements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notice-of-postponement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoAlphalist */

$this->title = 'Update Ro Alphalist: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ro-alphalist-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

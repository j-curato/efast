<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Alphalist */

$this->title = 'Update Alphalist: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="alphalist-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

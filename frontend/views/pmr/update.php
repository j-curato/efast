<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pmr */

$this->title = 'Update PMR: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pmrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pmr-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

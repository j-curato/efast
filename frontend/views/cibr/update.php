<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cibr */

$this->title = 'Update Cibr: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cibrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cibr-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
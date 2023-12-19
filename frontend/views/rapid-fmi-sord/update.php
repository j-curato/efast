<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RapidFmiSord */

$this->title = 'Update Rapid FMI SORD: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rapid FMI SORDs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rapid-fmi-sord-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

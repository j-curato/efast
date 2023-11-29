<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiSubprojects */

$this->title = 'Update  Subprojects: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => ' Subprojects', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-subprojects-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
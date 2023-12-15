<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RapidMgSord */

$this->title = 'Update Rapid Mg Sord: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rapid Mg Sords', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rapid-mg-sord-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiTranches */

$this->title = 'Update Fmi Tranches: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Tranches', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-tranches-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiPhysicalProgress */

$this->title = 'Create Fmi Physical Progress';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Physical Progresses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-physical-progress-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

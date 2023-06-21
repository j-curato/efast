<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = 'Create Transmittal';
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transmittal-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => []
    ]) ?>

</div>
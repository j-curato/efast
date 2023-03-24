<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Derecognition */

$this->title = 'Create Derecognition';
$this->params['breadcrumbs'][] = ['label' => 'Derecognitions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="derecognition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'propertyDetails' => [],
    ]) ?>

</div>
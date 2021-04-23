<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transmittal */

$this->title = 'Create Transmittal';
$this->params['breadcrumbs'][] = ['label' => 'Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transmittal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'model' => $model,
    ]) ?>

</div>

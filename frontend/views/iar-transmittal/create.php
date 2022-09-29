<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\IarTransmittal */

$this->title = 'Create IAR Transmittal';
$this->params['breadcrumbs'][] = ['label' => 'IAR Transmittals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-transmittal-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'action' => $action,
    ]) ?>

</div>
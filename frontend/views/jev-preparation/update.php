<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\JevPreparation */

$this->title = 'Update Jev Preparation: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Jev Preparations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="jev-preparation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'model' => $model,
        'modelJevItems'=>$modelJevItems
    ]) ?>

</div>

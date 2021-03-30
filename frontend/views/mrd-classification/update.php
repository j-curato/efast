<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MrdClassification */

$this->title = 'Update Mrd Classification: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mrd Classifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mrd-classification-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

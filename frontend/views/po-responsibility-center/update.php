<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoResponsibilityCenter */

$this->title = 'Update Po Responsibility Center: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Po Responsibility Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="po-responsibility-center-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

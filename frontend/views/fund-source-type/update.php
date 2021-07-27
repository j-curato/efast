<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FundSourceType */

$this->title = 'Update Fund Source Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Source Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fund-source-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

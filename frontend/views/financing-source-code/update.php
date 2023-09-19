<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FinancingSourceCode */

$this->title = 'Update Financing Source Code: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Financing Source Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="financing-source-code-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

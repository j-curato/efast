<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MfoPapCode */

$this->title = 'Update Mfo Pap Code: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mfo Pap Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mfo-pap-code-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

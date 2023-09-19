<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthorizationCode */

$this->title = 'Update Authorization Code: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authorization Codes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="authorization-code-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

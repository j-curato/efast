<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */

$this->title = 'Update Rpcppe: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rpcppes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rpcppe-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

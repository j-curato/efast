<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */

$this->title = 'Update Rpcppe: ' . $model->rpcppe_number;
$this->params['breadcrumbs'][] = ['label' => 'Rpcppes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rpcppe_number, 'url' => ['view', 'id' => $model->rpcppe_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rpcppe-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

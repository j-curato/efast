<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rpcppe */

$this->title = 'Create Rpcppe';
$this->params['breadcrumbs'][] = ['label' => 'Rpcppes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rpcppe-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NatureOfTransaction */

$this->title = 'Update Nature Of Transaction: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Nature Of Transactions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="nature-of-transaction-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */

$this->title = 'Update Pr Iar: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';


?>
<div class="pr-iar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'iar_items'=>!empty($iar_items)?$iar_items:[]
    ]) ?>

</div>

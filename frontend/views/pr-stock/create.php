<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */

$this->title = 'Create  Stock';
$this->params['breadcrumbs'][] = ['label' => 'Pr Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-stock-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
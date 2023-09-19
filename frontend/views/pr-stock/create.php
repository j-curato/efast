<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */

$this->title = 'Create  Stock';
$this->params['breadcrumbs'][] = ['label' => 'Pr Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-stock-create">
    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
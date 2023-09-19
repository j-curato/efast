<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrStockType */

$this->title = 'Create Pr Stock Type';
$this->params['breadcrumbs'][] = ['label' => 'Pr Stock Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-stock-type-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

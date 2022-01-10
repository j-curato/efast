<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrStock */

$this->title = 'Create  Stock';
$this->params['breadcrumbs'][] = ['label' => 'Pr Stocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-stock-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

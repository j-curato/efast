<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SsfSpNum */

$this->title = 'Create SSF SF No.';
$this->params['breadcrumbs'][] = ['label' => 'Ssf Sp Nums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssf-sp-num-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
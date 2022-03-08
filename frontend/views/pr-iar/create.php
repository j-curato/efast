<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrIar */

$this->title = 'Create Pr Iar';
$this->params['breadcrumbs'][] = ['label' => 'Pr Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-iar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

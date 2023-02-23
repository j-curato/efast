<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Liquidation */

$this->title = 'Create Cancelled Check';
$this->params['breadcrumbs'][] = ['label' => 'Liquidations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="liquidation-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_cancelled_form', [
        'model' => $model,

    ]) ?>

</div>
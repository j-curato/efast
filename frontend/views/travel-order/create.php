<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TravelOrder */

$this->title = 'Create Travel Order';
$this->params['breadcrumbs'][] = ['label' => 'Travel Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="travel-order-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => [],
    ]) ?>

</div>

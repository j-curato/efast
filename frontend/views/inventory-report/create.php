<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InventoryReport */

$this->title = 'Create Inventory Report';
$this->params['breadcrumbs'][] = ['label' => 'Inventory Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inventory-report-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

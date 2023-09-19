<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectProcurement */

$this->title = 'Create  Project Procurement';
$this->params['breadcrumbs'][] = ['label' => 'Pr Project Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-project-procurement-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

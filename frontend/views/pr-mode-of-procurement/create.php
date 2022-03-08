<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrModeOfProcurement */

$this->title = 'Create Pr Mode Of Procurement';
$this->params['breadcrumbs'][] = ['label' => 'Pr Mode Of Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-mode-of-procurement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

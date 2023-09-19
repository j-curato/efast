<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LddapAdas */

$this->title = 'Create Lddap Adas';
$this->params['breadcrumbs'][] = ['label' => 'Lddap Adas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lddap-adas-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

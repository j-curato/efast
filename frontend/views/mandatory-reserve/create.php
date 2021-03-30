<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MandatoryReserve */

$this->title = 'Create Mandatory Reserve';
$this->params['breadcrumbs'][] = ['label' => 'Mandatory Reserves', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mandatory-reserve-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

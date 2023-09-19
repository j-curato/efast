<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrOffice */

$this->title = 'Create Pr Office';
$this->params['breadcrumbs'][] = ['label' => 'Pr Offices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-office-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

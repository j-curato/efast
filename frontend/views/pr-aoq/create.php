<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

$this->title = 'Create Pr Aoq';
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-aoq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

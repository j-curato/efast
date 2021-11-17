<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PpeCondition */

$this->title = 'Create Ppe Condition';
$this->params['breadcrumbs'][] = ['label' => 'Ppe Conditions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ppe-condition-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

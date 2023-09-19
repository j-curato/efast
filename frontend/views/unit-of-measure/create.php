<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UnitOfMeasure */

$this->title = 'Create Unit Of Measure';
$this->params['breadcrumbs'][] = ['label' => 'Unit Of Measures', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="unit-of-measure-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

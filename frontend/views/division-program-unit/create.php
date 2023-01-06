<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DivisionProgramUnit */

$this->title = 'Create Division Program Unit';
$this->params['breadcrumbs'][] = ['label' => 'Division Program Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-program-unit-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

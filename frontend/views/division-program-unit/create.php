<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DivisionProgramUnit */

$this->title = 'Create Division Program Unit';
$this->params['breadcrumbs'][] = ['label' => 'Division Program Units', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="division-program-unit-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

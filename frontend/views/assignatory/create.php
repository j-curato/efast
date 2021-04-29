<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Assignatory */

$this->title = 'Create Assignatory';
$this->params['breadcrumbs'][] = ['label' => 'Assignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assignatory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

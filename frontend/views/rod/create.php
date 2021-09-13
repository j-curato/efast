<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rod */

$this->title = 'Create Rod';
$this->params['breadcrumbs'][] = ['label' => 'Rods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rod-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

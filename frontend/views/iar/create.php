<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Iar */

$this->title = 'Create Iar';
$this->params['breadcrumbs'][] = ['label' => 'Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

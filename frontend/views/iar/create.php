<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Iar */

$this->title = 'Create Iar';
$this->params['breadcrumbs'][] = ['label' => 'Iars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="iar-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

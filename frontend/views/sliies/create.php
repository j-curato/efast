<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sliies */

$this->title = 'Create Sliies';
$this->params['breadcrumbs'][] = ['label' => 'Sliies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sliies-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

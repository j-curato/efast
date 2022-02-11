<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoRao */

$this->title = 'Create Ro Rao';
$this->params['breadcrumbs'][] = ['label' => 'Ro Raos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-rao-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

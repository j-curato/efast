<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoAlphalist */

$this->title = 'Create Ro Alphalist';
$this->params['breadcrumbs'][] = ['label' => 'Ro Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-alphalist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

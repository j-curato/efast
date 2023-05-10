<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoCheckRange */

$this->title = 'Create Ro Check Range';
$this->params['breadcrumbs'][] = ['label' => 'Ro Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-check-range-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

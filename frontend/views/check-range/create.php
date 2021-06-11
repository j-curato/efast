<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */

$this->title = 'Create Check Range';
$this->params['breadcrumbs'][] = ['label' => 'Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="check-range-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

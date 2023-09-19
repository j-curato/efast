<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */

$this->title = 'Create Check Range';
$this->params['breadcrumbs'][] = ['label' => 'Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="check-range-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

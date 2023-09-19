<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banks */

$this->title = 'Create Banks';
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banks-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

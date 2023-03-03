<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SsfSpStatus */

$this->title = 'Create SSF SP Status';
$this->params['breadcrumbs'][] = ['label' => 'Ssf Sp Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ssf-sp-status-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

$this->title = 'Create Par';
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="par-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

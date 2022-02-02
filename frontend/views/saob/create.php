<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Saob */

$this->title = 'Create Saob';
$this->params['breadcrumbs'][] = ['label' => 'Saobs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saob-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

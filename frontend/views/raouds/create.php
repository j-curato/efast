<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Raouds */

$this->title = 'Create Raouds';
$this->params['breadcrumbs'][] = ['label' => 'Raouds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="raouds-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
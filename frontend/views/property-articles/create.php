<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyArticles */

$this->title = 'Create Property Articles';
$this->params['breadcrumbs'][] = ['label' => 'Property Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-articles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

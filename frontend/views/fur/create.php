<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fur */

$this->title = 'Create Fur';
$this->params['breadcrumbs'][] = ['label' => 'Furs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fur-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

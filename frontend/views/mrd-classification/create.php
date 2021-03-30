<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MrdClassification */

$this->title = 'Create Mrd Classification';
$this->params['breadcrumbs'][] = ['label' => 'Mrd Classifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mrd-classification-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

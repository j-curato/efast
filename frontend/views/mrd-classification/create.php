<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MrdClassification */

$this->title = 'Create Mrd Classification';
$this->params['breadcrumbs'][] = ['label' => 'Mrd Classifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mrd-classification-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

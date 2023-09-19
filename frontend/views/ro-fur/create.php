<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoFur */

$this->title = 'Create Ro Fur';
$this->params['breadcrumbs'][] = ['label' => 'Ro Furs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-fur-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

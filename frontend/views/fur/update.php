<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fur */

$this->title = 'Update Fur: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Furs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fur-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

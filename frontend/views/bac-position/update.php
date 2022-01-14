<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BacPosition */

$this->title = 'Update Bac Position: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bac Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bac-position-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

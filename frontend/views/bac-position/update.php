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

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

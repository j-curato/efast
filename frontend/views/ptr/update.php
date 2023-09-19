<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */

$this->title = 'Update Ptr: ' . $model->ptr_number;
$this->params['breadcrumbs'][] = ['label' => 'Ptrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ptr_number, 'url' => ['view', 'id' => $model->ptr_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ptr-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Advances */

$this->title = 'Update Advances: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Advances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="advances-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form_new', [
        'model' => $model,
    ]) ?>

</div>

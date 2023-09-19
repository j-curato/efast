<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoAsignatory */

$this->title = 'Update Po Asignatory: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Po Asignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="po-asignatory-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

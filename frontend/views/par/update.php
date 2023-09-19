<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Par */

$this->title = 'Update Par: ' . $model->par_number;
$this->params['breadcrumbs'][] = ['label' => 'Pars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->par_number, 'url' => ['view', 'id' => $model->par_number]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="par-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

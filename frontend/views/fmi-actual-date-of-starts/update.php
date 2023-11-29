<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiActualDateOfStarts */

$this->title = 'Update Fmi Actual Date Of Starts: ' . $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Actual Date Of Starts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->serial_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fmi-actual-date-of-starts-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = 'Create Record Allotments';
$this->params['breadcrumbs'][] = ['label' => 'Record Allotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="record-allotments-create">
    <?= $this->render('_form_new', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrAoq */

$this->title = 'Create Pr Aoq';
$this->params['breadcrumbs'][] = ['label' => 'Pr Aoqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-aoq-create">


    <?= $this->render('_form', [
        'model' => $model,
        'aoq_entries' => !empty($aoq_entries) ? $aoq_entries : []
    ]) ?>

</div>
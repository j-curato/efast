<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AllotmentType */

$this->title = 'Create Allotment Type';
$this->params['breadcrumbs'][] = ['label' => 'Allotment Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="allotment-type-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

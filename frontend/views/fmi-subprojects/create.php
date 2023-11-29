<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiSubprojects */

$this->title = 'Create  Subprojects';
$this->params['breadcrumbs'][] = ['label' => ' Subprojects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-subprojects-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
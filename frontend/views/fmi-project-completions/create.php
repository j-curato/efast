<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiProjectCompletions */

$this->title = 'Create Fmi Project Completions';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Project Completions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-project-completions-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

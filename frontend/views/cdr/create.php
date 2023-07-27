<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cdr */

$this->title = 'Create Cdr';
$this->params['breadcrumbs'][] = ['label' => 'Cdrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cdr-create">


    <?= $this->render('_form_new', [
        'model' => $model,
    ]) ?>

</div>

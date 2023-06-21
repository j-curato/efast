<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Radai */

$this->title = 'Create Radai';
$this->params['breadcrumbs'][] = ['label' => 'Radais', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="radai-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => []
    ]) ?>

</div>
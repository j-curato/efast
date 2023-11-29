<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBatches */

$this->title = 'Create Fmi Batches';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Batches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-batches-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

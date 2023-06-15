<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rci */

$this->title = 'Create Rci';
$this->params['breadcrumbs'][] = ['label' => 'Rcis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rci-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items'=>[]
    ]) ?>

</div>

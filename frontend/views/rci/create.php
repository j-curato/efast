<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rci */

$this->title = 'Create RCI';
$this->params['breadcrumbs'][] = ['label' => 'RCIs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rci-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => []
    ]) ?>

</div>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RoAlphalist */

$this->title = 'Create Ro Alphalist';
$this->params['breadcrumbs'][] = ['label' => 'Ro Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ro-alphalist-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

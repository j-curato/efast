<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Alphalist */

$this->title = 'Create Alphalist';
$this->params['breadcrumbs'][] = ['label' => 'Alphalists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alphalist-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

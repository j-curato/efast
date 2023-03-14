<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Rlsddp */

$this->title = 'Create RLSDDP';
$this->params['breadcrumbs'][] = ['label' => 'Rlsddps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rlsddp-create">


    <?= $this->render('_form', [
        'model' => $model,
        'items' => []
    ]) ?>

</div>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pmr */

$this->title = 'Create PMR';
$this->params['breadcrumbs'][] = ['label' => 'Pmrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pmr-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

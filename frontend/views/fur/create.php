<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fur */

$this->title = 'Create Fur';
$this->params['breadcrumbs'][] = ['label' => 'Furs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fur-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

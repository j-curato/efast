<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cibr */

$this->title = 'Create Cibr';
$this->params['breadcrumbs'][] = ['label' => 'Cibrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cibr-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

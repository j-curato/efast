<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RapidMgSord */

$this->title = 'Create Rapid Mg Sord';
$this->params['breadcrumbs'][] = ['label' => 'Rapid Mg Sords', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rapid-mg-sord-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

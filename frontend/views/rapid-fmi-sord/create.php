<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\RapidFmiSord */

$this->title = 'Create Rapid FMI SORD';
$this->params['breadcrumbs'][] = ['label' => 'Rapid FMI SORDs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rapid-fmi-sord-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FmiActualDateOfStarts */

$this->title = 'Create Fmi Actual Date Of Starts';
$this->params['breadcrumbs'][] = ['label' => 'Fmi Actual Date Of Starts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fmi-actual-date-of-starts-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\OtherPropertyDetails */

$this->title = 'Create Other Property Details';
$this->params['breadcrumbs'][] = ['label' => 'Other Property Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="other-property-details-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

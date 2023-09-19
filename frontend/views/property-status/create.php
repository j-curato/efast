<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PropertyStatus */

$this->title = 'Create Property Status';
$this->params['breadcrumbs'][] = ['label' => 'Property Statuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="property-status-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

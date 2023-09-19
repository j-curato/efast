<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrackingSheet */

$this->title = 'Create Tracking Sheet';
$this->params['breadcrumbs'][] = ['label' => 'Tracking Sheets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tracking-sheet-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

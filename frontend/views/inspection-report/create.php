<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\InspectionReport */

$this->title = 'Create Inspection Report';
$this->params['breadcrumbs'][] = ['label' => 'Inspection Reports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="inspection-report-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

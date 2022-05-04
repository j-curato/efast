<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Remittance */

$this->title = 'Update Remittance: ' . $model->remittance_number;
$this->params['breadcrumbs'][] = ['label' => 'Remittances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="remittance-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessBurs */

$this->title = 'Create Process Burs';
$this->params['breadcrumbs'][] = ['label' => 'Process Burs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-burs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'update_id' => $update_id,
                'update' => $update,
    ]) ?>

</div>

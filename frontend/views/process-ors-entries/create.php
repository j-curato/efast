<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrsEntries */

$this->title = 'Create Process Ors Entries';
$this->params['breadcrumbs'][] = ['label' => 'Process Ors Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-entries-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'update_id' => $update_id,
    ]) ?>

</div>

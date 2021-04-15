<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrsEntries */
if (!empty($update_id)){
    $title='Adjust Process Ors Entries';
}
else
{
    $title='Create Process Ors Entries';
}
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Process Ors Entries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-entries-create">


    <?= $this->render('_form_new', [
        
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'update_id' => $update_id,
        'update'=>$update
    ]) ?>

</div>

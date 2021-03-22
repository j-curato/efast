<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProcessOrs */

$this->title = 'Create Process Ors';
$this->params['breadcrumbs'][] = ['label' => 'Process Ors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="process-ors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [

        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,

    ]) ?>

</div>
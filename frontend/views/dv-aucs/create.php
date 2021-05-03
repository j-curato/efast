<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\DvAucs */

$this->title = 'Create Dv Aucs';
$this->params['breadcrumbs'][] = ['label' => 'Dv Aucs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dv-aucs-create">


    <?= $this->render('_form_new', [
      'dataProvider' => $dataProvider,
      'searchModel' => $searchModel,
      'update_id'=>$update_id
    ]) ?>

</div>
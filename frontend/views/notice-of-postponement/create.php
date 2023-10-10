<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\NoticeOfPostponement */

$this->title = 'Create Notice Of Postponement';
$this->params['breadcrumbs'][] = ['label' => 'Notice Of Postponements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-of-postponement-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

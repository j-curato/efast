<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cdr */

$this->title = 'Update Cdr: ' . 1;
$this->params['breadcrumbs'][] = ['label' => 'Cdrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 1, 'url' => ['view', 'id' => 1]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cdr-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form_new', [
        'model' => $model,
        'dataProvider' => $dataProvider,
        'reporting_period' => $reporting_period,
        'province' => $province,
        'consolidated' => $consolidated,
        'book' => $book,
        'cdr' => $cdr,
    ]) ?>

</div>
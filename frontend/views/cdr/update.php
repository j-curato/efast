<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cdr */

$this->title = 'Update Cdr: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cdrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 1, 'url' => ['view', 'id' => 1]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cdr-update">


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

<style>
@media print{
    h1{
        display: none;
    }
}
</style>
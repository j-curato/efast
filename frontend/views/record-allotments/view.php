<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RecordAllotments */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Record Allotments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="record-allotments-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'document_recieve_id',
            'fund_cluster_code_id',
            'financing_source_code_id',
            'fund_category_and_classification_code_id',
            'authorization_code_id',
            'mfo_pap_code_id',
            'fund_source_id',
            'reporting_period',
            'serial_number',
            'allotment_number',
            'date_issued',
            'valid_until',
            'particulars',
        ],
    ]) ?>

</div>

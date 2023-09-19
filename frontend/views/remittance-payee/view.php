<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RemittancePayee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Remittance Payees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="remittance-payee-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::button('<i class="fa fa-pencil-alt"></i> Update', ['value' => Url::to(yii::$app->request->baseUrl . '/index.php?r=remittance-payee/update&id=' . $model->id), 'id' => 'modalButtoncreate', 'class' => 'btn btn-success', 'data-placement' => 'left', 'data-toggle' => 'tooltip', 'title' => 'Add Sector']); ?>

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
            'payee.account_name',
            [
                'label'=>'General Ledger',
                'attribute'=>'generalLedger.general_ledger'
            ],
            'objec_code'
        ],
    ]) ?>

</div>
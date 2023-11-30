<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiBankDepositTypes */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Bank Deposit Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-bank-deposit-types-view">

    <div class="container">
        <div class="card p-2">
            <span>
                <?= Yii::$app->user->can('update_fmi_bank_deposit_type') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) : '' ?>
            </span>
        </div>
        <div class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'deposit_type',
                ],
            ]) ?>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-account-view">



    <div class="container card" style="padding: 1rem;">
        <p>
            <?= Html::a('<i class="fa fa-pencil-alt"></i> update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']); ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'account_number',
                'province',
                'created_at',
            ],
        ]) ?>
    </div>
</div>
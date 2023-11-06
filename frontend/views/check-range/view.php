<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CheckRange */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="check-range-view">


    <div class="container card" style="padding: 1rem;">
        <p>
            <?= Html::a('<i class="glyphicon glyphicon-pencil"></i> Update', ['update', 'id' =>  $model->id], ['class' => 'mdModal btn btn-primary']); ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'from',
                'to',
                'reporting_period',
                'begin_balance',
                'bankAccount.account_number'
            ],
        ]) ?>

    </div>
</div>
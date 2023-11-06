<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MonthlyLiquidationProgram */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Monthly Liquidation Programs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="monthly-liquidation-program-view">


    <div class="container card" style="padding:1rem">
        <p>

            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'reporting_period',
                'amount',
                'book_id',
                'province',
                'fund_source_type',
                'created_at',
            ],
        ]) ?>

    </div>
</div>
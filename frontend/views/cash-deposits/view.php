<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashDeposits */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Cash Deposits', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cash-deposits-view">

    <div class="card container p-3">

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'fk_mgrfr_id',
                    'value' => 'mgrfr.serial_number',
                ],
                [
                    'label' => 'Province',
                    'value' => function ($model) {
                        return $model->mgrfr->province->province_name;
                    },
                ],
                [
                    'label' => 'City/Municipality',
                    'value' => function ($model) {

                        return $model->mgrfr->municipality->municipality_name;
                    },
                ],
                [
                    'label' => 'Barangay',
                    'value' => function ($model) {

                        return $model->mgrfr->barangay->barangay_name;
                    },
                ],
                [
                    'label' => 'Purok/Sitio',
                    'value' => function ($model) {

                        return $model->mgrfr->purok;
                    },
                ],

                'serial_number',
                'reporting_period',
                'date',
                'particular',
                'matching_grant_amount',
                'equity_amount',
                'other_amount',

            ],
        ]) ?>
    </div>


</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrContractType */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Contract Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-contract-type-view">

    <div class="container card" style="padding: 1rem;">



        <p>

            <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['create'], ['class' => 'btn btn-success modalButtonCreate']) ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'contract_name',
            ],
        ]) ?>
    </div>

</div>
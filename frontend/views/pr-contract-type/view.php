<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\JqueryAsset;
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
            <?= Yii::$app->user->can('update_contract_type') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
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

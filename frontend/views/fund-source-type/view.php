<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FundSourceType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Source Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fund-source-type-view">

    <div class="container card" style="padding:2rem">

        <p>
            <?= Yii::$app->user->can('update_fund_source_type') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) : '' ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
            ],
        ]) ?>
    </div>

</div>

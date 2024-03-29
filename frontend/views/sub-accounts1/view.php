<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;
use aryelds\sweetalert\SweetAlertAsset;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts1 */

$this->title = $model->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sub-accounts1-view">
    <div class="container card" style="padding: 2rem;">
        <p>
            <?= Yii::$app->user->can('update_sub_account_1') ?  Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary ']) : ''; ?>
            <?= Yii::$app->user->can('create_sub_account_2') ? Html::a('<i class="fa fa-plus"></i> Create Sub-Account2', ['sub-accounts2/create', 'subAcc1Id' => $model->id], ['class' => 'btn btn-success mdModal']) : ''; ?>
        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'chart_of_account_id',
                'object_code',
                'name',
            ],
        ]) ?>
    </div>

</div>
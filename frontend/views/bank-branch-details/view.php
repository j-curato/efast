<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BankBranchDetails */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bank Branch Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bank-branch-details-view">


    <p>
        <?= Yii::$app->user->can('update_bank_branch_details') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fk_bank_branch_id',
            'address:ntext',
            'bank_manager',
        ],
    ]) ?>

</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MajorAccounts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Major Accounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="major-accounts-view">

    <p>
        <?= Yii::$app->user->can('update_major_account') ? Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'object_code',
        ],
    ]) ?>

</div>
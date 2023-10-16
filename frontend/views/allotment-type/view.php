<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AllotmentType */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Allotment Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="allotment-type-view">


    <p>
        <?= Yii::$app->user->can('update_allotment_type') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type:ntext',
        ],
    ]) ?>

</div>
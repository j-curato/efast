<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MrdClassification */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Mrd Classifications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="mrd-classification-view">

    <p>
        <?= Yii::$app->user->can('update_mrd_classification') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',
        ],
    ]) ?>

</div>

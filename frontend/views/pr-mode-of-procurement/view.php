<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrModeOfProcurement */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Mode Of Procurements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-mode-of-procurement-view">


    <div class="container card" style="padding: 1rem;">

        <p>
            <?= Html::a('<i class="fa fa-pencil-alt"></i> Create', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']); ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'mode_name',
                'description',
            ],
        ]) ?>
    </div>

</div>
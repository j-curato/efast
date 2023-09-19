<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PoResponsibilityCenter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Po Responsibility Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="po-responsibility-center-view">


    <div class="container card" style="padding: 1rem;">

        <p>
            <?= Html::a(' Update', ['update', 'id' =>  $model->id], ['class' => 'btn btn-primary modalButtonUpdate']); ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'province',
                'name',
                'description:ntext',
            ],
        ]) ?>
    </div>

</div>


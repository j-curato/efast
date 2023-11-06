<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PoAsignatory */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Po Asignatories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="po-asignatory-view">


    <div class="container card" style="padding:1rem">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']); ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'name',
                'position',
            ],
        ]) ?>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BacPosition */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bac Positions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bac-position-view">

    <div class="container card" style="padding: 1rem;">


        <p>
            <?= Yii::$app->user->can('update_bac_position') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'position',
            ],
        ]) ?>
    </div>

</div>
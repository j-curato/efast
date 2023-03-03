<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Citymun */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Citymuns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="citymun-view container panel panel-default">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'city_mun',
        ],
        'options' => [
            'class' => 'table detail-view'
        ]
    ]) ?>

</div>
<style>
    .container {
        padding: 3rem;
    }
</style>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>
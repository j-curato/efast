<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SsfSpNum */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ssf Sp Nums', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ssf-sp-num-view container panel panel-default">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary lrgModal']) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'serial_number',
            'budget_year',
            'fk_citymun_id',
            [
                'attribute' => 'fk_office_id',
                'value' => function ($model) {
                    return $model->office->office_name;
                }
            ],
            [
                'attribute' => 'fk_citymun_id',
                'value' => function ($model) {
                    return $model->city->city_mun;
                }
            ],
            'project_name:ntext',
            'cooperator',
            'equipment:ntext',
            'amount',
            'date',
        ],
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
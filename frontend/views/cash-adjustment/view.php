<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CashAdjustment */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cash Adjustments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cash-adjustment-view">


    <div class="container card" style="padding: 1rem;">

        <p>
            <?= Yii::$app->user->can('update_laps_amounts') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) : '' ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'book_id',
                    'value' => function ($model) {
                        return $model->book->name;
                    }
                ],
                'particular:ntext',
                'date',
                'amount',
            ],
        ]) ?>
    </div>

</div>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]) ?>
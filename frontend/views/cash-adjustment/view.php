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

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>

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
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]) ?>
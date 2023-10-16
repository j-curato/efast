<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\RoCheckRange */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ro Check Ranges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ro-check-range-view">


    <p>
        <?= Yii::$app->user->can('update_ro_check_range') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'book.name',
            'from',
            'to',
            [
                'attribute' => 'check_type',
                'value' => function ($model) {
                    $c = '';
                    if ($model->check_type == 1) {
                        $c = 'LBP Check';
                    } else if ($model->check_type == 1) {
                        $c = 'eCheck';
                    }
                    return $c;
                }
            ]
        ],
    ]) ?>

</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [JqueryAsset::class]]);
?>
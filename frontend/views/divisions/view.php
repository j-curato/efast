<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Divisions */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Divisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="divisions-view">


    <p>
        <?= Yii::$app->user->can('update_division') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary mdModal']) : '' ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'division',
            [
                'label' => 'Division Chief',
                'attribute' => 'fk_division_chief',
                'value' => function ($model) {
                    return $model->employee->f_name . ' ' . $model->employee->m_name[0] . ' ' . $model->employee->l_name;
                }
            ]

        ],
    ]) ?>

</div>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FmiActualDateOfStarts */

$this->title = $model->serial_number;
$this->params['breadcrumbs'][] = ['label' => 'Fmi Actual Date Of Starts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fmi-actual-date-of-starts-view">

    <div class="container">
        <card class=" card p-2">
            <span>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'mdModal btn btn-primary']) ?>
            </span>
        </card>
        <card class="card p-3">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'serial_number',
                    'fk_tbl_fmi_subproject_id',
                    'actual_date_of_start',
                ],
            ]) ?>
        </card>
    </div>




</div>
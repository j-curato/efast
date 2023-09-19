<?php

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Agency */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Agencies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agency-view container card">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'type',
        ],
    ]) ?>

</div>
<?php
$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => JqueryAsset::class]);
?>
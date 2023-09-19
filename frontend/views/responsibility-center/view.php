<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ResponsibilityCenter */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Responsibility Centers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="responsibility-center-view">

    <div class="container card" style="padding:2rem">


        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonUpdate']) ?>

        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'name',
                'description',
            ],
        ]) ?>
    </div>

</div>
<?php $this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]) ?>
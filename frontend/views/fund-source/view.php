<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FundSource */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Fund Sources', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="fund-source-view">


    <p>
        <?= Yii::$app->user->can('update_fund_source') ? Html::a('<i class="fa fa-pencil-alt"></i> Update', ['update', 'id' => $model->id], ['class' => 'modalButtonUpdate btn btn-primary']) : '' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description',

        ],
    ]) ?>

</div>
<?php

$this->registerJsFile(
    '@web/frontend/web/js/globalFunctions.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
)
?>
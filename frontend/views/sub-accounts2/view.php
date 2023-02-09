<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SubAccounts2 */

$this->title = $model->object_code;
$this->params['breadcrumbs'][] = ['label' => 'Sub Accounts2s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="sub-accounts2-view">



    <div class="container panel panel-default" style="padding: 2rem;">
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary modalButtonCreate']) ?>

        </p>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'sub_accounts1_id',
                'object_code',
                'name',
                [
                    'attribute' => 'is_active',
                    'value' => function ($model) {

                        return $model->is_active === 1 ? 'True' : 'False';
                    }
                ],
            ],
        ]) ?>
    </div>
</div>
<?php

$this->registerJsFile('@web/frontend/web/js/globalFunctions.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
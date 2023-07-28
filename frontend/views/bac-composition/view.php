<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BacComposition */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bac Compositions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="bac-composition-view">

    <div class="container">

        <table>

            <tbody>
                <tr>
                    <th>Effectivity Date :
                        &emsp;
                        &emsp;
                        &emsp;
                        <span><?= DateTime::createFromFormat('Y-m-d',$model->effectivity_date)->format('F d, Y') ?></span>
                    </th>
                </tr>
                <tr>
                    <th>Expiration Date
                        &emsp;
                        &emsp;
                        &emsp;
                        <span><?= DateTime::createFromFormat('Y-m-d',$model->expiration_date)->format('F d, Y') ?></span>
                    </th>
                </tr>
                <tr>
                    <th>RSO Number

                        &emsp;
                        &emsp;
                        &emsp;
                        <span><?= $model->rso_number ?></span>
                    </th>
                </tr>
                <th style="text-align: center;">Members</th>
                <?php

                foreach ($model->bacCompositionMembers as $val) {
                    $name = strtoupper($val->employee->f_name . ' ' . $val->employee->m_name[0] . '. ' . $val->employee->l_name);
                    $position = strtoupper($val->bacPosition->position);
                    echo "<tr>
                    <th style='text-align:center'>
                    <span> {$name}</span>

                    <br>
                    <span>
                    
                    {$position}
                    </span>
                    </th>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


</div>

<style>
    .container {
        background-color: white;
        padding: 4rem;
    }

    .left {
        text-align: left;
    }

    table {
        width: 100%;
    }

    td,
    th {
        padding: 1rem;
    }
</style>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Ptr */

$this->title = $model->ptr_number;
$this->params['breadcrumbs'][] = ['label' => 'Ptrs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ptr-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->ptr_number], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->ptr_number], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table>
        <thead>
            <tr>
                <th colspan="3">
                    <span>Entity Name : </span>
                    <span>_________________</span>
                </th>
                <th colspan="3">
                    <span>Fund Cluster :</span>
                    <span>_________________</span>
                </th>
            </tr>
            <tr>
                <th colspan="3">
                    <span>Fom Accountable Officer/Agency/Fund Cluster:</span>
                    <span>_______________________</span>
                    <br>
                    <span>To Accountable Officer/Agency/Fund CLuster:</span>
                    <span>_______________________</span>
                </th>
                <th colspan="3">
                    <span>PTR No. :</span>
                    <span>____________________</span>
                    <br>
                    <span>Date: </span>
                    <span>____________________</span>
                </th>
            </tr>
            <tr>
                <th colspan="6" style="width: 100px;">
                    <div style="width: 600px; border:1px solid red">


                        <span style="width:100px;margin-right: auto;">
                            <span class="chk_box">....</span>

                            Donation
                        </span>

                        <span style="width:100px;margin-left:auto;">
                            <span class="chk_box">....</span>

                            Relocate
                        </span>
                        <!-- <span>&#10003;</span> -->
                        <br>
                        <span style="width: 30px;">

                            <span class="chk_box">....</span>
                            Reassignment
                        </span>
                        <span style="width: 30px;margin-left:auto;">

                            <span class="chk_box" style="width:12px">....</span>
                            Others (Specify) __________________
                        </span>
                    </div>


                </th>
            </tr>
            <tr>
                <th colspan="2">Date Acquired</th>
                <th>Property No.</th>
                <th>Description</th>
                <th>Amount </th>
                <th>Condtion of PPE</th>
            </tr>
            <tr>
                <td style="padding: 0;" id="q">
                    <div class='editable' contenteditable style="width: 100%;height:100%;">I'm editable</div>
                </td>
                <td>
                    <div contenteditable>I'm also editable</div>
                </td>
            </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="6">
                    <span>Reason for Transfer:</span>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>Aprroved By:</td>
                <td>Released/Issued By:</td>
                <td>Recieved By:</td>
            </tr>
            <tr>
                <td>Signature:</td>
                <td>________________</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Printed Name:</td>
                <td>________________</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Designation:</td>
                <td>________________</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
            <tr>
                <td>Date:</td>
                <td>________________</td>
                <td>________________</td>
                <td>________________</td>
            </tr>
        </tfoot>
    </table>
</div>
<style>
    [contenteditable="true"] {
        padding: 20px;
    }

    table,
    th,
    td {
        border: 1px solid black;
        padding: 12px;
    }

    .chk_box {
        border: 1px solid black;
        color: white;

    }

    @media print {
        .chk_box {
            color: white;
        }
    }
</style>
<script>
    $('.editable').focusout(() => {
        console.log('qweqwe')
    })
</script>
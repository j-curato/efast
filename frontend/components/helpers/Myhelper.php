<?php

namespace app\components\helpers;

use app\models\ChartOfAccounts;
use app\models\Office;
use app\models\SubAccounts1;
use Da\QrCode\QrCode;
use ErrorException;
use Yii;
use yii\base\BaseObject;
use yii\db\Query;
use yii\helpers\Html;

class MyHelper extends BaseObject
{
    public static function post($name)
    {
        return Yii::$app->request->post($name);
    }
    public static function uuid()
    {
        return Yii::$app->db->createCommand('SELECT UUID_SHORT()')->queryScalar();
    }
    public static function checkAllotmentBalance(
        $allotment_id,
        $amount = 0,
        $prAllotmentId = null,
        $txnAllotmentId = null,
        $txnPrItmId = null,
        $orsItmId = null,
        $orsTxnItmId = null
    ) {
        $cur_balance = 0;
        try {
            $balance = Yii::$app->db->createCommand("CALL
            GetAllotmentBalance(
            :allotment_id,
            :prAllotmentId,
            :txnAllotmentId,
            :txnPrItmId,
            :orsItmId,
            :orsTxnItmId)")
                ->bindValue(':allotment_id', $allotment_id)
                ->bindValue(':prAllotmentId', $prAllotmentId)
                ->bindValue(':txnAllotmentId', $txnAllotmentId)
                ->bindValue(':txnPrItmId', $txnPrItmId)
                ->bindValue(':orsItmId', $orsItmId)
                ->bindValue(':orsTxnItmId', $orsTxnItmId)
                ->queryScalar();
            $cur_balance = floatval($balance) - floatval($amount);

            if ($cur_balance < 0) {
                throw new ErrorException("Allotment Amount Cannot be more than " . number_format($balance, 2));
            }
        } catch (ErrorException $e) {
            return $e->getMessage();
        }

        return true;
    }
    public static function gridDefaultAction($id, $class = 'modalButtonUpdate')
    {
        return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $id])
            . ' ' . Html::a('<i class="fa fa-pencil"></i>', ['update', 'id' => $id], ['class' => $class]);
    }
    public  static function getEmployee($id, $qry_type = 'all')
    {
        // $query =   Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE  employee_id = :id")
        //     ->bindValue(':id', $id);
        $query = new Query();
        $query->select(['employee_id', 'employee_name', 'position'])
            ->from('employee_search_view')
            ->where('employee_id = :id', ['id' => $id]);


        if ($qry_type === 'all') {
            $res =  $query->all();
        } else if ($qry_type === 'one') {
            $res = $query->one();
        }
        return $res;
    }
    public  static function beginTxn()
    {
        return YIi::$app->db->beginTransaction();
    }
    public  static function getUuid()
    {
        return YIi::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
    }
    public  static function getParNumber($office_id)
    {
        $office_name = Office::findOne($office_id)->office_name;
        $query = Yii::$app->db->createCommand("call getLstParNum(:office_id)")
            ->bindValue(':office_id', $office_id)
            ->queryOne();
        $num = 1;
        if (!empty($query['vcnt_num'])) {
            $num = intval($query['vcnt_num']);
        } else if (!empty($query['lst_num'])) {
            $num = intval($query['lst_num']);
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = strtoupper($office_name) . '-PAR-' . $new_num;
        return $string;
    }
    public static function getPcNumber($office_id)
    {
        $office_name = Office::findOne($office_id)->office_name;
        $query = Yii::$app->db->createCommand("call getLstPcNum(:office_id)")
            ->bindValue(':office_id', $office_id)
            ->queryOne();

        // var_dump($query);
        // die();
        $num = 1;
        if (!empty($query['vcnt_num'])) {
            $num = intval($query['vcnt_num']);
        } else if (!empty($query['lst_num'])) {
            $num = intval($query['lst_num']);
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = strtoupper($office_name) . '-PC-' . $new_num;
        return $string;
    }
    public static function UdpateParCurUser($par_id, $property_id)
    {
        Yii::$app->db->createCommand("UPDATE par SET is_current_user=0 WHERE 
        par.fk_property_id =:property_id
        AND par.id != :par_id
        ")
            ->bindValue(':property_id', $property_id)
            ->bindValue(':par_id', $par_id)
            ->query();
    }
    public static function generateQr($num)
    {
        $text = $num;
        $path = 'qr_codes';
        $qrCode = (new QrCode($text))
            ->setSize(250);
        header('Content-Type: ' . $qrCode->getContentType());
        $base_path =  \Yii::getAlias('@webroot');
        $qrCode->writeFile($base_path . "/qr_codes/$text.png");
    }
    public static function createPropertySubAccount($chart_of_account_id = '', $property_number = '')
    {
        try {
            if (empty($chart_of_account_id) || empty($property_number)) {
                throw new ErrorException('Chart of Account and Property Number Cannot be empty');
            }
            $chart_uacs = ChartOfAccounts::find()
                ->where("id = :id", ['id' => $chart_of_account_id])->one();
            $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;
            $uacs = $chart_uacs->uacs . '_';
            for ($i = strlen($last_id); $i <= 4; $i++) {
                $uacs .= 0;
            }

            $account_title = $chart_uacs->general_ledger . '-' . $property_number;

            $check_if_exists = Yii::$app->db->createCommand("SELECT id FROM sub_accounts1 WHERE sub_accounts1.name = :account_title")
                ->bindValue(':account_title', $account_title)
                ->queryScalar();

            if (!empty($check_if_exists)) {
                return ['isSuccess' => true, 'id' => $check_if_exists];
            }
            $model = new SubAccounts1();
            $model->chart_of_account_id = $chart_of_account_id;
            $model->object_code = $uacs . $last_id;
            $model->name = $account_title;
            $model->is_active = 1;
            if (!$model->validate()) {
                throw new ErrorException(json_encode($model->errors));
            }
            if (!$model->save(false)) {

                throw new ErrorException("Save Sub Account Failed");
            }
        } catch (ErrorException $e) {
            return ['isSuccess' => false, 'error_message' => $e->getMessage()];
        }

        return ['isSuccess' => true, 'id' => $model->id];
    }
    public static function getPropertyCustodians()
    {
        $property_custodian_query = new Query();
        $property_custodian_query->select(['employee_id', 'UPPER(employee_name) as employee_name'])
            ->from('employee_search_view')
            ->andWhere('property_custodian  = 1');
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $property_custodian_query->andWhere('office_name =:office_name', ['office_name' => $user_data->office->office_name]);
        }
        $f_property_custodian_query = $property_custodian_query->all();
        return $f_property_custodian_query;
    }
}

<?php

namespace frontend\components;

use app\models\Books;
use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\Office;
use app\models\SubAccounts1;
use common\models\User;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use Da\QrCode\QrCode;
use DateTime;
use ErrorException;
use yii\db\Query;

class MyComponent extends Component
{

    public function getUserData()
    {

        return  User::find()
            ->joinWith('employee')
            ->joinWith('divisionName')
            ->joinWith('office')
            ->where('user.id = :id', ['id' => Yii::$app->user->identity->id])
            ->one();
    }
    public function updateEmployee($employee_id)
    {
        $query = YIi::$app->db->createCommand("SELECT employee_name,employee_id,position FROM employee_search_view WHERE employee_id = :employee_id ")
            ->bindValue(':employee_id', $employee_id)
            ->queryAll();
        return $query;
    }
    public function getDivisions($id = '')
    {
        if (!empty($id)) {
            return YIi::$app->db->createCommand("SELECT UPPER(divisions.division) as division,id FROM divisions")
                ->bindValue(':id', $id)
                ->queryOne();
        }
        return YIi::$app->db->createCommand("SELECT UPPER(divisions.division) as division,id FROM divisions")->queryAll();
    }
    public function getMfoPapCode()
    {
        return YIi::$app->db->createCommand("SELECT CONCAT(mfo_pap_code.`code`,'-',mfo_pap_code.`name`) as mfo,id FROM mfo_pap_code")->queryAll();
    }
    public function getFundSources()
    {
        return YIi::$app->db->createCommand("SELECT fund_source.`name`,id FROM fund_source")->queryAll();
    }
    public function getOffices()
    {
        return YIi::$app->db->createCommand("SELECT office.office_name,id FROM office")->queryAll();
    }

    public function serverIp()
    {
        $host = gethostname();
        $ip = gethostbyname($host);
        return $ip;
    }
    public function getRaoudSerialNumber($reporting_period, $book, $update_id)
    {


        $book = Books::findOne($book);

        // $q = RecordAllotments::find()
        // ->orderBy(['id' => SORT_DESC])
        // ->one();

        // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
        $f = (new \yii\db\Query())
            ->select('serial_number')
            ->from('record_allotments');
        !empty($update_id) ? $f->where("id =:id", ['id' => $update_id]) : $f->orderBy("id DESC");
        $q = $f->one();


        if (!empty($q)) {
            $x = explode('-', $q['serial_number']);
            $y = 1;
            if (!empty($update_id)) {
                $y = 0;
            }
            $last_number = $x[3] + $y;
        } else {
            $last_number = 1;
        }

        $serial_number = $book->name . '-' . $reporting_period . '-' . $last_number;
        return  $serial_number;
    }
    public function getAllotmentNumber($reporting_period, $book)
    {
        $book = Books::findOne($book);
        $year =  DateTime::createFromFormat('Y-m', $reporting_period)->format('Y');
        // $q = RecordAllotments::find()
        // ->orderBy(['id' => SORT_DESC])
        // ->one();

        // KUHAAON ANG SERIAL NUMBER SA LAST ID OR SA GE UPDATE NA ID
        $qry = YIi::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(record_allotments.serial_number,'-',-1)AS UNSIGNED) +1  as l_num FROM `record_allotments`
      WHERE record_allotments.reporting_period LIKE :_year
      ORDER BY l_num DESC LIMIT 1")
            ->bindValue(':_year', $year . '%')
            ->queryScalar();
        $num  = 1;
        if (!empty($qry)) {
            $num  = $qry;
        }
        $serial_number = $book->name . '-' . $reporting_period . '-' . $num;
        return  $serial_number;
    }

    public function getOrsBursRaoudSerialNumber($serial_number)
    {
        // $serial_number = "Fund 01-2021-01-3";
        $raoud = (new \yii\db\Query())
            ->select("serial_number")
            ->from('raouds')
            ->orderBy('id DESC')
            ->one();

        $x = explode('-', $serial_number);
        $x[3] = explode('-', $raoud['serial_number'])[3] + 1;
        $y = implode('-', $x);

        return $y;
    }
    public function cancel($id, $table, $type)
    {
        // $q=(new \yii\db\Query())
        // ->update("$table")
        // ->set('is_cancelled =:is_cancelled',['is_cancelled'=>$type])
        // ->where("id =:id",['id'=>$id]);

        \Yii::$app->db->createCommand("UPDATE :t SET is_cancelled = :tpe WHERE id = :id")
            ->bindValue(`:t`, $table)
            ->bindValue(':tpe', $type)
            ->bindValue(':id', $id)

            ->execute();
    }
    public function cibrCdrHeader($province)
    {
        $prov = [
            'adn' => [
                'province' => 'Agusan Del Norte',
                'officer' => 'Rosie R. Vellesco',
                'location' => 'Butuan City'
            ],
            'sdn' => [
                'province' => 'Surigao Del Norte',
                'officer' => 'Glenn Michael M. Goloran',
                'location' => 'Surigao City'
            ],

            'ads' => [
                'province' => 'Agusan Del Sur',
                'officer' => 'Maria Prescylin C. Lademora',
                'location' => 'San Francisco, ADS'
            ],
            'sds' => [
                'province' => 'Surigao Del Sur',
                'officer' => 'Sarah P. Estrada',
                'location' => 'Tandag City'
            ],
            'pdi' => [
                'province' => 'Dinagat Islands',
                'officer' => 'Edmar M. Granada',
                'location' => 'San Jose, PDI'
            ],


        ];
        return $prov[$province];
    }
    public function cdrFilterQuery($reporting_period, $book_name, $province, $report_type)
    {
        $query = (new \yii\db\Query())
            ->select('id,is_final')
            ->from('cdr')
            ->where('reporting_period =:reporting_period', ['reporting_period' => $reporting_period])
            ->andWhere('book_name =:book_name', ['book_name' => $book_name])
            ->andWhere('province LIKE :province', ['province' => $province])
            ->andWhere('report_type LIKE :report_type', ['report_type' => $report_type])
            ->orderBy('reporting_period')
            ->one();
        if (!empty($query)) {
            return $query;
        } else {
            return '';
        }
    }

    public function createSubAccount1($account_title, $id)
    {
        $model = new SubAccounts1();
        // $account_title = $_POST['account_title'];
        // $id = $_POST['id'];

        $chart_uacs = ChartOfAccounts::find()
            ->where("id = :id", ['id' => $id])->one()->uacs;
        $last_id = SubAccounts1::find()->orderBy('id DESC')->one()->id + 1;

        $uacs = $chart_uacs . '_';
        for ($i = strlen($last_id); $i <= 4; $i++) {
            $uacs .= 0;
        }
        // if ($account_title) {


        $model->chart_of_account_id = $id;
        $model->object_code = $uacs . $last_id;
        $model->name = $account_title;
        if ($model->validate()) {
            if ($model->save()) {
                // CONCAT(chart_of_accounts.id,'-',chart_of_accounts.uacs,'-',1) as code,
                return $model->id . '-' . $model->object_code . '-' . 1;
            }
        } else {
            // validation failed: $errors is an array containing error messages
            $errors = $model->errors;
            return json_encode($errors);
        }
    }
    public function getHiddenFormTokenField()
    {
        $token = \Yii::$app->getSecurity()->generateRandomString();
        $token = str_replace('+', '.', base64_encode($token));

        // \Yii::$app->session->set(\Yii::$app->params['form_token_param'], $token);
        return Html::input('text', 'token', $token);
    }

    public function generatePcQr($pc_number)
    {

        $text = $pc_number;
        $path = 'qr_codes';
        $qrCode = (new QrCode($text))
            ->setSize(250);
        header('Content-Type: ' . $qrCode->getContentType());
        $base_path =  \Yii::getAlias('@webroot');
        $qrCode->writeFile($base_path . "/qr_codes/$text.png");
    }

    public function getStockPart()
    {
        $q = [];


        $q['part-1'] =



            [


                ['type' => 'PERFUMES OR COLOGNES OR FRAGRANCES', 'object_code' => '5020301000'],
                ['type' => 'ALCOHOL OR ACETONE BASED ANTISEPTICS', 'object_code' => '5020301000'],
                ['type' => 'COLOR COMPOUNDS AND DISPERSIONS', 'object_code' => '5020301000'],
                ['type' => 'FILMS', 'object_code' => '5020301000'],
                ['type' => 'PAPER MATERIALS AND PRODUCTS', 'object_code' => '5020301000'],
                ['type' => 'BATTERIES AND CELLS AND ACCESSORIES', 'object_code' => '5020399000'],
                ['type' => 'MANUFACTURING COMPONENTS AND SUPPLIES', 'object_code' => '5020301000'],
                ['type' => 'HEATING AND VENTILATION AND AIR CIRCULATION', 'object_code' => '5020399000'],
                ['type' => 'MEDICAL THERMOMETERS AND ACCESSORIES', 'object_code' => '5020399000'],
                ['type' => 'LIGHTING AND FIXTURES AND ACCESSORIES', 'object_code' => '5020399000'],
                ['type' => 'MEASURING AND OBSERVING AND TESTING EQUIPMENT', 'object_code' => '5020301000'],
                ['type' => 'CLEANING EQUIPMENT AND SUPPLIES', 'object_code' => '5020399000'],
                ['type' => 'PERSONAL PROTECTIVE EQUIPMENT', 'object_code' => '5020399000'],
                ['type' => 'INFORMATION AND COMMUNICATION TECHNOLOGY (ICT) EQUIPMENT AND DEVICES AND ACCESSORIES', 'object_code' => '5020301000'],
                ['type' => 'OFFICE EQUIPMENT AND ACCESSORIES AND SUPPLIES', 'object_code' => '5020301000'],
                ['type' => 'PRINTER OR FACSIMILE OR PHOTOCOPIER SUPPLIES', 'object_code' => '5020301000'],
                ['type' => 'AUDIO AND VISUAL EQUIPMENT AND SUPPLIES', 'object_code' => '5060405003'],
                ['type' => 'FLAG OR ACCESSORIES', 'object_code' => '5020399000'],
                ['type' => 'PRINTED PUBLICATIONS', 'object_code' => '5020399000'],
                ['type' => 'FIRE FIGHTING EQUIPMENT', 'object_code' => '5020399000'],
                ['type' => 'CONSUMER ELECTRONICS', 'object_code' => '5060602000'],
                ['type' => 'FURNITURE AND FURNISHINGS', 'object_code' => '5020399000'],
                ['type' => 'ARTS AND CRAFTS EQUIPMENT AND ACCESSORIES AND SUPPLIES', 'object_code' => '5020301000'],
                ['type' => 'SOFTWARE', 'object_code' => '5060602000'],
            ];

        $q['part-2'] = [
            ['type' => 'Common Electrical Supplies', 'object_code' => '5020301000'],
            ['type' => 'Common Office Supplies', 'object_code' => '5020301000'],
            ['type' => 'Construction Supplies', 'object_code' => '5020399000'],
            ['type' => 'Common Janitorial Supplies', 'object_code' => '5020399000'],
            ['type' => 'Consumables', 'object_code' => '5020301000'],
            ['type' => 'Military, Police,and Traffic Supplies', 'object_code' => '5020312000'],
            ['type' => 'Clothing, textiles and Accessories', 'object_code' => '5020399000'],



        ];
        $q['part-3'] = [



            ['type' => 'Accountable Forms Expenses'],
            ['type' => 'Agricultural and Marine Supplies Expenses'],
            ['type' => 'Animal/Zoological Supplies Expenses'],
            ['type' => 'Food Supplies Expenses'],
            ['type' => 'Welfare Goods Expenses'],
            ['type' => 'Drugs and Medicines Expenses'],
            ['type' => 'Medical, Dental and Laboratory Supplies Expenses'],
            ['type' => 'Fuel, Oil and Lubricants Expenses'],
            ['type' => 'Other Suppplies and Material Expense'],
            ['type' => 'POSTAGE AND COURIER SERVICES'],
            ['type' => 'Repair and Maintenance-Transportation Equipment'],
            ['type' => 'Repair and Maintenance-Machinery and Equipment'],
            ['type' => 'Repair and Maintenance-Furniture and Fixture'],
            ['type' => 'Other Maintenance and Operating Expenses'],
            ['type' => 'Printing and Publication Expenses'],
            ['type' => 'Other Professional Services'],

        ];
        return $q;
    }
    public function generateAdvancesNumber()
    {
        $q = Yii::$app->db->createCommand("SELECT CAST(substring_index(nft_number, '-', -1)AS UNSIGNED) as q 
            from advances 
            WHERE 
            nft_number NOT LIKE 'S%'
            AND nft_number NOT LIKE 'a%'
            AND nft_number NOT LIKE 'P%'
            AND nft_number NOT LIKE 'R%'
            ORDER BY q DESC
            LIMIT 1")->queryScalar();

        $num = 0;
        if (!empty($q)) {
            // $x = explode('-', $q->nft_number);
            $num = (int) $q + 1;
        } else {
            $num = 1;
        }

        $y = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $y .= 0;
        }
        $y .= $num;
        return date('Y') . '-' . $y;
    }
    function convertNumberToWord($num = false)
    {
        $num = str_replace(array(',', ' '), '', trim($num));
        if (!$num) {
            return false;
        }
        $num = (int) $num;
        $words = array();
        $list1 = array(
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
            'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        );
        $list2 = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety', 'hundred');
        $list3 = array(
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion', 'sextillion', 'septillion',
            'octillion', 'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion', 'quattuordecillion',
            'quindecillion', 'sexdecillion', 'septendecillion', 'octodecillion', 'novemdecillion', 'vigintillion'
        );
        $num_length = strlen($num);
        $levels = (int) (($num_length + 2) / 3);
        $max_length = $levels * 3;
        $num = substr('00' . $num, -$max_length);
        $num_levels = str_split($num, 3);
        for ($i = 0; $i < count($num_levels); $i++) {
            $levels--;
            $hundreds = (int) ($num_levels[$i] / 100);
            $hundreds = ($hundreds ? ' ' . $list1[$hundreds] . ' hundred' . ' ' : '');
            $tens = (int) ($num_levels[$i] % 100);
            $singles = '';
            if ($tens < 20) {
                $tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
            } else {
                $tens = (int)($tens / 10);
                $tens = ' ' . $list2[$tens] . ' ';
                $singles = (int) ($num_levels[$i] % 10);
                $singles = ' ' . $list1[$singles] . ' ';
            }
            $words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_levels[$i])) ? ' ' . $list3[$levels] . ' ' : '');
        } //end for loop
        $commas = count($words);
        if ($commas > 1) {
            $commas = $commas - 1;
        }
        return implode(' ', $words);
    }
    function convertNumberToWords($number)
    {
        $ones = array(
            0 => '',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine'
        );

        $tens = array(
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );

        $number = intval($number);

        if ($number < 10) {
            return $ones[$number];
        } elseif ($number < 20) {
            return $tens[$number];
        } elseif ($number < 100) {
            $unit = $number % 10;
            return $tens[$number - $unit] . ' ' . $ones[$unit];
        } elseif ($number < 1000) {
            $hundreds = intval($number / 100);
            $remainder = $number % 100;
            return $ones[$hundreds] . ' Hundred ' . $this->convertNumberToWords($remainder);
        } elseif ($number < 1000000) {
            $thousands = intval($number / 1000);
            $remainder = $number % 1000;
            return $this->convertNumberToWords($thousands) . ' Thousand ' . $this->convertNumberToWords($remainder);
        } elseif ($number < 1000000000) {
            $millions = intval($number / 1000000);
            $remainder = $number % 1000000;
            return $this->convertNumberToWords($millions) . ' Million ' . $this->convertNumberToWords($remainder);
        } else {
            $billions = intval($number / 1000000000);
            $remainder = $number % 1000000000;
            return $this->convertNumberToWords($billions) . ' Billion ' . $this->convertNumberToWords($remainder);
        }
    }
    function getWorkdays($date1, $date2, $workSat = FALSE, $patron = NULL)
    {
        if (!defined('SATURDAY')) define('SATURDAY', 6);
        if (!defined('SUNDAY')) define('SUNDAY', 0);

        // Array of all public festivities
        $publicHolidays = array(
            '01-01',
            '04-09',
            '05-01',
            '08-28',
            '11-01',
            '11-30',
            '12-08',
            '12-25',
            '12-30',
        );
        // The Patron day (if any) is added to public festivities
        if ($patron) {
            $publicHolidays[] = $patron;
        }

        /*
         * Array of all Easter Mondays in the given interval
         */
        $yearStart = date('Y', strtotime($date1));
        $yearEnd   = date('Y', strtotime($date2));

        for ($i = $yearStart; $i <= $yearEnd; $i++) {
            $easter = date('Y-m-d', easter_date($i));
            list($y, $m, $g) = explode("-", $easter);
            $monday = mktime(0, 0, 0, date($m), date($g) + 1, date($y));
            $easterMondays[] = $monday;
        }

        $start = strtotime($date1);
        $end   = strtotime($date2);
        $workdays = 0;
        for ($i = $start; $i <= $end; $i = strtotime("+1 day", $i)) {
            $day = date("w", $i);  // 0=sun, 1=mon, ..., 6=sat
            $mmgg = date('m-d', $i);
            if (
                $day != SUNDAY &&
                !in_array($mmgg, $publicHolidays) &&
                !in_array($i, $easterMondays) &&
                !($day == SATURDAY && $workSat == FALSE)
            ) {
                $workdays++;
            }
        }

        return intval($workdays);
    }
    public function irNumber()
    {

        $num = 1;
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(ir_number,'-',-1) AS UNSIGNED) as last_num FROM inspection_report ORDER BY last_num DESC LIMIT 1")->queryScalar();
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i <= 4; $i++) {
            $zero .= 0;
        }
        return date('Y') . '-' . $zero . $num;
    }
    public function iarNumber()
    {

        $num = 1;
        $query = Yii::$app->db->createCommand("SELECT CAST(SUBSTRING_INDEX(iar_number,'-',-1) AS UNSIGNED) as last_num FROM iar ORDER BY last_num DESC LIMIT 1")->queryScalar();
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $zero = '';
        for ($i = strlen($num); $i < 4; $i++) {
            $zero .= 0;
        }
        return date('Y') . '-' . $zero . $num;
    }
    public function employeeName($id)
    {
        return Yii::$app->db->createCommand("SELECT employee_name,position FROM employee_search_view WHERE employee_id = :id")
            ->bindValue(':id', $id)
            ->queryOne();
    }

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
                ->bindValue(':allotment_id', !empty($allotment_id) ? $allotment_id : null)
                ->bindValue(':prAllotmentId', !empty($prAllotmentId) ? $prAllotmentId : null)
                ->bindValue(':txnAllotmentId', !empty($txnAllotmentId) ? $txnAllotmentId : null)
                ->bindValue(':txnPrItmId', !empty($txnPrItmId) ? $txnPrItmId : null)
                ->bindValue(':orsItmId', !empty($orsItmId) ? $orsItmId : null)
                ->bindValue(':orsTxnItmId', !empty($orsTxnItmId) ? $orsTxnItmId : null)
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
    public static function gridDefaultAction($id, $class = 'mdModal')
    {
        return Html::a('<i class="fa fa-eye"></i>', ['view', 'id' => $id])
            . ' ' . Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $id], ['class' => $class]);
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

        $user_data = User::getUserDetails();
        if (strtolower($user_data->employee->office->office_name !== 'ro')) {
            $property_custodian_query->andWhere('office_name =:office_name', ['office_name' => $user_data->employee->office->office_name]);
        }
        $f_property_custodian_query = $property_custodian_query->all();
        return $f_property_custodian_query;
    }
}

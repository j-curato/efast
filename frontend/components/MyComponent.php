<?php

namespace frontend\components;

use app\models\Books;
use app\models\ChartOfAccounts;
use app\models\FundClusterCode;
use app\models\SubAccounts1;
use Yii;
use yii\base\Component;
use yii\helpers\Html;
use Da\QrCode\QrCode;

class MyComponent extends Component
{
    // public function getRaoudSerialNumber($q)
    // {
    //     return $q;
    // }
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
                'officer' => 'Fritzie N. Usares',
                'location' => 'Tandag City'
            ],
            'pdi' => [
                'province' => 'Dinagat Islands',
                'officer' => 'Venus A. Custodio',
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
    public function getParNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT
        SUBSTRING_INDEX(par.par_number,'-',-1) as p_number
        FROM par
        ORDER BY  p_number DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = 'DTI XIII-' . $new_num;
        return $string;
    }
    function getPcNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT substring_index(pc_number,'-',-1) as pc_number
        FROM property_card
        
        ORDER BY pc_number DESC LIMIT 1
        ")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = $query + 1;
        }
        $period = date('Y-m');
        $l_num = substr(str_repeat(0, 5) . $num, -5);
        $string = "PC $period-" . $l_num;

        return $string;
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
}

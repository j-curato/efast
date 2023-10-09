<?php

namespace frontend\controllers;

use Yii;
use yii\db\Query;
use app\models\Books;
use app\models\Office;
use yii\db\Expression;
use common\models\User;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use app\components\helpers\MyHelper;

class PropertyReportsController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'user-properties',
                    'ppelc',


                ],
                'rules' => [
                    [
                        'actions' => [
                            'user-properties',
                        ],
                        'allow' => true,
                        'roles' => ['property_accountabilities']
                    ],
                    [
                        'actions' => [
                            'ppelc',
                        ],
                        'allow' => true,
                        'roles' => ['ppelc']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionUserProperties()
    {
        if (Yii::$app->request->post()) {


            $act_usr_id  = !empty(MyHelper::post('act_usr_id')) ? MyHelper::post('act_usr_id') : null;
            $actbl_ofr  = !empty(MyHelper::post('actbl_ofr')) ? MyHelper::post('actbl_ofr') : null;
            $user_data = User::getUserDetails();
            $office  = Yii::$app->user->can('ro_property_admin') ? Yii::$app->request->post('office') ?? null : $user_data->employee->office->id;
            // if (empty($act_usr_id) && empty($actbl_ofr)) {
            //     return json_encode('');
            // }
            if (!Yii::$app->user->can('po_property_admin') && empty($actbl_ofr)) {
                return json_encode([]);
            }
            $qry = new Query();
            $qry->select([
                "property.property_number",
                "location.location",
                'par.par_number',
                new Expression('IFNULL(property_articles.article_name,property.article) as article_name'),
                'property.description',
                'property.serial_number',
                new Expression('property.date as date_acquired'),
                'property.acquisition_amount',
                new Expression(" (CASE 
                    WHEN par.is_unserviceable = 1 THEN 'UnSeviceable'
                    ELSE 'Serviceable'
                END ) as isServiceable"),
                new Expression('IFNULL(act_usr.employee_name,"") as actual_user'),
                new Expression('rcv_by.employee_name as actble_ofr'),
                new Expression('property_card.serial_number as pc_num')
            ])
                ->from('property')
                ->join('LEFT JOIN', 'par', 'property.id = par.fk_property_id')
                ->join('LEFT JOIN', 'property_card', 'par.id = property_card.fk_par_id')
                ->join('LEFT JOIN', 'property_articles', 'property.fk_property_article_id = property_articles.id')
                ->join('LEFT JOIN', 'employee_search_view as act_usr', 'par.fk_actual_user = act_usr.employee_id')
                ->join('LEFT JOIN', 'employee_search_view as rcv_by', 'par.fk_received_by = rcv_by.employee_id')
                ->join('LEFT JOIN',  'derecognition', 'property.id = derecognition.fk_property_id')
                ->join('LEFT JOIN',  'location', 'par.fk_location_id = location.id')
                ->andWhere('par.is_current_user = 1')
                ->andWhere('derecognition.id IS NULL');
            if (!empty($act_usr_id)) {
                $qry->andWhere("par.fk_actual_user = :act_usr_id", ['act_usr_id' => $act_usr_id]);
            }
            if (!empty($actbl_ofr)) {
                $qry->andWhere("par.fk_received_by= :actbl_ofr", ['actbl_ofr' => $actbl_ofr]);
            }
            if (YIi::$app->user->can('ro_property_admin')) {
                $qry->andWhere("property.fk_office_id= :office", ['office' => $office]);
            }
            $qry->orderBy("location.id");

            return json_encode($result = ArrayHelper::index($qry->all(), null, 'actble_ofr'));
        }
        return $this->render('user_properties');
    }
    public function actionPpelc()
    {
        if (Yii::$app->request->post()) {
            $book_id = !empty(Yii::$app->request->post('book_id')) ? Yii::$app->request->post('book_id') : null;
            $employee_id = !empty(Yii::$app->request->post('employee_id')) ? Yii::$app->request->post('employee_id') : null;
            $office_id = !empty(Yii::$app->request->post('office_id')) ? Yii::$app->request->post('office_id') : null;
            $reporting_period = !empty(Yii::$app->request->post('reporting_period')) ? Yii::$app->request->post('reporting_period') : null;
            $uacs_id = !empty(Yii::$app->request->post('uacs')) ? Yii::$app->request->post('uacs') : null;



            return json_encode($this->ppelcQuery(
                $book_id,
                $employee_id,
                $office_id,
                $reporting_period,
                $uacs_id
            ));
        }
        return $this->render('ppelc');
    }
    private function ppelcQuery(
        $book_id = '',
        $employee_id = null,
        $office_id = null,
        $reporting_period = '',
        $uacs_id = ''
    ) {
        $uacs  = Yii::$app->db->createCommand("SELECT uacs FROM chart_of_accounts WHERE id = :id")->bindValue(':id', $uacs_id)->queryScalar();
        $book_name = Books::findOne($book_id)->name;
        $qry = new Query();
        $qry->select([
            'detailed_property_database.pc_num',
            'detailed_property_database.uacs',
            'detailed_property_database.general_ledger',
            'detailed_other_property_details.book_name',
            'detailed_property_database.date_acquired',
            'detailed_property_database.acquisition_amount',
            'detailed_other_property_details.book_val',
            'detailed_other_property_details.mnthly_depreciation',
            'detailed_property_database.useful_life',
            'detailed_property_database.strt_mnth',
            new Expression(':reporting_period as reporting_period', ['reporting_period' => $reporting_period]),
            new Expression('(CASE 
            WHEN TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period) > detailed_property_database.useful_life THEN  detailed_property_database.useful_life
            ELSE TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period)
            END) as diff', ['r_period' => "$reporting_period-01"]),
            new Expression(
                '(CASE 
            WHEN TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period) > detailed_property_database.useful_life THEN  detailed_property_database.useful_life
            ELSE TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period)
            END) * detailed_other_property_details.mnthly_depreciation as depreciated_amt',


                ['r_period' => "$reporting_period-01"]
            ),
            new Expression('detailed_other_property_details.book_val-
            (CASE 
            WHEN TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period) > detailed_property_database.useful_life THEN  detailed_property_database.useful_life
            ELSE TIMESTAMPDIFF(MONTH,CONCAT(detailed_property_database.strt_mnth,"-01"),:r_period)
            END) * detailed_other_property_details.mnthly_depreciation
            as book_bal', ['r_period' => "$reporting_period-01"]),

        ])
            ->from('detailed_property_database')
            ->join('JOIN', 'detailed_other_property_details', 'detailed_property_database.property_id = detailed_other_property_details.property_id')
            ->andWhere("detailed_property_database.is_current_user = 1")
            ->andWhere("detailed_property_database.isUnserviceable = 'serviceable'")
            ->andWhere("detailed_property_database.strt_mnth <=:reporting_period", ['reporting_period' => $reporting_period])
            ->andWhere("detailed_property_database.uacs =:uacs", ['uacs' => $uacs])
            ->andWhere("detailed_other_property_details.book_name =:book_name", ['book_name' => $book_name])
            ->andWhere("DATE_FORMAT(detailed_property_database.derecognition_date,'%Y-%m') >= :reporting_period 
        OR detailed_property_database.derecognition_num IS NULL", ['reporting_period' => $reporting_period]);

        if (!Yii::$app->user->can('ro_property_admin')) {
            $user_data = User::getUserDetails();
            $office_id = $user_data->employee->office->id;
        }
        if (!empty($office_id)) {
            $offce_name = Office::findOne($office_id)->office_name;
            $qry->andWhere("detailed_property_database.office_name = :offce_name", ['offce_name' => $offce_name]);
        }
        if (!empty($employee_id)) {
            $qry->andWhere("detailed_property_database.rcv_by_id = :emp_id", ['emp_id' => $employee_id]);
        }
        $qry->orderBy("detailed_property_database.date_acquired");
        // echo $qry->createCommand()->getRawSql();
        // die();
        return $qry->all();
    }
}

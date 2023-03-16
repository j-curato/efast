<?php

namespace frontend\controllers;

use app\components\helpers\MyHelper;
use Yii;
use app\models\Par;
use app\models\ParIndexSearch;
use app\models\ParSearch;
use app\models\PropertyCard;
use Da\QrCode\QrCode;
use ErrorException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * ParController implements the CRUD actions for Par model.
 */
class ParController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [

            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'view',
                    'create',
                    'update',
                    'delete',
                    'search-employee',
                    'search-par',
                    'par-details',
                    'get-par',
                    'blank-sticker'
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'search-employee',
                            'search-par',
                            'par-details',
                            'get-par',
                            'blank-sticker'

                        ],
                        'allow' => true,
                        'roles' => ['@']
                    ],


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

    /**
     * Lists all Par models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Par model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            // 'stickerDetails' => $this->stickerDetails($id)
        ]);
    }
    public function stickerDetails($id)
    {
        return Yii::$app->db->createCommand("SELECT 
        property.property_number,
        par.par_number,
        par.old_par_number,
        par.date,
        property.province,
        par.location,
        CASE
            WHEN par.employee_id IS NULL OR par.employee_id = '' THEN par.accountable_officer
                ELSE account_officer.employee_name		
        END as accountable_officer,
        CASE
            WHEN par.actual_user IS NULL OR par.actual_user = '' THEN par.recieve_by_jocos
                ELSE act_user.employee_name		
        END as actual_user,
        
        CASE
            WHEN property.employee_id IS NULL OR property.employee_id = '' THEN par.issued_by
                ELSE property_officer.employee_name		
        END as issued_by,
        
        par.remarks
         FROM `par`
        LEFT JOIN property ON par.fk_property_id = property.id
        LEFT JOIN employee_search_view  as account_officer ON par.employee_id = account_officer.employee_id
        LEFT JOIN employee_search_view as  act_user ON par.actual_user = act_user.employee_id
        LEFT JOIN employee_search_view as property_officer ON property.employee_id  = property_officer.employee_id
        WHERE par.id = :id        
        ")
            ->bindValue(':id', $id)
            ->queryOne();
    }

    /**
     * Creates a new Par model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Par();
        if (!Yii::$app->user->can('super-user')) {
            $user_data = Yii::$app->memem->getUserData();
            $office_id = $user_data->office->id;
            $model->fk_office_id = $office_id;
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $office_name = YIi::$app->db->createCommand("SELECT office.office_name FROM property
                JOIN office ON property.fk_office_id = office.id
                WHERE property.id = :id")
                ->bindValue(':id', $model->fk_property_id)
                ->queryScalar();
            $model->par_number =     MyHelper::getParNumber($model->fk_office_id);;
            $model->_year = date('Y');
            $model->is_current_user = 1;
            try {
                $transaction = Yii::$app->db->beginTransaction();
                if (!$model->validate()) {
                    throw new ErrorException(json_encode($model->errors));
                }
                if (!$model->save(false)) {
                    throw new ErrorException('Model Save Failed');
                }
                MyHelper::UdpateParCurUser($model->id, $model->fk_property_id);
                $pc = new PropertyCard();
                $pc->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $pc->serial_number =    MyHelper::getPcNumber($model->fk_office_id);;
                $pc->fk_par_id = $model->id;
                $this->generateQr($pc->serial_number);
                if (!$pc->validate()) {
                    throw new ErrorException(json_encode($pc->errors));
                }
                if (!$pc->save(false)) {
                    throw new ErrorException('Save Failed');
                }

                $transaction->commit();
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (ErrorException $e) {
                $transaction->rollBack();
                return json_encode(['error' => $e->getMessage()]);
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Par model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                // return $model->agency_id;
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Par model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Par model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Par the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Par::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getParNumber($office_name)
    {
        $query = Yii::$app->db->createCommand("SELECT
        CAST(SUBSTRING_INDEX(par.par_number,'-',-1)AS UNSIGNED) as p_number
        FROM par
        WHERE par._year >=2023
        ORDER BY  p_number DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = strtoupper($office_name) . '-PAR-' . $new_num;
        return $string;
    }
    public function actionSearchPar($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('par.par_number as id, par.par_number AS text')
                ->from('par')
                ->join('LEFT JOIN', 'ptr', 'par.par_number = ptr.par_number')
                ->where(['like', 'par.par_number', $q])
                ->andWhere("ptr.ptr_number IS NULL");

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        // elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        // }
        return $out;
    }
    public function actionParDetails()
    {
        if ($_POST) {
            $par_number = $_POST['par_number'];
            $query = Yii::$app->db->createCommand("SELECT
            par.par_number,
            par.date as par_date,
            property.property_number,
            property.quantity,
            property.acquisition_amount,
            property.article,
            REPLACE(property.description,'[n]','\n') as `description`,
            property.iar_number,
            property.model,
            property.serial_number,
            property_card.pc_number,
            unit_of_measure.unit_of_measure,
            books.`name` as book_name,
            agency.`name` as agency_name,
            agency.`id` as agency_id,
            UPPER(recieved_by.employee_name) as rcv_by_employee_name,
            recieved_by.employee_id
            FROM par
            LEFT JOIN property ON par.property_number = property.property_number
            LEFT JOIN employee_search_view as recieved_by ON par.employee_id  = recieved_by.employee_id
            LEFT JOIN books ON property.book_id = books.id
            LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
            LEFT JOIN agency ON par.agency_id = agency.id
            LEFT JOIN property_card ON par.par_number = property_card.par_number
            WHERE par.par_number = :par_number
            ")
                ->bindValue(':par_number', $par_number)
                ->queryOne();
            return json_encode($query);
        }
    }
    public function generateQr($pc_number)
    {
        $text = $pc_number;
        $path = 'qr_codes';
        $qrCode = (new QrCode($text))
            ->setSize(250);
        header('Content-Type: ' . $qrCode->getContentType());
        $base_path =  \Yii::getAlias('@webroot');
        $qrCode->writeFile($base_path . "/qr_codes/$text.png");
    }
    function getPcNumber($office_name)
    {

        $query = Yii::$app->db->createCommand("SELECT CAST(substring_index(serial_number,'-',-1) AS UNSIGNED) as pc_number
        FROM property_card
        JOIN PAR ON property_card.fk_par_id = par.id
        WHERE 
        par._year > 2023
        ORDER BY pc_number DESC LIMIT 1
        ")->queryScalar();
        $num = 1;

        if (!empty($query)) {
            $num = intval($query) + 1;
        }

        $l_num = substr(str_repeat(0, 5) . intval($num), -5);
        $string = $office_name . "-PC-" . $l_num;
        return $string;
    }
    public function actionGetPar()
    {
        if ($_POST) {
            $pc_number = $_POST['id'];
            $query = Yii::$app->db->createCommand("SELECT par_number  FROM property_card where pc_number =:pc_number")
                ->bindValue(':pc_number', $pc_number)
                ->queryScalar();
            return json_encode($query);
        }
    }
    public function actionBlankSticker()
    {
        return $this->render('par_sticker');
    }
}

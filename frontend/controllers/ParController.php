<?php

namespace frontend\controllers;

use Yii;
use app\models\Par;
use app\models\ParSearch;
use app\models\PropertyCard;
use Da\QrCode\QrCode;
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
        $searchModel = new ParSearch();
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
        ]);
    }

    /**
     * Creates a new Par model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Par();
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->par_number = $this->getParNumber();

            if ($model->save(false)) {

                $pc = new PropertyCard();
                $pc->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
                $pc->pc_number = $this->getPcNumber();
                $pc->par_number = $model->par_number;
                $pc->fk_par_id = $model->id;
                $this->generateQr($pc->pc_number);
                if ($pc->save()) {
                    // $url = Url::to(['@propertycardView', 'id' => $pc->pc_number]);
                    // echo $url;
                    // return Yii::$app->response->redirect(['property-card/view', 'id' => $pc->id]);
                    return $this->redirect(['view', 'id' => $model->id]);
                    // return $this->redirect($url);
                }

                return $this->redirect(['view', 'id' => $model->id]);
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
    public function getParNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT
        CAST(SUBSTRING_INDEX(par.par_number,'-',-1)AS UNSIGNED) as p_number
        FROM par
        ORDER BY  p_number DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = 'DTI-XIII-' . $new_num;
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
}

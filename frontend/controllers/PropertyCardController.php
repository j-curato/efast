<?php

namespace frontend\controllers;

use Yii;
use app\models\PropertyCard;
use app\models\PropertyCardIndexSearch;
use app\models\PropertyCardSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Da\QrCode\QrCode;
use yii\filters\AccessControl;

/**
 * PropertyCardController implements the CRUD actions for PropertyCard model.
 */
class PropertyCardController extends Controller
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
                    'property-details',
                    'print-pc',
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                            'delete',
                            'property-details',
                            'print-pc',

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
    private function StickerDetails($id)
    {
        return Yii::$app->db->createCommand("SELECT 
       property_card.serial_number as pc_num,
       par.par_number,
       par.is_unserviceable,
       par.date as par_date,
       property.property_number,
       property.acquisition_amount,
       property.date as date_aquired,
       property.article,
       property_articles.article_name,
       property.description,
       received_by.employee_name as rcv_by,
       actual_user.employee_name as act_usr,
       issued_by.employee_name as isd_by,
       `location`.`location`, 
       office.office_name
       FROM property_card
       JOIN par ON property_card.fk_par_id = par.id
       JOIN property ON par.fk_property_id = property.id
       LEFT JOIN employee_search_view as received_by ON  par.fk_received_by = received_by.employee_id
       LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
       LEFT JOIN employee_search_view as issued_by ON par.fk_issued_by_id  = issued_by.employee_id
       LEFT JOIN `location` ON par.fk_location_id = location.id
       LEFT JOIN office ON par.fk_office_id = office.id
       LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
       WHERE property_card.id = :id
       ")
            ->bindValue(':id', $id)
            ->queryOne();
    }
    /**
     * Lists all PropertyCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertyCardIndexSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PropertyCard model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'sticker_details' => $this->StickerDetails($id),
        ]);
    }

    /**
     * Creates a new PropertyCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PropertyCard();
        if ($model->load(Yii::$app->request->post())) {
            $model->id = Yii::$app->db->createCommand("SELECT UUID_SHORT()")->queryScalar();
            $model->pc_number = $this->getPcNumber();

            $this->generateQr($model->pc_number);
            if ($model->save()) {
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PropertyCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PropertyCard model.
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
     * Finds the PropertyCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PropertyCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyCard::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    function getPcNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT CAST(substring_index(pc_number,'-',-1)AS UNSIGNED) as pc_number
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
    public function actionPropertyDetails()
    {
        if ($_POST) {
            $id = $_POST['id'];
            $query  = Yii::$app->db->createCommand("SELECT
            par.par_number,
            IFNULL(ptr.ptr_number,'') as ptr_number,
            par.date as par_date,
            property.property_number,
            property.quantity,
            property.acquisition_amount,
            property.article,
            property.iar_number,
            property.model,
            property.serial_number,
            property.date as date_acquired,
            property_card.pc_number,
            UPPER(recieved_by.employee_name) as accountable_officer
            FROM property_card
			LEFT JOIN par ON  property_card.par_number =par.par_number
            LEFT JOIN property ON par.property_number = property.property_number
            LEFT JOIN employee_search_view as recieved_by ON par.employee_id  = recieved_by.employee_id
            LEFT JOIN ptr ON par.par_number = ptr.par_number
            WHERE property_card.pc_number = :pc_number
          ")
                ->bindValue(':pc_number', $id)
                ->queryOne();
            return json_encode($query);
        }

        return $this->render("qr_scanner");
    }
    public function actionPrintPc()
    {

        $items = [];

        if (Yii::$app->request->isPost) {
            $reporting_period  =  YIi::$app->request->post('reporting_period');
            $items =  Yii::$app->db->createCommand("SELECT 
            property_card.id  as pc_id,
            property_card.serial_number as pc_num,
            par.par_number,
            par.is_unserviceable,
            par.date as par_date,
            property.property_number,
            property.acquisition_amount,
            property.date as date_aquired,
            property.article,
            property.description,
            received_by.employee_name as rcv_by,
            actual_user.employee_name as act_usr,
            issued_by.employee_name as isd_by,
            location.location, 
            office.office_name,
            property_articles.article_name
            FROM property_card
            JOIN par ON property_card.fk_par_id = par.id
            JOIN property ON par.fk_property_id = property.id
            LEFT JOIN employee_search_view as received_by ON  par.fk_received_by = received_by.employee_id
            LEFT JOIN employee_search_view as actual_user ON par.fk_actual_user = actual_user.employee_id
            LEFT JOIN employee_search_view as issued_by ON par.fk_issued_by_id  = issued_by.employee_id
            LEFT JOIN `location` ON par.fk_location_id = location.id
            LEFT JOIN office ON par.fk_office_id = office.id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            WHERE par.date LIKE :reporting_period
            
          ")
                ->bindValue(':reporting_period', $reporting_period . '%')
                ->queryAll();
        }
        return $this->render('stickers', ['items' => $items]);
    }
}

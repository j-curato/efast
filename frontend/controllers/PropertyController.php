<?php

namespace frontend\controllers;

use Yii;
use app\models\Property;
use app\models\PropertySearch;
use yii\db\Query;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PropertyController implements the CRUD actions for Property model.
 */
class PropertyController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Property models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Property model.
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
     * Creates a new Property model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Property();

        if ($model->load(Yii::$app->request->post())) {
            $model->property_number = $this->getPropertyNumber();

            if ($model->validate()) {
                if ($model->save(false)) {
                }
            } else {
                return json_encode($model->errors);
            }

            return $this->redirect(['view', 'id' => $model->property_number]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Property model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->property_number]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Property model.
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
     * Finds the Property model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Property the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Property::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    public function getPropertyNumber()
    {

        $query = Yii::$app->db->createCommand("SELECT
        SUBSTRING_INDEX(property.property_number,'-',-1) as p_number
        FROM property
        ORDER BY  p_number DESC LIMIT 1")->queryScalar();
        $num = 1;
        if (!empty($query)) {
            $num = intval($query) + 1;
        }
        $new_num = substr(str_repeat(0, 5) . $num, -5);
        $string = 'DTI XIII-' . $new_num;
        return $string;
    }
    public function actionSearchProperty($q = null, $id = null, $province = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;


        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('property.property_number as id, property.property_number AS text')
                ->from('property')
                ->where(['like', 'property.property_number', $q]);

            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        // elseif ($id > 0) {
        //     $out['results'] = ['id' => $id, 'text' => ChartOfAccounts::find($id)->uacs];
        // }
        return $out;
    }
    public function actionGetProperty()
    {
        if ($_POST) {
            $query = Yii::$app->db->createCommand("SELECT
            CONCAT(employee.f_name,' ',employee.l_name) as disbursing_officer,
           
            DATE_FORMAT( property.date, '%M %d, %Y')  as `date`,
            property.article,
            property.iar_number,
            property.acquisition_amount,
            property.model,
            property.property_number,
            property.quantity,
            property.serial_number,
            books.`name` as book,
            unit_of_measure.unit_of_measure
            FROM property
            LEFT JOIN books ON property.book_id = books.id
            LEFT JOIN unit_of_measure ON property.unit_of_measure_id = unit_of_measure.id
            LEFT JOIN employee ON property.employee_id = employee.employee_id
            WHERE property.property_number = :property_number")
                ->bindValue(':property_number', $_POST['id'])
                ->queryOne();
            return json_encode($query);
        }
    }


}

<?php

namespace frontend\controllers;

use Yii;
use app\models\Role;
use app\models\RoleSearch;
use ErrorException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RoleController implements the CRUD actions for Role model.
 */
class RoleController extends Controller
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
                ],
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'view',
                            'create',
                            'update',
                        ],
                        'allow' => true,
                        'roles' => ['super-user']
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

    /**
     * Lists all Role models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Role model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Role model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Role();

        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $auth = Yii::$app->authManager;
                $role = $auth->createRole($model->name);
                $auth->add($role);
                $permissions = Yii::$app->request->post('permissions');
                $childrenRoles = Yii::$app->request->post('childrenRoles') ?? [];
                foreach ($permissions as $permission) {
                    $permissionObject = $auth->getPermission($permission);
                    // $auth->add($permissionObject);
                    $auth->addChild($role, $permissionObject);
                }

                foreach ($childrenRoles as $child) {
                    $childRole = $auth->getRole($child);
                    $auth->addChild($role, $childRole);
                }


                $txn->commit();
                return $this->redirect(['view', 'id' => $model->name]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Role model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldModel = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            try {
                $txn = Yii::$app->db->beginTransaction();
                $auth = Yii::$app->authManager;
                $role = $auth->getRole($oldModel->name);
                $newPermissions = Yii::$app->request->post('permissions') ?? [];
                $newChildrenRoles = Yii::$app->request->post('childrenRoles') ?? [];



                $rolePermissions = $auth->getPermissionsByRole($oldModel->name);
                $removedPermissions =  array_diff(array_keys($rolePermissions), $newPermissions);

                $childrenRoles = $auth->getChildRoles($oldModel->name);

                // return json_encode($newChildrenRoles);

                $removedChildren =  array_diff(array_keys($childrenRoles), $newChildrenRoles);
                // return json_encode($removedChildren);
                // insert new permissions
                foreach ($newPermissions as $newPermission) {
                    $permissionObject = $auth->getPermission($newPermission);
                    if (!array_key_exists($newPermission, $rolePermissions)) {
                        $auth->addChild($role, $permissionObject);
                    }
                }
                // remmove permissions
                foreach ($removedPermissions as $removePermission) {
                    $auth->removeChild($role, $auth->getPermission($removePermission));
                }
                // add child row

                foreach ($newChildrenRoles as $child) {

                    if (!array_key_exists($child, $childrenRoles)) {
                        $childRole = $auth->getRole($child);
                        $auth->addChild($role, $childRole);
                    }
                }

                // remove child

                foreach ($removedChildren as $child) {
                    // return $child;
                    $auth->removeChild($role, $auth->getRole($child));
                }


                $oldRole  = $auth->getRole($oldModel->name);
                $oldRole->name = $model->name;
                $oldRole->description = $model->description;
                $auth->update($oldModel->name, $oldRole);
                $txn->commit();
                return $this->redirect(['view', 'id' => $model->name]);
            } catch (ErrorException $e) {
                $txn->rollBack();
                return $e->getMessage();
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Role model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the Role model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Role the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

<?php

namespace frontend\controllers;

use Yii;
use DateTime;
use DateInterval;
use yii\helpers\Url;
use app\models\Event;
use common\models\User;
use yii\web\Controller;
use app\models\Password;
use yii\bootstrap4\Toast;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use common\models\LoginForm;
use yii\bootstrap\ActiveForm;
use common\models\UploadImage;
use yii\filters\AccessControl;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\ChangePassword;
use ErrorException;
use frontend\models\VerifyEmailForm;
use yii\web\BadRequestHttpException;
use frontend\models\ResetPasswordForm;
use yii\base\InvalidArgumentException;
use lavrentiev\widgets\toastr\Notification;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $defaultIndex = 'login';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'logout', 'signup', 'index', 'q',
                    'update-system',
                    'token',
                    'change-password',
                    'clear-exports',
                    'profile'
                ],
                'rules' => [

                    [
                        'actions' => ['logout', 'index', 'q', 'token', 'change-password', 'clear-exports', 'profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update-system', 'signup'],
                        'allow' => true,
                        'roles' => ['super-user'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                // 'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()

    {
        $ev = Event::find()->all();
        $events = [];
        foreach ($ev as $e) {

            $event = new \edofre\fullcalendar\models\Event();
            $event->id = $e->id;

            $event->title = $e->title;
            $event->start = $e->created_at;
            $event->end =  '2021-7-10';
            $events[] = $event;
        }
        return $this->render('index', [
            'events' => $events
        ]);
    }
    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionChangePassword()
    {
        $model = new ChangePassword();
        if ($model->load(Yii::$app->request->post())) {
            if (!$model->updatePassword()) {
                return $model->errors;
            }
            return $this->goBack();

            // return $this->redirect('');
        }

        return $this->render('change_password', [
            'model' => $model,
        ]);
    }
    public function actionProfile()
    {

        $changePassModel = new ChangePassword();
        if ($changePassModel->load(Yii::$app->request->post())) {

            try {
                Yii::$app->response->format = Response::FORMAT_JSON;
                $validate = ActiveForm::validate($changePassModel);
                if (!empty($validate)) {
                    return $validate;
                }
                if (!$changePassModel->updatePassword()) {
                    throw new ErrorException('Change Pass Failed');
                }
                return ['success' => true];
            } catch (ErrorException $e) {
                return $e->getMessage();
            }

            Yii::$app->session->setFlash(
                'success',
                'Thank you for registration. Please check your inbox for verification email.'
            );
        }

        // $createAcc = new SignupForm();
        // if (Yii::$app->user->can('super-user')) {

        //     if ($createAcc->load(Yii::$app->request->post())) {
        //         if (!$createAcc->validate()) {
        //             return json_encode($createAcc->errors);
        //         }
        //         if ($createAcc->signup()) {
        //         }

        //         return $this->goHome();
        //     }
        // }

        return $this->render('profile', [
            'changePassModel' => $changePassModel,

        ]);
    }
    public function actionLogin()
    {
        $this->layout = 'main-login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return $this->render('index');
            Yii::$app->session->regenerateID(true);
            return $this->goBack();

            // return $this->redirect('');
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        // $this->layout = 'register';
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {

            if (!$model->validate()) {
                return json_encode($model->errors);
            }
            if ($model->signup()) {
            }
            // Yii::$app->session->setFlash(
            //     'success',
            //     'Thank you for registration. Please check your inbox for verification email.'
            // );
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'main-login';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
    // public function actionMigrateUp()
    // {
    //     // https://github.com/yiisoft/yii2/issues/1764#issuecomment-42436905
    //     $oldApp = \Yii::$app;
    //     new \yii\console\Application([
    //         'id'            => 'Command runner',
    //         'basePath'      => '@app',
    //         'components'    => [
    //             'db' => $oldApp->db,
    //         ],
    //     ]);
    //     // yii migrate --migrationPath=@yii/rbac/migrations
    //     \Yii::$app->runAction('migrate', ['migrationPath' => '@yii/rbac/migrations/', 'interactive' => false]);
    //     \Yii::$app->runAction('migrate/up', ['migrationPath' => '@console/migrations/', 'interactive' => false]);
    //     \Yii::$app = $oldApp;
    // }
    public function actionQ()
    {
        $ev = Event::find()->all();
        $events = [];
        foreach ($ev as $e) {
            $day = date('d', strtotime($e->end_date)) + 1;
            $date = new DateTime($e->end_date);
            $date->add(new DateInterval('P1D'));
            // echo $date->format('Y-m-d') . "\n";
            $event = [
                'id' => $e->id,
                'title' => $e->title,
                'start' => $e->created_at,
                'end' => $date->format('Y-m-d'),
            ];

            $events[] = $event;
        }


        return json_encode($events);
    }
    public function actionX()
    {

        $arr = [
            [
                'username' => 'adnadmin1',
                'password' => uniqid(),
                'email' => 'adnadmin1@gmail.com',
                'province' => 'adn',
                'ass' => 'province_admin_1'
            ],
            [
                'username' => 'adnadmin2',
                'password' => uniqid(),
                'email' => 'adnadmin2@gmail.com',
                'province' => 'adn',
                'ass' => 'province_admin_2'
            ],
            [
                'username' => 'adsadmin1',
                'password' => uniqid(),
                'email' => 'adsadmin1@gmail.com',
                'province' => 'ads',
                'ass' => 'province_admin_1'

            ],
            [
                'username' => 'adsadmin2',
                'password' => uniqid(),
                'email' => 'adsadmin2@gmail.com',
                'province' => 'ads',
                'ass' => 'province_admin_2'
            ],
            [
                'username' => 'sdsadmin1',
                'password' => uniqid(),
                'email' => 'sdsadmin1@gmail.com',
                'province' => 'sds',
                'ass' => 'province_admin_1'
            ],
            [
                'username' => 'sdsadmin2',
                'password' => uniqid(),
                'email' => 'sdsadmin2@gmail.com',
                'province' => 'sds',
                'ass' => 'province_admin_2'
            ],
            [
                'username' => 'sdnadmin1',
                'password' => uniqid(),
                'email' => 'sdnadmin1@gmail.com',
                'province' => 'sdn',
                'ass' => 'province_admin_1'
            ],
            [
                'username' => 'sdnadmin2',
                'password' => uniqid(),
                'email' => 'sdnadmin2@gmail.com',
                'province' => 'sdn',
                'ass' => 'province_admin_2'
            ],
            [
                'username' => 'pdiadmin1',
                'password' => uniqid(),
                'email' => 'pdiadmin1@gmail.com',
                'province' => 'pdi',
                'ass' => 'province_admin_1'
            ],
            [
                'username' => 'pdiadmin2',
                'password' => uniqid(),
                'email' => 'pdiadmin2@gmail.com',
                'province' => 'pdi',
                'ass' => 'province_admin_2'
            ],
        ];

        foreach ($arr as $ar) {
            $q = uniqid();
            $model = new User();
            $model->username = $ar['username'];
            $model->email = $ar['email'];
            $model->province = $ar['province'];
            $model->setPassword($q);
            $model->generateAuthKey();
            $model->generateEmailVerificationToken();

            if ($model->save(false)) {
                $p = new Password();
                $p->username = $ar['username'];
                $p->password =   $q;
                Yii::$app->db->createCommand("
                INSERT INTO auth_assignment (item_name,user_id)
                VALUES ('{$ar['ass']}',$model->id);
                ")->query();
                if ($p->save(false)) {
                }
            }
        }
        return json_encode($arr);
    }
    public function actionUpdateSystem()
    {
        echo  shell_exec('git pull git@github.com:kiotipot1/dti-afms-2.git');
        echo   shell_exec('yii migrate --interactive=0');
    }
    public function actionToken()
    {

        if ($_POST) {

            $token = 'C0ocZR073FC8lOWDXi1uoNxCwIBuPLKN';
            return json_encode(['token' => $token]);
        }
    }
    public function actionClearExports()
    {

        if (YIi::$app->request->isPost) {

            $folderPath = Url::base() . '/exports';
            // return $folderPath;
            // $files = FileHelper::findFiles($folderPath);
            $appDir = Yii::getAlias('@webroot');
            $folder_path = $appDir . '\exports';


            $file_list = FileHelper::findFiles($folder_path);
            // return json_encode($file_list);
            foreach ($file_list as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            return 'succ';
        }
        return 'wala';
    }
    function actionUploadImage()
    {

        $model = new UploadImage();
        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                // file is uploaded successfully
                return $this->redirect(['profile']);
            }
        }
        return $this->renderAjax('upload_form', ['model' => $model]);
    }
    public function actionGitUpdate()
    {
        $repoPath = Yii::getAlias('@webroot/gitpull.sh');
        echo getcwd() . "\n";
        $output = exec('q.bat 2>&1');
        var_dump($output);
        echo "\nexecuted ";
        // $output = shell_exec("start /B $repoPath 2>&1");
    }
}

<?php

namespace frontend\controllers;

use app\models\Event;
use app\models\Password;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

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
                'only' => ['logout', 'signup', 'index', 'q', 'update-system'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'q'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update-system'],
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
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
    public function actionLogin()
    {
        $this->layout = 'main-login';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // return $this->render('index');
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
        $this->layout = 'register';
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
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
            $event = [
                'id' => $e->id,
                'title' => $e->title,
                'start' => $e->created_at,
                'end' => date("Y-m-$day", strtotime($e->end_date)),
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
}

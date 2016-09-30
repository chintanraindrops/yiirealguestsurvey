<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dektrium\user\controllers;

use dektrium\user\Finder;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use dektrium\user\models\Feedback;
use dektrium\user\models\User;
use dektrium\user\models\Profile;
use dektrium\user\models\RequestFeedback;
use dektrium\user\models\BusinessLocation;
use dektrium\user\traits\AjaxValidationTrait;
use yii\helpers\Url;
use dektrium\user\Mailer;

/**
 * ProfileController shows users profiles.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ProfileController extends Controller
{
    use AjaxValidationTrait;
    /** @var Finder */
    protected $finder;

    /**
     * @param string           $id
     * @param \yii\base\Module $module
     * @param Finder           $finder
     * @param array            $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index', 'frontshow', 'feedback', 'feedbackthankyou'], 'roles' => ['@']],
                    ['allow' => true, 'actions' => ['show', 'feedbackfromprofile', 'frontshow', 'feedback', 'feedbackthankyou'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    /**
     * Redirects to current user's profile.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->redirect(['show', 'id' => \Yii::$app->user->getId()]);
    }

    /**
     * @return Mailer
     * @throws \yii\base\InvalidConfigException
     */
    protected function getMailer()
    {
        return \Yii::$container->get(Mailer::className());
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        } else { // [[CUSTOM]] RI , for direct redirecting user to edit profile
            return $this->redirect(['/user/settings/profile']);
        }

        return $this->render('show', [
            'profile' => $profile,
        ]);
    }

    public function actionFeedbackfromprofile($profilename = false){
        if(empty($profilename)){
            throw new NotFoundHttpException();
        }

        //$profileDataFromUrl = Profile::find()->where(['profile_url' => $profilename])->one();
        $location = BusinessLocation::find()->where(['profile_url' => $profilename])->one();
        
        if(is_null($location)){
            throw new NotFoundHttpException();
        }
        
        $id = $location->getAttribute('user_id');
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        $userData = User::findOne($id);
        $profileData = Profile::findOne($id);

        $model = \Yii::createObject(Feedback::className());
        
        $this->performAjaxValidation($model);

        $post = \Yii::$app->request->post();
        
        if(!empty($post)){
            $model->user_id = (!empty($post['user_id'])) ? $post['user_id'] : '';
            $model->location_id = (!empty($post['user_id'])) ? $post['location_id'] : '';
            $model->from_firstname = (!empty($post['from_firstname'])) ? $post['from_firstname'] : '';
            $model->from_lastname = (!empty($post['from_lastname'])) ? $post['from_lastname'] : '';
            $model->from_email = (!empty($post['from_email'])) ? $post['from_email'] : '';
            $model->from_mobile = (!empty($post['from_mobile'])) ? $post['from_mobile'] : '';
            $model->from_token = (!empty($post['from_token'])) ? $post['from_token'] : '';
            $model->notes = (!empty($post['notes'])) ? $post['notes'] : '';
            $model->type = (!empty($post['client_feedback_type'])) ? $post['client_feedback_type'] : '';
            $model->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;
            $model->setData();
            if(!empty($model->user_id)){ // && (!empty($model->from_email) || !empty($model->from_mobile))
                $save = $model->save();
                if($save){
                    /*\Yii::$app->session->setFlash(
                        'success',
                        \Yii::t(
                            'user',
                            'Feedback Successfully Sent'
                        )
                    );*/
                    //return \Yii::$app->response->redirect('/user/profile/feedbackthankyou?id='.$profile->user_id.'&feed_id='.$model->getAttribute('id').'&feedback_type='.$post['client_feedback_type']);
                    return \Yii::$app->response->redirect(Url::to('@web'.'/user/profile/feedbackthankyou?token='.$model->getAttribute('from_token'), true));

                } else {
                    \Yii::$app->session->setFlash(
                        'error',
                        \Yii::t(
                            'user',
                            'Feedback Could not Save or Sent'
                        )
                    );
                }
            }
        } else {
            return $this->render('feedback', [
                'model'  => $model,
                'profile' => $profile,
                'location' => $location,
            ]);
        }
        
        return $this->render('feedback', [
            'profile' => $profile,
            'location' => $location,
        ]);
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFrontshow($id)
    {
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('frontshow', [
            'profile' => $profile,
        ]);
    }

    public function actionFeedback($token = false)
    {
        if (empty($token)) {
            throw new NotFoundHttpException();
        }

        $requestFeedbackData = RequestFeedback::find()->where(['token' => $token])->one();
        $id = $requestFeedbackData->getAttribute('user_id');
        $location_id = $requestFeedbackData->getAttribute('location_id');
        $profile = $this->finder->findProfileById($id);
        $location = BusinessLocation::find()->where(['id' => $location_id])->one();        

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        $userData = User::findOne($id);
        $profileData = Profile::findOne($id);

        //var_dump($profileData->name);var_dump($userData->username);exit;

        $model = \Yii::createObject(Feedback::className());
        //$model_query = \Yii::createObject(FeedbackQuery::className());
        //$tempStr = 'http://frontend.dev/index.php?r=/user/profile/feedback&id=11&firstname=Bob&lastname=Builder&email=bobthebuilder@mailinator.com';

        //$tempStr = substr($tempStr, 0, strrpos($tempStr, 'index.php'));
        //var_dump($tempStr);exit;

        $this->performAjaxValidation($model);

        $post = \Yii::$app->request->post();
        //var_dump($post);exit;
        if(!empty($post)){
            $model->user_id = (!empty($post['user_id'])) ? $post['user_id'] : '';
            $model->location_id = (!empty($post['location_id'])) ? $post['location_id'] : '';
            $model->from_firstname = (!empty($post['from_firstname'])) ? $post['from_firstname'] : '';
            $model->from_lastname = (!empty($post['from_lastname'])) ? $post['from_lastname'] : '';
            $model->from_email = (!empty($post['from_email'])) ? $post['from_email'] : '';
            $model->from_mobile = (!empty($post['from_mobile'])) ? $post['from_mobile'] : '';
            $model->from_token = (!empty($post['from_token'])) ? $post['from_token'] : '';
            $model->notes = (!empty($post['notes'])) ? $post['notes'] : '';
            $model->type = (!empty($post['client_feedback_type'])) ? $post['client_feedback_type'] : '';
            $model->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;
            $model->setData();
            if(!empty($model->user_id)){ // && (!empty($model->from_email) || !empty($model->from_mobile))
                if(!empty($model->from_email) || !empty($model->from_mobile)){
                    if(!empty($model->from_email)){
                        $feed_id = $model->from_email;
                    } else {
                        $feed_id = $model->from_mobile;
                    }
                    $first_time_to_feedback = $model->findExistFeedback($feed_id, $model->location_id, $model->user_id);
                    if($first_time_to_feedback){
                        $save = $model->save();
                        if($save){
                            $data = (object) array('feedback_type' => ucwords($post['client_feedback_type']));
                            $data->email = [$userData->email => $location->getAttribute('location_name')];
                            $data->client = (!empty($profileData->name)) ? ucwords($profileData->name) : ((!empty($userData->username)) ? $userData->username : 'Customer' );
                            $data->end_user = (!empty($post['from_firstname']) && !empty($post['from_lastname'])) ? ucwords($post['from_firstname'].' '.$post['from_lastname']) : ((!empty($post['from_email'])) ? $post['from_email'] : '');
                            $data->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;
                            $urlStr = Url::to('@web', true);
                            $data->feedback_link = substr($urlStr, 0, strrpos($urlStr, 'index.php'));
                            $data->feedback_notes = (!empty($post['notes'])) ? $post['notes'] : 0;

                            if(!empty($data->email)) $client_mailsent = $this->mailer->sendFeedbackReceivedMessage($data);
                            
                            if(!empty($post['from_firstname']) && !empty($post['from_lastname'])){
                                $enduseremail = [$post['from_email'] => $post['from_firstname'].' '.$post['from_lastname']];
                            } else {
                                $enduseremail = $post['from_email'];
                            }
                            $endUserEmailData = (object) array('email' => $enduseremail);
                            $endUserEmailData->sender_email = [$userData->email => $location->getAttribute('location_name')];
                            $endUserEmailData->end_user = (!empty($post['from_firstname']) && !empty($post['from_lastname'])) ? ucwords($post['from_firstname'].' '.$post['from_lastname']) : ((!empty($post['from_email'])) ? $post['from_email'] : '');
                            $endUserEmailData->client = (!empty($profileData->name)) ? ucwords($profileData->name) : ((!empty($userData->username)) ? $userData->username : 'Our Customer' );
                            $endUserEmailData->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;

                            if(!empty($endUserEmailData->email)) $enduser_mailsent = $this->mailer->sendThankyouforFeedbackMessage($endUserEmailData);


                            \Yii::$app->session->setFlash(
                                'success',
                                \Yii::t(
                                    'user',
                                    'Feedback Successfully Sent'
                                )
                            );

                            $requestFeedbackDataId = $requestFeedbackData->getAttribute('id');
                            $requestFeedbackData->updateAll(['status' => 'reviewed'], 'id = '.$requestFeedbackDataId);

                            /*return $this->render('feedbackthankyou', [
                                'feedback_type' => $post['client_feedback_type'],
                                'profile' => $profile,
                            ]);

                            return \Yii::$app->response->redirect(Url::to(['profile/feedbackthankyou&id='.$profile->user_id.'&feedback_type='.$post['client_feedback_type']] ));

                            */
                            //return \Yii::$app->response->redirect('/user/profile/feedbackthankyou?id='.$profile->user_id.'&feedback_type='.$post['client_feedback_type']);
                            //echo Url::to('@web'.'/user/profile/feedbackthankyou?token='.$post['from_token'], true);exit;
                            return \Yii::$app->response->redirect(Url::to('@web'.'/user/profile/feedbackthankyou?token='.$post['from_token'], true));

                            /*return \Yii::$app->response->redirect(Url::to(['profile/feedbackthankyou', ['id' => $profile->user_id , 'feedback_type' => $post['client_feedback_type'] ] ] ));*/
                        } else {
                            \Yii::$app->session->setFlash(
                                'error',
                                \Yii::t(
                                    'user',
                                    'Feedback Could not Save or Sent'
                                )
                            );
                        }
                    } else {
                        \Yii::$app->session->setFlash(
                            'warning',
                            \Yii::t(
                                'user',
                                'Review already submitted by you within last 24 hrs. Try Next Day !'
                            )
                        );
                    }
                } else {
                    $save = $model->save();
                    if($save){
                        \Yii::$app->session->setFlash(
                            'success',
                            \Yii::t(
                                'user',
                                'Feedback Successfully Sent'
                            )
                        );

                        return \Yii::$app->response->redirect(Url::to('@web'.'/user/profile/feedbackthankyou?id='.$profile->user_id.'&feed_id='.$model->getAttribute('id').'&feedback_type='.$post['client_feedback_type'], true));

                    } else {
                        \Yii::$app->session->setFlash(
                            'error',
                            \Yii::t(
                                'user',
                                'Feedback Could not Save or Sent'
                            )
                        );
                    }
                }
            }
        } else {
            $requestFeedbackDataId = $requestFeedbackData->getAttribute('id');
            $requestFeedbackData->updateAll(['status' => 'open'], 'id = '.$requestFeedbackDataId);
            
            return $this->render('feedback', [
                'model'  => $model,
                'profile' => $profile,
                'requestFeedbackData' => $requestFeedbackData,
                'location' => $location,
            ]);
        }
        
        return $this->render('feedback', [
            'profile' => $profile,
            'requestFeedbackData' => $requestFeedbackData,
            'location' => $location,
        ]);
    }

    /**
     * user's feedback page.
     *
     * @param int $id
     *
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionFeedback2($id, $profilename = false)
    {
        $profile = $this->finder->findProfileById($id);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        $userData = User::findOne($id);
        $profileData = Profile::findOne($id);

        //var_dump($profileData->name);var_dump($userData->username);exit;

        $model = \Yii::createObject(Feedback::className());
        //$model_query = \Yii::createObject(FeedbackQuery::className());
        //$tempStr = 'http://frontend.dev/index.php?r=/user/profile/feedback&id=11&firstname=Bob&lastname=Builder&email=bobthebuilder@mailinator.com';

        //$tempStr = substr($tempStr, 0, strrpos($tempStr, 'index.php'));
        //var_dump($tempStr);exit;

        $this->performAjaxValidation($model);

        $post = \Yii::$app->request->post();
        //var_dump($post);exit;
        if(!empty($post)){
            $model->user_id = (!empty($post['user_id'])) ? $post['user_id'] : '';
            $model->from_firstname = (!empty($post['from_firstname'])) ? $post['from_firstname'] : '';
            $model->from_lastname = (!empty($post['from_lastname'])) ? $post['from_lastname'] : '';
            $model->from_email = (!empty($post['from_email'])) ? $post['from_email'] : '';
            $model->from_mobile = (!empty($post['from_mobile'])) ? $post['from_mobile'] : '';
            $model->notes = (!empty($post['notes'])) ? $post['notes'] : '';
            $model->type = (!empty($post['client_feedback_type'])) ? $post['client_feedback_type'] : '';
            $model->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;
            $model->setData();
            if(!empty($model->user_id)){ // && (!empty($model->from_email) || !empty($model->from_mobile))
                if(!empty($model->from_email) || !empty($model->from_mobile)){
                    if(!empty($model->from_email)){
                        $feed_id = $model->from_email;
                    } else {
                        $feed_id = $model->from_mobile;
                    }
                    $first_time_to_feedback = $model->findExistFeedback($feed_id, $model->user_id);
                    if($first_time_to_feedback){
                        $save = $model->save();
                        if($save){
                            $data = (object) array('feedback_type' => ucwords($post['client_feedback_type']));
                            $data->email = $userData->email;
                            $data->client = (!empty($profileData->name)) ? ucwords($profileData->name) : ((!empty($userData->username)) ? $userData->username : 'Customer' );
                            $data->end_user = (!empty($post['from_firstname']) && !empty($post['from_lastname'])) ? ucwords($post['from_firstname'].' '.$post['from_lastname']) : ((!empty($post['from_email'])) ? $post['from_email'] : '');
                            $data->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;
                            $urlStr = Url::to('@web', true);
                            $data->feedback_link = substr($urlStr, 0, strrpos($urlStr, 'index.php'));
                            $data->feedback_notes = (!empty($post['notes'])) ? $post['notes'] : '';

                            if(!empty($data->email)) $client_mailsent = $this->mailer->sendFeedbackReceivedMessage($data);
                            
                            $endUserEmailData = (object) array('email' => $post['from_email']);
                            $endUserEmailData->end_user = (!empty($post['from_firstname']) && !empty($post['from_lastname'])) ? ucwords($post['from_firstname'].' '.$post['from_lastname']) : ((!empty($post['from_email'])) ? $post['from_email'] : '');
                            $endUserEmailData->client = (!empty($profileData->name)) ? ucwords($profileData->name) : ((!empty($userData->username)) ? $userData->username : 'Our Customer' );
                            $endUserEmailData->contact_me = (!empty($post['contact_me'])) ? $post['contact_me'] : 0;

                            if(!empty($endUserEmailData->email)) $enduser_mailsent = $this->mailer->sendThankyouforFeedbackMessage($endUserEmailData);


                            \Yii::$app->session->setFlash(
                                'success',
                                \Yii::t(
                                    'user',
                                    'Feedback Successfully Sent'
                                )
                            );

                            /*return $this->render('feedbackthankyou', [
                                'feedback_type' => $post['client_feedback_type'],
                                'profile' => $profile,
                            ]);

                            return \Yii::$app->response->redirect(Url::to(['profile/feedbackthankyou&id='.$profile->user_id.'&feedback_type='.$post['client_feedback_type']] ));

                            */
                            return \Yii::$app->response->redirect(Url::to('@web'.'/user/profile/feedbackthankyou?id='.$profile->user_id.'&feedback_type='.$post['client_feedback_type'], true));

                            /*return \Yii::$app->response->redirect(Url::to(['profile/feedbackthankyou', ['id' => $profile->user_id , 'feedback_type' => $post['client_feedback_type'] ] ] ));*/
                        } else {
                            \Yii::$app->session->setFlash(
                                'error',
                                \Yii::t(
                                    'user',
                                    'Feedback Could not Save or Sent'
                                )
                            );
                        }
                    } else {
                        \Yii::$app->session->setFlash(
                            'warning',
                            \Yii::t(
                                'user',
                                'Review already submitted by you within last 24 hrs. Try Next Day !'
                            )
                        );
                    }
                } else {
                    $save = $model->save();
                    if($save){
                        \Yii::$app->session->setFlash(
                            'success',
                            \Yii::t(
                                'user',
                                'Feedback Successfully Sent'
                            )
                        );

                        return \Yii::$app->response->redirect(Url::to('@web'.'/user/profile/feedbackthankyou?id='.$profile->user_id.'&feed_id='.$model->getAttribute('id').'&feedback_type='.$post['client_feedback_type'], true));

                    } else {
                        \Yii::$app->session->setFlash(
                            'error',
                            \Yii::t(
                                'user',
                                'Feedback Could not Save or Sent'
                            )
                        );
                    }
                }
            }
        } else {
            return $this->render('feedback', [
                'model'  => $model,
                'profile' => $profile,
            ]);
        }
        
        return $this->render('feedback', [
            'profile' => $profile,
        ]);
    }

    public function actionFeedbackthankyou($token)
    {
        if (empty($token)) {
            throw new NotFoundHttpException();
        }

        $feedbackData = Feedback::find()->where(['from_token' => $token])->one();
        $id = $feedbackData->getAttribute('user_id');
        
        $get = \Yii::$app->request->get();
        //var_dump($feedbackData);exit;
        $location_id = $feedbackData->getAttribute('location_id');
        $profile = $this->finder->findProfileById($id);
        $location = BusinessLocation::find()->where(['id' => $location_id])->one();

        $userData = User::findOne($id);
        $profileData = Profile::findOne($id);

        $post = \Yii::$app->request->post();
        
        if(!empty($post['feedback_id'])){
            $model = Feedback::findOne($post['feedback_id']);
        } else {
            $model = \Yii::createObject(Feedback::className());
        }
        $this->performAjaxValidation($model);

        if ($profile === null) {
            throw new NotFoundHttpException();
        }

        if(!empty($post)){
            //var_dump($post);exit;
            if(!empty($post['Feedback']['from_firstname'])) {
                $firstnameArr = explode(" ", $post['Feedback']['from_firstname']);
                $model->from_firstname = $firstnameArr[0];
                $model->from_lastname = $firstnameArr[1];
            }
            /*$model->from_firstname = (!empty($post['Feedback']['from_firstname'])) ? $post['Feedback']['from_firstname'] : '';
            $model->from_lastname = (!empty($post['Feedback']['from_lastname'])) ? $post['Feedback']['from_lastname'] : '';*/
            $model->from_email = (!empty($post['Feedback']['from_email'])) ? $post['Feedback']['from_email'] : '';
            $model->from_mobile = (!empty($post['Feedback']['from_mobile'])) ? $post['Feedback']['from_mobile'] : '';
            $model->from_token = $get['token'];
            $model->id = $post['feedback_id'];
            // $model->setIsNewRecord(0);
            $model->isNewRecord = 0;
            $model->setData();

            //var_dump($model);exit;

            $saveOp = $model->save();
            //var_dump($saveOp);exit;
            if($saveOp){
                \Yii::$app->session->setFlash(
                    'success',
                    \Yii::t(
                        'user',
                        'Feedback Successfully Sent'
                    )
                );

                $data = (object) array('feedback_type' => ucwords($feedbackData->getAttribute('type')));
                $data->email = [$userData->email => $location->getAttribute('location_name')];
                $data->client = (!empty($profileData->name)) ? ucwords($profileData->name) : ((!empty($userData->username)) ? $userData->username : 'Customer' );
                $data->end_user = (!empty($model->from_firstname)) ? ucwords($model->from_firstname.' '.$model->from_lastname) : ((!empty($post['Feedback']['from_email'])) ? $post['Feedback']['from_email'] : '');
                $data->contact_me = (!empty($feedbackData->getAttribute('contact_me'))) ? $feedbackData->getAttribute('contact_me') : 0;
                $urlStr = Url::to('@web', true);
                $data->feedback_link = substr($urlStr, 0, strrpos($urlStr, 'index.php'));
                $data->feedback_notes = (!empty($feedbackData->getAttribute('notes'))) ? $feedbackData->getAttribute('notes') : '';
                //var_dump($data->feedback_link);exit;

                if(!empty($data->email)) $client_mailsent = $this->mailer->sendFeedbackReceivedMessage($data);
                
                if(!empty($model->from_firstname)){
                    $enduseremail = [$post['Feedback']['from_email'] => $model->from_firstname.' '.$model->from_lastname];
                } else {
                    $enduseremail = $post['Feedback']['from_email'];
                }
                $endUserEmailData = (object) array('email' => $enduseremail);
                $endUserEmailData->sender_email = [$userData->email => $location->getAttribute('location_name')];
                $endUserEmailData->end_user = (!empty($model->from_firstname)) ? ucwords($model->from_firstname.' '.$model->from_lastname) : ((!empty($post['Feedback']['from_email'])) ? $post['Feedback']['from_email'] : '');
                $endUserEmailData->client = (!empty($profileData->name)) ? ucwords($profileData->name) : ((!empty($userData->username)) ? $userData->username : 'Our Customer' );
                $endUserEmailData->contact_me = (!empty($feedbackData->getAttribute('contact_me'))) ? $feedbackData->getAttribute('contact_me') : 0;

                if(!empty($endUserEmailData->email)) $enduser_mailsent = $this->mailer->sendThankyouforFeedbackMessage($endUserEmailData);
                
                return \Yii::$app->response->redirect(Url::to('@web'.'/user/profile/feedbackthankyou?token='.$get['token'], true));

            } else {
                \Yii::$app->session->setFlash(
                    'error',
                    \Yii::t(
                        'user',
                        'Feedback Could not Save or Sent'
                    )
                );
            }
        }

        return $this->render('feedbackthankyou', [
            'get' => $get,
            'profile' => $profile,
            'model'=> $model,
            'feedbackData' => $feedbackData,
            'location' => $location,
        ]);
    }

    public function checkIsAccountSetuped(){
        $role = \Yii::$app->user->identity->getRole();
        if($role == "client_admin"){
            $profile = $this->finder->findProfileById(\Yii::$app->user->id);
            if ($profile->setup === null || $profile->setup != "finish") {
                return false;
            }
        }
        return true;
    }
}
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
use dektrium\user\Mailer;
use yii\web\NotFoundHttpException;
use dektrium\user\traits\AjaxValidationTrait;
use dektrium\user\models\RegistrationForm;
use dektrium\user\models\RequestFeedbackForm;
use dektrium\user\models\RequestFeedback;
use dektrium\user\models\RequestFeed;
use dektrium\user\models\ImportUserForm;
use yii\web\UploadedFile;
use dektrium\user\models\RequestContactForm;
use dektrium\user\models\ProfileForm;
use dektrium\user\models\BusinessLocation;
use dektrium\user\models\StaffLocationMap;

use dektrium\user\models\Profile;
use dektrium\user\models\User;
use dektrium\user\traits\EventTrait;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use dektrium\user\models\BusinessLocationForm;
use dektrium\user\models\EmailTemplateForm;
use dektrium\user\models\EmailTemplate;
use dektrium\user\models\EmailTemplateMap;
use dektrium\user\models\EmailTemplateMapForm;
use dektrium\user\models\Feedback;
// use dektrium\user\models\User;
use dektrium\user\models\UserMap;
// use dektrium\user\models\UserSearch;
// use yii\data\ActiveDataProvider;
// use yii\data\SqlDataProvider;
// use yii\db\Query;


/**
 * ProfileController shows users profiles.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ClientDashboardController extends Controller
{
	use AjaxValidationTrait;
	use EventTrait;

	/**
	 * Event is triggered before updating user's profile.
	 * Triggered with \dektrium\user\events\UserEvent.
	 */
	const EVENT_BEFORE_PROFILE_UPDATE = 'beforeProfileUpdate';

	/**
	 * Event is triggered after updating user's profile.
	 * Triggered with \dektrium\user\events\UserEvent.
	 */
	const EVENT_AFTER_PROFILE_UPDATE = 'afterProfileUpdate';

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
		
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/user/security/login']);
		}
	}

	/** @inheritdoc */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'actions' => ['feedbacks'], 'roles' => ['admin']],
					['allow' => true, 'actions' => ['index'], 'roles' => ['@']],
					['allow' => true, 'actions' => ['dashboard', 'finalsetup', 'requestfeedback', 'account', 'uploadcsv', 'addstaff', 'addenduser', 'requestcontact', 'requestcontact2', 'profilesetup', 'location', 'requestcontact3', 'feedbacks', 'feedback', 'managestaff', 'editstaff', 'updatestaff', 'deletestaff', 'confirmstaff', 'blockstaff', 'unblockstaff', 'importcustomer'], 'roles' => ['?', '@']],
				],
			],
		];
	}

	/**
	 * Redirects to current client's dashboard.
	 *
	 * @return \yii\web\Response
	 */
	public function actionIndex()
	{
		return $this->redirect(['dashboard']);
	}





	/**
	 * Shows client's Account setup.
	 *
	 * @return \yii\web\Response
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function actionAccount(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		$role = \Yii::$app->user->identity->getRole();
        if($role == "client_admin"){
            $profile = $this->finder->findProfileById(\Yii::$app->user->id);
            
            if(is_null($profile)) {
            	\Yii::$app->getUser()->logout();
            	return $this->goHome();
            }
            
            if ($profile->setup == "finish") {
                \Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'You already setupped your account.'
					)
				);
				return $this->redirect(["dashboard"]);
            }
        }
		

		$modelContact = \Yii::createObject(RequestContactForm::className());
		$modelProfile = \Yii::createObject(ProfileForm::className());

		$model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());

		if(empty($modelProfile->name)){
			$modelProfile = $model;
		}
		
		return $this->render('account', [
			'modelContact'  => $modelContact,
			'modelProfile'  => $modelProfile,
		]);
	}

	public function actionRequestcontact(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		$modelContact = \Yii::createObject(RequestContactForm::className());
		
		// Send contact request...
		if($modelContact->load(\Yii::$app->request->post()) && $modelContact->request()){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'Thank you, will call you when we are done.'
				)
			);
			return $this->redirect(['/user/security/login']);
		}
	}

	public function actionRequestcontact2(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		$modelProfile = \Yii::createObject(ProfileForm::className());
		
		// Send contact request...
		if($modelProfile->load(\Yii::$app->request->post()) && $modelProfile->requestContact()){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'Thank you, will call you when we are done.'
				)
			);
			return $this->redirect(['/user/security/login']);
		}
	}

	public function actionRequestcontact3(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		$modelProfile = \Yii::createObject(ProfileForm::className());
		
		$post = \Yii::$app->request->post();
		$locations = array();
		foreach ($post['business-location'] as $key => $value) {
			foreach ($value as $k => $v) {
				$locations[$k][$key] = $v;
			}
		}
		// Send contact request...
		if($modelProfile->load($post) && $modelProfile->requestContact3($locations)){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'Thank you, will call you when we are done.'
				)
			);
			return $this->redirect(['/user/security/login']);
		}
	}

	public function actionProfilesetup(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		// echo "<pre>"; print_r(\Yii::$app->request->post()); exit;
		$modelContact = \Yii::createObject(RequestContactForm::className());
		$modelProfile = \Yii::createObject(ProfileForm::className());
		
		$number_of_locations = \Yii::$app->request->post()['no_of_location'];
		$modelProfile->load(\Yii::$app->request->post());
		// $modelProfile->setup = \Yii::$app->request->post()['Profile']['setup'];

		$model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
		$model->setup = \Yii::$app->request->post()['Profile']['setup'];
		if(empty($modelProfile->name)){
			$modelProfile = $model;
		}

		if ($model == null) {
			$model = \Yii::createObject(Profile::className());
			$model->link('user', \Yii::$app->user->identity);
		}

		$event = $this->getProfileEvent($model);

		$this->performAjaxValidation($model);

		$this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
		$modelProfile->request($model);
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			$modelLocation = \Yii::createObject(BusinessLocationForm::className());
			
			$this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
			return $this->render('accountstep2', [
				'modelProfile'  => $modelProfile,
				'modelLocation'  => $modelLocation,
				'number_of_locations' => $number_of_locations,
				'business_location' => array(),
			]);
		}

		return $this->render('account', [
			'modelContact'  => $modelContact,
			'modelProfile'  => $modelProfile,
		]);
	}

	public function actionLocation(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		$modelProfile = \Yii::createObject(ProfileForm::className());
		$post = \Yii::$app->request->post();
		$business_location = array();
		if(!empty($post)){
			foreach ($post['business-location'] as $key => $value) {
				foreach ($value as $k => $v) {
					$business_location[$k]['business-location'][$key] = $v;
				}
			}
		}
		$modelProfile->load($post);
		$model = $this->finder->findProfileById(\Yii::$app->user->identity->getId());
		if(!empty($post)){
			$model->setup = $post['Profile']['setup'];
		}

		$emailTemplateMap = \Yii::createObject(EmailTemplateMap::className());
		$user_templates = $emailTemplateMap->findAll(['user_id'=>\Yii::$app->user->id]);
		if(!empty($user_templates)){
			$user_template = $user_templates[0];
		} else {
			$user_template = array();
		}

		// $emailTemplate = \Yii::createObject(EmailTemplate::className());
		// $email_template = $emailTemplate->findAll([1,2,3,4]);
		$email_template = EmailTemplate::find()->where([])->all();;
		// echo "<pre>"; print_r($email_template); exit;

		// if(empty($modelProfile->name)){
		//     $modelProfile = $model;
		// }

		if ($model == null) {
			$model = \Yii::createObject(Profile::className());
			$model->link('user', \Yii::$app->user->identity);
		}

		$event = $this->getProfileEvent($model);
		$this->performAjaxValidation($model);
		$this->trigger(self::EVENT_BEFORE_PROFILE_UPDATE, $event);
		$modelProfile->request($model);
		if ($model->load($post) && $model->save()) {
			$this->trigger(self::EVENT_AFTER_PROFILE_UPDATE, $event);
			$return = 0;
			foreach ($business_location as $key => $value) {
				$modelLocation = \Yii::createObject(BusinessLocationForm::className());
				if(!empty($value['business-location']['business_name']) && !empty($value['business-location']['location_name']) && !empty($value['business-location']['phone'])){
					$return = $modelLocation->request($value);
				}
			}
			if($return){
				$modelEmailTemplate = \Yii::createObject(EmailTemplateForm::className());
				return $this->render('accountstep3', [
					'modelProfile'  => $modelProfile,
					'modelLocation'  => $modelLocation,
					'business_location' => $business_location,
					'modelEmailTemplate' => $modelEmailTemplate,
					'email_template' => $email_template,
					'user_template' => $user_template,
				]);
			} else {
				return $this->render('accountstep2', [
					'modelProfile'  => $modelProfile,
					'modelLocation'  => $modelLocation,
					'number_of_locations' => $post['no_of_location'],
					'business_location' => $business_location,
				]);
			}
		} else {
			$modelLocation = \Yii::createObject(BusinessLocationForm::className());
			return $this->render('accountstep2', [
					'modelProfile'  => $modelProfile,
					'modelLocation'  => $modelLocation,
					'number_of_locations' => 2,
					'business_location' => $business_location,
				]);
		}
	}

	public function actionFinalsetup(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		$post = \Yii::$app->request->post();

		// $post_templates = [];
		// foreach ($post['email-template-form'] as $key => $value) {
		// 	$post_templates[]['email-template-form'] = $value;
		// }
		
		// $emailTemplateForm = \Yii::createObject(EmailTemplateForm::className());
		// $emailTemplateForm->load($post);

		$return = false;
		foreach ($post['email-template-form'] as $key => $value) {
			$emailTemplate = \Yii::createObject(EmailTemplateMap::className());
			$emailTemplate->user_id = \Yii::$app->user->id;
			$emailTemplate->title = $value['title'];
			$emailTemplate->template = $value['template'];
			$emailTemplate->template_id = $value['template_id'];
			$emailTemplate->active = 1;
			$return = $emailTemplate->add();
		}
		
		if($return){
			Profile::updateAll($post['Profile'], ['user_id' => \Yii::$app->user->id]);
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'Thank you, you successfully setup your account.'
				)
			);
			return $this->redirect(['/user/profile']);
		} else {
			\Yii::$app->session->setFlash(
				'warning',
				\Yii::t(
					'user',
					'Something went to wrong, please try again.'
				)
			);
			return $this->redirect(['/user/clientDashboard/account']);
		}
	}





	/**
	 * Shows client's Dashboard.
	 *
	 * @return \yii\web\Response
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function actionDashboard($activeTab = "addStaff"){
        if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		$registrationModel = \Yii::createObject(RegistrationForm::className());
		$RequestFeedbackModel = \Yii::createObject(RequestFeedbackForm::className());
		$modelProfile = \Yii::createObject(ProfileForm::className());

        $role = \Yii::$app->user->identity->getRole();

        if($role == "client_staff"){
			$location_map = StaffLocationMap::findAll(['staff_id'=>\Yii::$app->user->identity->getId()]);
			$locations = array();
			foreach ($location_map as $map) {
				$location = BusinessLocation::find()->where(['id' => $map->getAttribute("location_id")])->one();
				$locations[$location->getAttribute("id")] = $location->getAttribute("location_name");
			}
		} else {
			$location_map = BusinessLocation::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);
			foreach ($location_map as $map) {
				$locations[$map->getAttribute("id")] = $map->getAttribute("location_name");
			}
		}

        return $this->render('addstaff', [
			'registrationModel' => $registrationModel,
			'RequestFeedbackModel' => $RequestFeedbackModel,
			'modelProfile' => $modelProfile,
			'role' => $role,
			'locations' => $locations,
		]);
	}

	public function actionImportcustomer(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		$importUserModel = \Yii::createObject(ImportUserForm::className());
        $role = \Yii::$app->user->identity->getRole();

        if($role == "client_staff"){
			$location_map = StaffLocationMap::findAll(['staff_id'=>\Yii::$app->user->identity->getId()]);
			$locations = array();
			foreach ($location_map as $map) {
				$location = BusinessLocation::find()->where(['id' => $map->getAttribute("location_id")])->one();
				$locations[$location->getAttribute("id")] = $location->getAttribute("location_name");
			}
		} else {
			$location_map = BusinessLocation::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);
			foreach ($location_map as $map) {
				$locations[$map->getAttribute("id")] = $map->getAttribute("location_name");
			}
		}
		
		return $this->render('importcustomer', [
			'importUserModel' => $importUserModel,
			'role' => $role,
			'locations' => $locations,
		]);
	}

	public function actionUploadcsv(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		$importUserModel = \Yii::createObject(ImportUserForm::className());
		$role = \Yii::$app->user->identity->getRole();

		if($role == "client_staff"){
			$location_map = StaffLocationMap::findAll(['staff_id'=>\Yii::$app->user->identity->getId()]);
			$locations = array();
			foreach ($location_map as $map) {
				$location = BusinessLocation::find()->where(['id' => $map->getAttribute("location_id")])->one();
				$locations[$location->getAttribute("id")] = $location->getAttribute("location_name");
			}
		} else {
			$location_map = BusinessLocation::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);
			foreach ($location_map as $map) {
				$locations[$map->getAttribute("id")] = $map->getAttribute("location_name");
			}
		}

		if (\Yii::$app->request->isPost) {
			$post = \Yii::$app->request->post();
			$location_id = $post['import-user-form']['location_id'];
			$importUserModel->csvfile = UploadedFile::getInstances($importUserModel, 'csvfile');
			if (!$importUserModel->upload($location_id)) {
				return $this->render('importcustomer', [
					'importUserModel' => $importUserModel,
					'role' => $role,
					'locations' => $locations,
				]);
			}
		}
		return $this->redirect(['/user/clientDashboard/importcustomer']);		
	}

	public function actionAddstaff(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		$registrationModel = \Yii::createObject(RegistrationForm::className());
		$RequestFeedbackModel = \Yii::createObject(RequestFeedbackForm::className());
		$modelProfile = \Yii::createObject(ProfileForm::className());
		$role = \Yii::$app->user->identity->getRole();

		if($role == "client_staff"){
			$location_map = StaffLocationMap::findAll(['staff_id'=>\Yii::$app->user->identity->getId()]);
			$locations = array();
			foreach ($location_map as $map) {
				$location = BusinessLocation::find()->where(['id' => $map->getAttribute("location_id")])->one();
				$locations[$location->getAttribute("id")] = $location->getAttribute("location_name");
			}
		} else {
			$location_map = BusinessLocation::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);
			foreach ($location_map as $map) {
				$locations[$map->getAttribute("id")] = $map->getAttribute("location_name");
			}
		}

		if ($registrationModel->load(\Yii::$app->request->post()) && $modelProfile->load(\Yii::$app->request->post())) {
			if($registrationModel->register()){
				$user = User::findAll(['username'=>$registrationModel->username]);
				if(!empty($user)){
					$user = $user[0];
					Profile::updateAll(\Yii::$app->request->post()['Profile'], ['user_id' => $user->getAttribute("id")]);
				}
				return $this->redirect(['/user/clientDashboard/dashboard']);
			} else {
				return $this->render('addstaff', [
					'registrationModel' => $registrationModel,
					'RequestFeedbackModel' => $RequestFeedbackModel,
					'modelProfile' => $modelProfile,
					'role' => $role,
					'locations' => $locations,
				]); 
			}
		}
		return $this->redirect(['/user/clientDashboard/dashboard']);
	}

	public function actionAddenduser(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		$registrationModel = \Yii::createObject(RegistrationForm::className());
		$RequestFeedbackModel = \Yii::createObject(RequestFeedbackForm::className());
		$modelProfile = \Yii::createObject(ProfileForm::className());
		$role = \Yii::$app->user->identity->getRole();

		if($role == "client_staff"){
			$location_map = StaffLocationMap::findAll(['staff_id'=>\Yii::$app->user->identity->getId()]);
			$locations = array();
			foreach ($location_map as $map) {
				$location = BusinessLocation::find()->where(['id' => $map->getAttribute("location_id")])->one();
				$locations[$location->getAttribute("id")] = $location->getAttribute("location_name");
			}
		} else {
			$location_map = BusinessLocation::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);
			foreach ($location_map as $map) {
				$locations[$map->getAttribute("id")] = $map->getAttribute("location_name");
			}
		}

		if ($RequestFeedbackModel->load(\Yii::$app->request->post())) {
			if(!$RequestFeedbackModel->request()){
				return $this->render('addstaff', [
					'registrationModel' => $registrationModel,
					'RequestFeedbackModel' => $RequestFeedbackModel,
					'modelProfile' => $modelProfile,
					'role' => $role,
					'locations' => $locations,
				]); 
			}
		}
		return $this->redirect(['/user/clientDashboard/dashboard']);
	}


	/**
	 * Shows client's Dashboard.
	 *
	 * @return \yii\web\Response
	 * @throws \yii\web\NotFoundHttpException
	 */
	public function actionRequestfeedback()
	{
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		$model = \Yii::createObject(RequestFeedbackForm::className());
		if(\Yii::$app->user->identity->role == "client_staff"){
			$locationsIds = StaffLocationMap::findAll(['staff_id'=>\Yii::$app->user->identity->getId()]);
			$locArr = array();
			foreach ($locationsIds as $loc) {
				array_push($locArr, $loc->getAttribute('location_id'));
			}
			$locationsObj = BusinessLocation::findAll(['id'=>$locArr]);
		} else {
			$locationsObj = BusinessLocation::findAll(['user_id'=>\Yii::$app->user->identity->getId()]);			
		}

		$activemodel = \Yii::createObject(RequestFeedback::className());
		$this->performAjaxValidation($model);
		if ($model->load(\Yii::$app->request->post())) {
			$model->request();
			$activemodel->location_id = '';
			$activemodel->setAttribute('firstname', '');
			$activemodel->setAttribute('lastname', '');
			$activemodel->setAttribute('email', '');
			$activemodel->setAttribute('mobile', '');
		}

		return $this->render('requestfeedback', [
			'model'  => $model,
			'locationsObj' => $locationsObj,
		]);
		/*$model = new RequestFeed();
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			echo 'here';exit;
			return $this->redirect(['view', 'id' => $model->code]);
		} else {
			return $this->render('requestfeedback', [
				'model' => $model,
			]);
		}*/
	}

	public function actionFeedbacks(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }

		if(!$this->checkUserIsLoggedIn2()){
			return $this->redirect(["/"]);
		}

		$feedbacks = Feedback::findAll(['user_id'=>\Yii::$app->user->id]);
		
		return $this->render('myfeedbacks', [
			'feedbacks'  => $feedbacks,
		]);
	}

	public function actionFeedback($id){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['account']); }
		
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		if($id === NULL || $id == 0){
			return $this->redirect(['/user/clientDashboard/feedbacks']);
		}

		$feedback = Feedback::find()->where(['id' => $id])->one();
		return $this->render('myfeedback', [
			'feedback'  => $feedback,
		]);
	}

	


	// MANAGE CLIENT STAFF
	public function actionManagestaff(){
		if(!$this->checkUserIsLoggedIn()){
			return $this->redirect(["/"]);
		}

		// get staffs id...
		$my_staffs = UserMap::findAll(['parent_id'=>\Yii::$app->user->id]);
		$user_ids = array();
		foreach ($my_staffs as $key => $staff) {
			$user_ids[] = $staff->getAttribute("user_id");
		}
		$staffs = Profile::findAll(['user_id' => $user_ids]);
		
		return $this->render('managestaff', [
            'staffs'  => $staffs,
        ]);
	}

	public function actionEditstaff($id) {
		if(empty($id)){return $this->redirect(["managestaff"]);}
		$registrationModel = User::find()->where(['id'=>$id])->one();
		$modelProfile = Profile::find()->where(['user_id'=>$id])->one();

		return $this->render("editstaff", [
			'registrationModel' => $registrationModel,
			'modelProfile' => $modelProfile,
		]);
	}

	public function actionUpdatestaff(){
		if (\Yii::$app->request->isPost) {
			$post = \Yii::$app->request->post();
			
			$registrationModel = User::find()->where(['id'=>$post['User']['id']])->one();
			$modelProfile = Profile::find()->where(['user_id'=>$post['User']['id']])->one();

			$registrationModel->load($post);
			$modelProfile->load($post);

			if($registrationModel->save() && Profile::updateAll($post['Profile'], ['user_id' => $post['User']['id']])){
				\Yii::$app->session->setFlash(
				'info',
					\Yii::t(
						'user',
						'Staff Member update successfully.'
					)
				);
			} else {
				return $this->render("editstaff", [
					'registrationModel' => $registrationModel,
					'modelProfile' => $modelProfile,
				]);
			}
		}
		return $this->redirect(['managestaff']);
	}

	public function actionDeletestaff($id) {
		if(empty($id)){return $this->redirect(["managestaff"]);}
		// $registrationModel = User::find()->where(['id'=>$id])->one();
		// $modelProfile = Profile::find()->where(['user_id'=>$id])->one();

		if(User::deleteAll(['id' => $id])){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'User deleted Successfully.'
				)
			);
			return $this->redirect(['managestaff']);
		}
	}

	public function actionConfirmstaff($id){
		if(empty($id)){return $this->redirect(["managestaff"]);}

		User::updateAll(['confirmed_at'=>time()], ['id' => $id]);

		\Yii::$app->session->setFlash(
			'info',
			\Yii::t(
				'user',
				'Member Confirmed Successfully.'
			)
		);

		return $this->redirect(["managestaff"]);
	}

	public function actionBlockstaff($id){
		if(empty($id)){return $this->redirect(["managestaff"]);}

		User::updateAll(['blocked_at'=>time()], ['id' => $id]);

		\Yii::$app->session->setFlash(
			'info',
			\Yii::t(
				'user',
				'Member Block Successfully.'
			)
		);

		return $this->redirect(["managestaff"]);
	}

	public function actionUnblockstaff($id){
		if(empty($id)){return $this->redirect(["managestaff"]);}

		User::updateAll(['blocked_at'=>NULL], ['id' => $id]);

		\Yii::$app->session->setFlash(
			'info',
			\Yii::t(
				'user',
				'Member Unblock Successfully.'
			)
		);

		return $this->redirect(["managestaff"]);
	}







	// COMMON METHODS
	public function checkUserIsLoggedIn(){
		if(\Yii::$app->user->isGuest){
			\Yii::$app->session->setFlash(
				'warning',
					\Yii::t(
						'user',
						'Sorry, you are not allowed to view this page.'
					)
				);
			return false;
		}
		$role = \Yii::$app->user->identity->getRole();
		if($role != "client_admin"){
			\Yii::$app->session->setFlash(
				'warning',
					\Yii::t(
						'user',
						'Sorry, you are not allowed to view this page.'
					)
				);
			return false;
		}
		return true;
	}

	public function checkUserIsLoggedIn2(){
		if(\Yii::$app->user->isGuest){
			\Yii::$app->session->setFlash(
				'warning',
					\Yii::t(
						'user',
						'Sorry, you are not allowed to view this page.'
					)
				);
			return false;
		}
		$role = \Yii::$app->user->identity->getRole();
		if($role != "client_admin" && $role != "client_staff"){
			\Yii::$app->session->setFlash(
				'warning',
					\Yii::t(
						'user',
						'Sorry, you are not allowed to view this page.'
					)
				);
			return false;
		}
		return true;
	}

	public function checkIsAccountSetuped(){
		if(\Yii::$app->user->isGuest){
			\Yii::$app->session->setFlash(
				'warning',
					\Yii::t(
						'user',
						'Sorry, you are not allowed to view this page.'
					)
				);
			return false;
		}
		$role = \Yii::$app->user->identity->getRole();
        if($role == "client_admin"){
            $profile = $this->finder->findProfileById(\Yii::$app->user->id);

            if ($profile->setup === null || $profile->setup != "finish") {
            	\Yii::$app->session->setFlash(
				'warning',
					\Yii::t(
						'user',
						'First you have to setup your account.'
					)
				);
                return false;
            }
        }
        return true;
	}
}

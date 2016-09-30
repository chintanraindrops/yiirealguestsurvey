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
use dektrium\user\models\User;
use dektrium\user\traits\EventTrait;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use dektrium\user\models\BusinessLocationForm;
use dektrium\user\models\BusinessLocation;
use dektrium\user\models\StaffLocationMap;
use dektrium\user\models\UserMap;
use dektrium\user\models\Profile;
use dektrium\user\models\EmailTemplateMap;
use dektrium\user\models\EmailTemplate;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * ProfileController shows users profiles.
 *
 * @property \dektrium\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class BusinessLocationController extends Controller
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

	/** @var guest */
	protected $guest;	

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
			// //header('location:'.Url::to('@web'.'/user/security/login', true));die();
			// \Yii::$app->response->redirect(Url::to('@web'.'/user/security/login', true));
			$this->guest = true;
		}
	}

	/** @inheritdoc */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'actions' => ['index'], 'roles' => ['@']],
					['allow' => true, 'actions' => ['locations', 'new', 'add', 'edit', 'delete', 'update', 'staffmap', 'deletemap', 'templates', 'addtemplate', 'newtemplate', 'edittemplate', 'deletetemplate', 'updatetemplate'], 'roles' => ['?', '@']],
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
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/user/security/login']);
		}
		return $this->redirect(['locations']);
	}

	public function actionLocations(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/user/security/login']);
		}
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$model = \Yii::createObject(BusinessLocation::className());
		$locations = $model->findAll(['user_id'=>\Yii::$app->user->id]);

		// get data...
			$assignedLocations = StaffLocationMap::findAll(['user_id' => \Yii::$app->user->id]);

			$modelUserMap = \Yii::createObject(UserMap::className());
			$users = $modelUserMap->findAll(['parent_id'=>\Yii::$app->user->id]);
			$show_users = array();
			if(!empty($users)){
				foreach ($users as $user) {
					$assigned = true;
					// foreach ($assignedLocations as $al) {
					// 	if($al->getAttribute("staff_id") == $user->getAttribute("user_id")){
					// 		$assigned = false;
					// 	}
					// }
					if($assigned){
						$show_users[] = $user->getAttribute("user_id");
					}
				}
			}
			$showUsers = array();
			$profiles = Profile::findAll(['user_id' => $show_users]);
			foreach ($profiles as $profile) {
				$showUsers[$profile->getAttribute("user_id")] = $profile->getAttribute("name");
			}
			$showLocations = array();
			foreach ($locations as $loc) {
				$showLocations[$loc->getAttribute("id")] = $loc->getAttribute("location_name");
			}

			$assigned_locations = array();
			$count = 0;
			foreach ($assignedLocations as $al) {
				$profiles = Profile::findAll(['user_id' => $al->getAttribute("staff_id")]);
				$profile = $profiles[0];
				$assigned_locations[$count]['staff_name'] = $profile->getAttribute("name");

				$locs = BusinessLocation::findAll(['id' => $al->getAttribute("location_id")]);
				$loc = $locs[0];
				$assigned_locations[$count]['location_name'] = $loc->getAttribute("location_name");;
				$assigned_locations[$count++]['assign_id'] = $al->getAttribute("id");
			}

		$modelMap = \Yii::createObject(StaffLocationMap::className());

		$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
		$business_name = $business_name->getAttribute("business_name");

		return $this->render('locations', [
			'locations'  => $locations,
			'modelMap'  => $modelMap,
			'showUsers'  => $showUsers,
			'showLocations'  => $showLocations,
			'assignedLocations'  => $assigned_locations,
			'business_name' => $business_name,
		]);
	}

	public function actionNew(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$locationForm = \Yii::createObject(BusinessLocationForm::className());

		$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
		$business_name = $business_name->getAttribute("business_name");

		return $this->render('addlocation', [
			'locationForm'  => $locationForm,
			'business_name' => $business_name,
		]);
	}

	public function actionEdit($id){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		if($id === NULL || $id == 0){
			return $this->redirect(["/user/businessLocation/locations"]);
		}

		$location = BusinessLocation::find()->where(['id' => $id])->one();
		$locationForm = \Yii::createObject(BusinessLocationForm::className());
		
		$locationForm->id = $location->getAttribute("id");
		$locationForm->user_id = $location->getAttribute("user_id");
		$locationForm->business_name = $location->getAttribute("business_name");
		$locationForm->location_name = $location->getAttribute("location_name");
		$locationForm->address = $location->getAttribute("address");
		$locationForm->city = $location->getAttribute("city");
		$locationForm->state = $location->getAttribute("state");
		$locationForm->zip = $location->getAttribute("zip");
		$locationForm->phone = $location->getAttribute("phone");
		$locationForm->google_page = $location->getAttribute("google_page");
		$locationForm->facebook_page = $location->getAttribute("facebook_page");
		$locationForm->trip_advisor = $location->getAttribute("trip_advisor");
		$locationForm->yelp = $location->getAttribute("yelp");
		$locationForm->google_place_id = $location->getAttribute("google_place_id");
		$locationForm->feedback_approach_text = $location->getAttribute("feedback_approach_text");
		$locationForm->profile_url = $location->getAttribute("profile_url");
		$locationForm->logo = $location->getAttribute("logo");

		$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
		$business_name = $business_name->getAttribute("business_name");

		return $this->render('editlocation', [
			'locationForm'  => $locationForm,
			'business_name'  => $business_name,
		]);
	}

	public function actionAdd(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$modelLocation = \Yii::createObject(BusinessLocation::className());
		$post = \Yii::$app->request->post();

		$modelLocation->user_id = \Yii::$app->user->id;
        $modelLocation->business_name = $post['business-location']['business_name'];
        $modelLocation->location_name = $post['business-location']['location_name'];
        $modelLocation->address = $post['business-location']['address'];
        $modelLocation->city = $post['business-location']['city'];
        $modelLocation->state = $post['business-location']['state'];
        $modelLocation->zip = $post['business-location']['zip'];
        $modelLocation->phone = $post['business-location']['phone'];
        $modelLocation->google_page = $post['business-location']['google_page'];
        $modelLocation->facebook_page = $post['business-location']['facebook_page'];
        $modelLocation->trip_advisor = $post['business-location']['trip_advisor'];
        $modelLocation->yelp = $post['business-location']['yelp'];
        $modelLocation->google_place_id = $post['business-location']['google_place_id'];
        $modelLocation->feedback_approach_text = $post['business-location']['feedback_approach_text'];
        $modelLocation->profile_url = $post['business-location']['profile_url'];

        $modelLocation->setAttribute("user_id", \Yii::$app->user->id);
        $modelLocation->setAttribute("business_name", $post['business-location']['business_name']);
        $modelLocation->setAttribute("location_name", $post['business-location']['location_name']);
        $modelLocation->setAttribute("address", $post['business-location']['address']);
        $modelLocation->setAttribute("city", $post['business-location']['city']);
        $modelLocation->setAttribute("state", $post['business-location']['state']);
        $modelLocation->setAttribute("zip", $post['business-location']['zip']);
        $modelLocation->setAttribute("phone", $post['business-location']['phone']);
        $modelLocation->setAttribute("google_page", $post['business-location']['google_page']);
        $modelLocation->setAttribute("facebook_page", $post['business-location']['facebook_page']);
        $modelLocation->setAttribute("trip_advisor", $post['business-location']['trip_advisor']);
        $modelLocation->setAttribute("yelp", $post['business-location']['yelp']);
        $modelLocation->setAttribute("google_place_id", $post['business-location']['google_place_id']);
        $modelLocation->setAttribute("feedback_approach_text", $post['business-location']['feedback_approach_text']);
        $modelLocation->setAttribute("profile_url", $post['business-location']['profile_url']);

        $profile_url = $post['business-location']['profile_url'];

		if(!empty($profile_url)){
			$locationData = BusinessLocation::find()->where(['profile_url' => $profile_url])->one();

			if ($locationData !== null) {
				\Yii::$app->session->setFlash(
						'error',
						\Yii::t(
							'user',
							'Profile Name Already Taken.'
						)
					);
				
					$locationForm = \Yii::createObject(BusinessLocationForm::className());

					$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
					$business_name = $business_name->getAttribute("business_name");

					return $this->render('addlocation', [
						'locationForm'  => $locationForm,
						'business_name' => $business_name,
					]);
	        }
		}
        
        if($modelLocation->save()){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'New Location Added Successfully.'
				)
			);
			return $this->redirect(['locations']);
		}
	}

	public function actionUpdate(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$post = \Yii::$app->request->post();
		$id = $post['business-location']['id'];
		$profile_url = $post['business-location']['profile_url'];

		if(!empty($profile_url)){
			$locationData = BusinessLocation::find()->where('id != :id and profile_url = :profile_url', ['profile_url' => $profile_url, 'id' => $id])->one();

			if ($locationData !== null) {
				\Yii::$app->session->setFlash(
						'error',
						\Yii::t(
							'user',
							'Profile Name Already Taken.'
						)
					);
					return $this->redirect(['edit', "id"=>$id]);
	        }
		}

		if(!empty($id)) {
			
			$businessLocationActive = BusinessLocation::find()->where(['id' => $id])->one();
			$businessLocation = new BusinessLocationForm();

			$businessLocation->logo = UploadedFile::getInstance($businessLocation, 'logo');
			$businessLocation->setData($post);

			$uploaded_file = $businessLocation->upload();
	        if($uploaded_file){
	        	$businessLocationActive->setAttribute('logo', $uploaded_file);
	        	$post['business-location']['logo'] = $uploaded_file;
	        } else {
	        	$post['business-location']['logo'] = $businessLocationActive->getAttribute('logo');
	        }

			if(BusinessLocation::updateAll($post['business-location'], ['id' => $id])){
				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Location Update Successfully.'
					)
				);
				return $this->redirect(['locations']);
			} else {
				// return $this->redirect(['edit', "id"=>$id]);
				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Location Update Successfully.'
					)
				);
				return $this->redirect(['locations']);
			}
		}

	}

	public function actionDelete($id){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		if(!empty($id)) {
			if(BusinessLocation::deleteAll(['id' => $id])){
				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Location deleted Successfully.'
					)
				);
				return $this->redirect(['locations']);
			}
		}
	}

	public function checkIsAccountSetuped(){
		if($this->guest){return false;}
        $role = \Yii::$app->user->identity->getRole();
        if($role == "client_admin"){
            $profile = $this->finder->findProfileById(\Yii::$app->user->id);
            if ($profile->setup === null || $profile->setup != "finish") {
                return false;
            }
        }
        return true;
    }

    public function actionStaffmap(){
    	$post = \Yii::$app->request->post();
    	$modelMap = \Yii::createObject(StaffLocationMap::className());
    	
    	$modelMap->user_id = \Yii::$app->user->id;
        $modelMap->staff_id = $post['StaffLocationMap']['staff_id'];
        $modelMap->location_id = $post['StaffLocationMap']['location_id'];

        $map = \Yii::createObject(StaffLocationMap::className());
		$staffs = $map->findAll(['staff_id'=>$post['StaffLocationMap']['staff_id']]);
		$add = true;
		if(count($staffs)){
			foreach ($staffs as $staff) {
				if($staff->getAttribute("location_id") == $post['StaffLocationMap']['location_id']){
					$add = false;
				}
			}
		}

		if(!$add){
			\Yii::$app->session->setFlash(
				'warning',
				\Yii::t(
					'user',
					'You already assigned.'
				)
			);
			return $this->redirect(['locations']);
		}

        if($add && $modelMap->add()){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'Location Assigned Successfully.'
				)
			);
			return $this->redirect(['locations']);
		}    	
    }

    public function actionDeletemap($id){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		if(!empty($id)) {
			if(StaffLocationMap::deleteAll(['id' => $id])){
				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Location deleted Successfully.'
					)
				);
				return $this->redirect(['locations']);
			}
		}
	}

	public function actionTemplates(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/user/security/login']);
		}
		$role = \Yii::$app->user->identity->getRole();
		if($role == "client_staff"){return $this->redirect(['/user/clientDashboard/dashboard']);}
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$emailTemplates = \Yii::createObject(EmailTemplateMap::className());
		$templates = $emailTemplates->findAll(['user_id'=>\Yii::$app->user->id]);

		$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
		$business_name = $business_name->getAttribute("business_name");

		$email_templates1 = EmailTemplate::find()->where([])->all();
		$email_templates2 = EmailTemplateMap::find()->where(['user_id' => \Yii::$app->user->id])->all();
		$allow_add = true;
		if(count($email_templates2) >= count($email_templates1)){
			$allow_add = false;
		}

		return $this->render('templates', [
			'templates'  => $templates,
			'business_name' => $business_name,
			'allow_add' => $allow_add,
		]);
	}

	public function actionAddtemplate(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$post = \Yii::$app->request->post();

		$emailTemplate = \Yii::createObject(EmailTemplateMap::className());
		$emailTemplate->user_id = \Yii::$app->user->id;
		$emailTemplate->title = $post['EmailTemplateMap']['title'];
		$emailTemplate->template = $post['EmailTemplateMap']['template'];
		$emailTemplate->template_id = $post['EmailTemplateMap']['template_id'];
		$emailTemplate->active = $post['EmailTemplateMap']['active'];

		if($post['EmailTemplateMap']['active'] == 1){
			$email_templates = EmailTemplateMap::find()->where([])->all();
			foreach ($email_templates as $key => $value) {
				EmailTemplateMap::updateAll(['active'=>0]);
			}
		}
		
		if($emailTemplate->add()){
			\Yii::$app->session->setFlash(
				'info',
				\Yii::t(
					'user',
					'New Email Template Added Successfully.'
				)
			);
			return $this->redirect(['templates']);
		}
	}

	public function actionNewtemplate(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$templateForm = \Yii::createObject(EmailTemplateMap::className());

		$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
		$business_name = $business_name->getAttribute("business_name");

		$email_templates = EmailTemplate::find()->where([])->all();
		$templates = array();
		foreach ($email_templates as $key => $template) {
			$temp = EmailTemplateMap::find()->where(['user_id'=>\Yii::$app->user->id, 'template_id'=>$template->getAttribute("id")])->one();
			if(empty($temp)){
				$templates[$template->getAttribute("id")] = $template->getAttribute("title");
			} else {
				unset($email_templates[$key]);
			}
		}
		// $templates = $emailTemplates->findAll(['user_id'=>\Yii::$app->user->id]);

		return $this->render('addtemplate', [
			'templateForm'  => $templateForm,
			'business_name' => $business_name,
			'templates' => $templates,
			'email_templates' => $email_templates,
		]);
	}

	public function actionEdittemplate($id){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		if($id === NULL || $id == 0){
			return $this->redirect(["/user/businessLocation/templates"]);
		}

		// $templateForm = \Yii::createObject(EmailTemplateMap::className());
		$templateForm = EmailTemplateMap::find()->where(['user_id'=>\Yii::$app->user->id, 'id'=>$id])->one();

		$business_name = Profile::find()->where(['user_id' => \Yii::$app->user->id])->one();
		$business_name = $business_name->getAttribute("business_name");

		return $this->render('edittemplate', [
			'templateForm'  => $templateForm,
			'business_name'  => $business_name,
		]);
	}

	public function actionUpdatetemplate(){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		$post = \Yii::$app->request->post();
		$id = $post['EmailTemplateMap']['id'];

		// if($post['EmailTemplateMap']['active'] == 1){
		// 	$email_templates = EmailTemplateMap::find()->where([])->all();
		// 	foreach ($email_templates as $key => $value) {
		// 		EmailTemplateMap::updateAll(['active'=>0]);
		// 	}
		// }
		
		if(!empty($id)) {
			if(EmailTemplateMap::updateAll($post['EmailTemplateMap'], ['id' => $id])){
				// $template_active = EmailTemplateMap::find()->where([
				// 							'user_id' => \Yii::$app->user->id, 
				// 							'active' => 1
				// 						])->all();
				// if(empty($template_active)){
				// 	$template = EmailTemplateMap::find()->where(['user_id' => \Yii::$app->user->id])->one();
				// 	EmailTemplateMap::updateAll(['active' => 1], ['id' => $template->getAttribute("id")]);
				// }

				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Email Template Update Successfully.'
					)
				);
				return $this->redirect(['templates']);
			} else {
				// \Yii::$app->session->setFlash(
				// 	'warning',
				// 	\Yii::t(
				// 		'user',
				// 		'Something went wrong or you did not change anything, please try again.'
				// 	)
				// );
				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Email Template Update Successfully.'
					)
				);
				return $this->redirect(['templates']);
			}
		}
	}

	public function actionDeletetemplate($id){
		if(!$this->checkIsAccountSetuped()) { return $this->redirect(['/user/clientDashboard/account']); }

		if(!empty($id)) {
			if(EmailTemplateMap::deleteAll(['id' => $id])){
				// $template_active = EmailTemplateMap::find()->where([
				// 							'user_id' => \Yii::$app->user->id, 
				// 							'active' => 1
				// 						])->all();
				// $template_active1 = EmailTemplateMap::find()->where(['user_id' => \Yii::$app->user->id])->all();
				// if(empty($template_active) && !empty($template_active1)){
				// 	$template = EmailTemplateMap::find()->where(['user_id' => \Yii::$app->user->id])->one();
				// 	EmailTemplateMap::updateAll(['active' => 1], ['id' => $template->getAttribute("id")]);
				// }

				\Yii::$app->session->setFlash(
					'info',
					\Yii::t(
						'user',
						'Email Template deleted Successfully.'
					)
				);
				return $this->redirect(['templates']);
			}
		}
	}
}

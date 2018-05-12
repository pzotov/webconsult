<?php

namespace app\controllers;

use app\models\BonusCategory;
use app\models\Manager;
use app\models\Stat;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\data\Pagination;

class ManagersController extends Controller {
	
	/**
	 * {@inheritdoc}
	 */
	public function actions(){
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
	 * Выводим список менеджеров
	 *
	 * @return string
	 */
	public function actionIndex(){
		$request = Yii::$app->request;
		
		$query = Manager::find();
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'page' => $request->get('page', 1) - 1
		]);
		
		$rows = $query
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy([
				'name' => SORT_ASC
			])
			->all();
		
		return $this->render('index', [
			'rows' => $rows,
			'pages' => $pages
		]);
		
	}
	
	/**
	 * Добавление/редактирование менеджера
	 *
	 * @return string
	 */
	public function actionEdit($id = 0){
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		if($id){
			if(!($manager = Manager::findOne($id))){
				$session->addFlash("error", "Неверный ID менеджера");
				return $this->redirect(['managers/index'], 302);
			}
		} else {
			$manager = new Manager();
		}
		
		if($request->isPost){
			if($manager->load($request->post()) && $manager->save()){
				$session->addFlash("success", "Сохранено");
				return $this->redirect(['managers/index'], 302);
			}
			$session->addFlash("error", "Ошибка сохранения");
		}
		return $this->render('edit', [
			'model' => $manager
		]);
	}
	
	/**
	 * Удаление менеджера
	 *
	 * @return string
	 */
	public function actionDelete($id){
		$session = Yii::$app->session;
		
		if(!$id || !($manager = Manager::findOne($id))){
			$session->addFlash("error", "Неверный ID менеджера");
			return $this->redirect(['managers/index'], 302);
		}
		$manager->delete();
		
		$session->addFlash("success", "Менеджер удален");
		return $this->redirect(['managers/index'], 302);
	}
	
	/**
	 * Расчет зарплаты менеджера
	 *
	 * @return string
	 */
	public function actionSalary($id){
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		if(!$id || !($manager = Manager::findOne($id))){
			$session->addFlash("error", "Неверный ID менеджера");
			return $this->redirect(['managers/index'], 302);
		}
		
		$month = $request->post('month', date('m/Y'));
		if(preg_match('%^(\d\d)/(\d{4})$%ims', $month, $m)){
			$sql_month = $m[2].'-'.$m[1];
		} else {
			$month = date('m/Y');
			$sql_month = date('Y-m');
		}
		
		$bonuses = 0;
		
		$calls = Stat::find()
			->where(['manager_id' => $manager->id])
			->andWhere(['like', 'date', $sql_month.'%', false])
			->sum('calls')
		;
		$calc_info = [];
		//Выбираем, по каким категориями рассчитывать бонусы за звонки,
		//выбираем только категории, минимальное значение которых ниже количества звонков
		if($categories = BonusCategory::find()
			->where(['<=', 'bottom_line', $calls])
			->orderBy(['bottom_line' => SORT_DESC])
			->all()){
			$rest_of_calls = $calls;
			foreach($categories as $category){
				//подсчитываем, сколько звонков тарифицируются по данной категории
				$calls_to_process = $rest_of_calls - $category->bottom_line + 1;
				$value = $category->value * $calls_to_process;
				$bonuses += $value;
				$calc_info[] = [
					'calls' => $calls_to_process,
					'category' => $category,
					'value' => $value
				];
				$rest_of_calls -= $calls_to_process;
			}
		}
		
		return $this->render('salary', [
			'manager' => $manager,
			'month' => $month,
			'bonuses' => $bonuses,
			'calls' => $calls,
			'calc_info' => $calc_info
		]);
	}
	
	
}

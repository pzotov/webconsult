<?php

namespace app\controllers;

use app\models\Manager;
use app\models\Stat;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class StatsController extends Controller {
	/**
	 * Выводим таблицу с количеством сделанных звонков
	 *
	 * @return string
	 */
	public function actionIndex($id = 0){
		$request = Yii::$app->request;
		
		$query = Stat::find();
		if($id && ($manager = Manager::findOne($id))){
			$query->where(['manager_id' => $id]);
		} else {
			$manager = null;
		}
		
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'page' => $request->get('page', 1) - 1
		]);
		
		$rows = $query
			->with('manager')
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy([
				'date' => SORT_ASC,
				'manager_id' => SORT_ASC
			])
			->all();
		
		return $this->render('index', [
			'rows' => $rows,
			'pages' => $pages,
			'manager' => $manager
		]);
		
	}
	
	/**
	 * Добавление/редактирование информации о сделанных звонках за день
	 *
	 * @return string
	 */
	public function actionEdit($id = 0){
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		if($id){
			if(!($stat = Stat::findOne($id))){
				$session->addFlash("error", "Неверный ID записи");
				return $this->redirect(['stats/index'], 302);
			}
		} else {
			$stat = new Stat();
		}
		
		if($request->isPost){
			if($stat->load($request->post()) && $stat->save()){
				$session->addFlash("success", "Сохранено");
				return $this->redirect(['stats/index'], 302);
			}
			
			$session->addFlash("error", "Ошибка сохранения");
		}
		
		$managersList = ArrayHelper::map(Manager::find()
			->select(['id', 'name'])
			->orderBy(['name' => SORT_ASC])
			->all(), 'id', 'name');
		
		return $this->render('edit', [
			'model' => $stat,
			'managersList' => $managersList
		]);
	}
	
	/**
	 * Удаление записи о звонках
	 *
	 * @return string
	 */
	public function actionDelete($id){
		$session = Yii::$app->session;
		
		if(!$id || !($stat = Stat::findOne($id))){
			$session->addFlash("error", "Неверный ID записи");
			return $this->redirect(['stats/index'], 302);
		}
		$stat->delete();
		
		$session->addFlash("success", "Запись удалена");
		return $this->redirect(['stats/index'], 302);
	}
}

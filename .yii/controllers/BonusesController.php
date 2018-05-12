<?php

namespace app\controllers;

use app\models\BonusCategory;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\data\Pagination;

/**
 * Class BonusesController
 * @package app\controllers
 */
class BonusesController extends Controller {
	/**
	 * Выводим таблицу системы начисления бонусов
	 *
	 * @return string
	 */
	public function actionIndex(){
		$request = Yii::$app->request;
		
		$query = BonusCategory::find();
		$countQuery = clone $query;
		$pages = new Pagination([
			'totalCount' => $countQuery->count(),
			'page' => $request->get('page', 1) - 1
		]);
		
		$rows = $query
			->offset($pages->offset)
			->limit($pages->limit)
			->orderBy([
				'bottom_line' => SORT_ASC
			])
			->all();
		
		return $this->render('index', [
			'rows' => $rows,
			'pages' => $pages
		]);
		
	}
	
	/**
	 * Добавление/редактирование категории
	 *
	 * @return string
	 */
	public function actionEdit($id = 0){
		$request = Yii::$app->request;
		$session = Yii::$app->session;
		
		if($id){
			if(!($category = BonusCategory::findOne($id))){
				$session->addFlash("error", "Неверный ID категории");
				return $this->redirect(['bonuses/index'], 302);
			}
		} else {
			$category = new BonusCategory();
		}
		
		if($request->isPost){
			if($category->load($request->post()) && $category->save()){
				$session->addFlash("success", "Сохранено");
				return $this->redirect(['bonuses/index'], 302);
			}
			$session->addFlash("error", "Ошибка сохранения");
		}
		return $this->render('edit', [
			'model' => $category
		]);
	}
	
	/**
	 * Удаление категории
	 *
	 * @return string
	 */
	public function actionDelete($id){
		$session = Yii::$app->session;
		
		if(!$id || !($category = BonusCategory::findOne($id))){
			$session->addFlash("error", "Неверный ID категории");
			return $this->redirect(['bonuses/index'], 302);
		}
		$category->delete();
		
		$session->addFlash("success", "Категория удалена");
		return $this->redirect(['bonuses/index'], 302);
	}
}

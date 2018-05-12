<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Менеджер
 */
class Manager extends ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function rules(){
		return [
			[['name', 'salary'], 'required', 'message' => '{attribute} – обязательное поле'],
			[['name'], 'string'],
			[['salary'], 'double'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels(){
		return [
			'name' => 'ФИО',
			'salary' => 'Оклад, руб.'
		];
	}
}

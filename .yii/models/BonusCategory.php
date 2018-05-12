<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Категория начисления бонусов
 */
class BonusCategory extends ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function rules(){
		return [
			[['name', 'bottom_line', 'value'], 'required', 'message' => '{attribute} – обязательное поле'],
			[['name'], 'string'],
			[['bottom_line'], 'integer', 'min' => 1],
			[['bottom_line'], 'unique', 'message' => 'Для значения {value} уже есть другая категория'],
			[['value'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels(){
		return [
			'name' => 'Название категории',
			'bottom_line' => 'Минимальное количество звонков для начисления бонусов по этой категории (включительно)',
			'top_line' => 'Максимальное количество звонков для начисления бонусов по этой категории',
			'value' => 'Бонус начисления к окладу за звонок'
		];
	}
	
	/**
	 * Возвращает верхнюю границу количества звонков
	 * @return integer
	 */
	public function getTop_line(){
		$top_line = self::find()
			->select('bottom_line')
			->where(['>', 'bottom_line', $this->bottom_line])
			->orderBy(['bottom_line' => SORT_ASC])
			->scalar();
		if($top_line) return $top_line-1;
		return 0;
	}
}

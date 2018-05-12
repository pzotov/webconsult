<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Статистика обработанных звонков
 */
class Stat extends ActiveRecord {
	/**
	 * @inheritdoc
	 */
	public function behaviors(){
		return [
			[
				'class' => TimestampBehavior::className(),
				'attributes' => [
					ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
				],
			],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules(){
		return [
			[['manager_id'], 'required', 'message' => '{attribute} – обязательное поле'],
			[['manager_id', 'calls'], 'integer'],
			[['date'], 'date', 'format' => 'yyyy-MM-dd'],
			[['manager_id', 'date'], 'unique', 'targetAttribute' => ['manager_id', 'date'], 'message' => 'Количество звонков для этого менеджера на эту дату уже задано'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels(){
		return [
			'manager_id' => 'Менеджер',
			'date' => 'Дата',
			'calls' => 'Количество звонков',
		];
	}
	
	/**
	 * Возвращает соответствующего менеджера
	 * @return yii\db\ActiveQuery
	 */
	public function getManager(){
		return $this->hasOne(Manager::class, ['id' => 'manager_id']);
	}
	
	/**
	 * Выводит дату в формате DD.MM.YYYY
	 * @return string
	 */
	public function getFormatted_date(){
		return preg_replace('%(\d{4})-(\d\d)-(\d\d)%ims', '$3.$2.$1', $this->date);
	}
}

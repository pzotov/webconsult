<?php
/**
 * @var $this yii\web\View
 * @var $manager app\models\Manager менеджер
 * @var $month string Месяц, за который делается расчет
 * @var $calls integer Сколько звонков обработано за месяц
 * @var $bonuses integer Бонусы за звонки
 * @var $calc_info array Информация о расчете бонусов
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Расчет зарплаты менеджера '.$manager->name.' за месяц';
?>

<?= Html::beginForm(['managers/salary', 'id' => $manager->id]) ?>



<div class="form-group">
	<label for="month" class="control-label">Выберите месяц</label>
	<?= DatePicker::widget([
		'name' => 'month',
		'id' => 'month',
		'value' => $month,
		'options' => [
			'readonly' => true,
			'class' => 'form-control'
		],
		'language' => 'ru',
		'dateFormat' => 'MM/yyyy',
		'clientOptions' => [
			'changeMonth' => true,
	        'changeYear' => true,
	        'showButtonPanel' => true,
			'onClose' => new \yii\web\JsExpression('function(dateText, inst) {


                            function isDonePressed(){
                                return ($(\'#ui-datepicker-div\').html().indexOf(\'ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover\') > -1);
                            }

                            if (isDonePressed()){
                                var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
                                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                                $(this).datepicker(\'setDate\', new Date(year, month, 1)).trigger(\'change\');
                                
                                 $(\'.date-picker\').focusout()//Added to remove focus from datepicker input box on selecting date
                            }
                        }'),
			'beforeShow' => new \yii\web\JsExpression('function(input, inst) {

                            inst.dpDiv.addClass(\'month_year_datepicker\')

                            if ((datestr = $(this).val()).length > 0) {
                                year = datestr.substring(datestr.length-4, datestr.length);
                                month = datestr.substring(0, 2);
                                $(this).datepicker(\'option\', \'defaultDate\', new Date(year, month-1, 1));
                                $(this).datepicker(\'setDate\', new Date(year, month-1, 1));
                                $(".ui-datepicker-calendar").hide();
                            }
                        }'),
			
		]
	]);
	?>
</div>

<div class="form-actions">
	<?= Html::submitButton('Рассчитать зарплату', ['class' => 'btn btn-primary']) ?>
	<a href="<?= Url::to(['managers/index']) ?>" class="btn btn-default pull-right">Вернуться к списку менеджеров</a>
</div>

<?= Html::endForm() ?>

<table class="table table-border margin-top">
	<tr>
		<td>Оклад</td>
		<td><?= $manager->salary ?> руб.</td>
	</tr>
	<tr>
		<td>Количество звонков за месяц</td>
		<td><?= $calls ?></td>
	</tr>
	<tr>
		<td>Бонусы за обработанные звонки</td>
		<td><?= $bonuses ?> руб.</td>
	</tr>
	<tr>
		<th>Итого</th>
		<th><?= $manager->salary + $bonuses ?> руб.</th>
	</tr>
</table>

<?php if(count($calc_info)){ ?>
<h4>Расчет бонусов</h4>

<table class="table table-border margin-top">
	<tr>
		<th>Категория</th>
		<th>Количество звонков</th>
		<th>Бонусы</th>
	</tr>
<?php foreach($calc_info as $calc){ ?>
	<tr>
		<td>
			<strong><?= $calc['category']->name ?></strong><br>
			<?= $calc['category']->value ?> руб. за звонок
		</td>
		<td><?= $calc['calls'] ?></td>
		<td><?= $calc['value'] ?> руб.</td>
	</tr>
<?php } ?>
	<tr>
		<th colspan="2" class="text-right">Итого:</th>
		<th><?= $bonuses ?> руб.</th>
	</tr>
</table>
<?php } ?>
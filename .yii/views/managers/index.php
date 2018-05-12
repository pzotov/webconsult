<?php
/**
 * @var $this yii\web\View
 * @var $rows app\models\Manager[] Список менеджеров
 * @var $pages yii\data\Pagination Постраничная навигация
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Менеджеры";
?>

<div class="text-right">
	<a href="<?= Url::to(['managers/edit', 'id' => 0]) ?>" class="btn btn-primary">Добавить менеджера</a>
</div>

	<table class="table table-hover nowrap" width="100%">
		<thead>
		<tr>
			<th>#</th>
			<th>ФИО</th>
			<th>Оклад</th>
			<th width="200"></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($rows as $i => $row){ ?>
			<tr>
				<td><?= $pages->pageSize*$pages->page + $i + 1 ?></td>
				<td><?= $row->name ?></td>
				<td><?= $row->salary ?> руб.</td>
				<td class="text-right">
					<a href="<?= Url::toRoute(['stats/index', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Информация об обработанных звонках"><i class="glyphicon glyphicon-list"></i></a>
					<a href="<?= Url::toRoute(['managers/salary', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Расчет зарплаты"><i class="glyphicon glyphicon-ruble"></i></a>
					<a href="<?= Url::toRoute(['managers/edit', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Редактировать"><i class="glyphicon glyphicon-pencil text-success"></i></a>
					<a href="<?= Url::toRoute(['managers/delete', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Удалить" onclick="return confirm('Вы действительно хотите удалить менеджера <?= $row->name ?>?');"><i class="glyphicon glyphicon-remove text-danger"></i></a>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

<?php
// отображаем ссылки на страницы
echo \yii\widgets\LinkPager::widget([
	'pagination' => $pages,
]);
?>


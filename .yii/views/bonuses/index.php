<?php
/**
 * @var $this yii\web\View
 * @var $rows app\models\BonusCategory[] Список категорий
 * @var $pages yii\data\Pagination Постраничная навигация
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Система начислений бонусов";
?>

<div class="text-right">
	<a href="<?= Url::to(['bonuses/edit', 'id' => 0]) ?>" class="btn btn-primary">Добавить категорию</a>
</div>

	<table class="table table-hover nowrap" width="100%">
		<thead>
		<tr>
			<th>#</th>
			<th>Категория</th>
			<th>Количество звонков</th>
			<th>Бонусы за звонок</th>
			<th width="140"></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($rows as $i => $row){ ?>
			<tr>
				<td><?= $pages->pageSize*$pages->page + $i + 1 ?></td>
				<td><?= $row->name ?></td>
				<td>
					<?= $row->bottom_line ? 'от '.$row->bottom_line : '' ?>
					<?= $row->top_line ? ' до '.$row->top_line : '' ?>
				</td>
				<td><?= $row->value ?></td>
				<td class="text-right">
					<a href="<?= Url::toRoute(['bonuses/edit', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Редактировать"><i class="glyphicon glyphicon-pencil text-success"></i></a>
					<a href="<?= Url::toRoute(['bonuses/delete', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Удалить" onclick="return confirm('Вы действительно хотите удалить категорию \'<?= $row->name ?>\'?');"><i class="glyphicon glyphicon-remove text-danger"></i></a>
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


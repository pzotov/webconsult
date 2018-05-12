<?php
/**
 * @var $this yii\web\View
 * @var $rows app\models\Stat[] Список записей о количестве звонков
 * @var $pages yii\data\Pagination Постраничная навигация
 * @var $manager app\models\Manager|null Выбранный менеджер
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Данные об обработанных звонках";
if($manager) $this->title .= ' менеджером '.$manager->name;
?>

<div class="text-right">
	<a href="<?= Url::to(['stats/edit', 'id' => 0]) ?>" class="btn btn-primary">Добавить информацию</a>
</div>

	<table class="table table-hover nowrap" width="100%">
		<thead>
		<tr>
			<th>#</th>
			<th>Дата</th>
			<?= $manager ? '' : '<th>Менеджер</th>' ?>
			<th>Количество звонков</th>
			<th width="140"></th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($rows as $i => $row){ ?>
			<tr>
				<td><?= $pages->pageSize*$pages->page + $i + 1 ?></td>
				<td><?= $row->formatted_date ?></td>
				<?= $manager ? '' : '<td>'. $row->manager->name .'</td>' ?>
				<td><?= $row->calls ?></td>
				<td class="text-right">
					<a href="<?= Url::toRoute(['stats/edit', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Редактировать"><i class="glyphicon glyphicon-pencil text-success"></i></a>
					<a href="<?= Url::toRoute(['stats/delete', 'id' => $row->id]) ?>" class="btn btn-link btn-sm" title="Удалить" onclick="return confirm('Вы действительно хотите информацию за <?= $row->date ?>?');"><i class="glyphicon glyphicon-remove text-danger"></i></a>
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


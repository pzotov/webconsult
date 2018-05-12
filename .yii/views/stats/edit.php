<?php
/**
 * @var $this yii\web\View
 * @var $model app\models\Stat
 * @var $managersList array
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = ($model->id ? 'Редактирование' : 'Добавление').' информации о звонке';
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'date')->input('date', ['autofocus' => true]) ?>
<?= $form->field($model, 'manager_id')->dropDownList($managersList, ['class' => 'form-control']) ?>
<?= $form->field($model, 'calls')->input('number') ?>

<div class="form-actions">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	<a href="<?= Url::to(['stats/index']) ?>" class="btn btn-default pull-right">Выйти без сохранения</a>
</div>

<?php ActiveForm::end(); ?>

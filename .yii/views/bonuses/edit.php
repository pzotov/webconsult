<?php
/**
 * @var $this yii\web\View
 * @var $model app\models\BonusCategory
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$this->title = $model->id ? 'Редактирование категории' : 'Добавление категории';
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
<?= $form->field($model, 'bottom_line')->input('number', ['min' => 1]) ?>
<?= $form->field($model, 'value')->input('number', ['step' => '0.01']) ?>
<div class="form-actions">
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
	<a href="<?= Url::to(['bonuses/index']) ?>" class="btn btn-default pull-right">Выйти без сохранения</a>
</div>

<?php ActiveForm::end(); ?>

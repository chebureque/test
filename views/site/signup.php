<?php

/* @var $this yii\web\View */
/* @var $model app\models\SignupForm */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use app\models\UserProfile;

$this->title = 'Signup';
$this->registerJs(<<<JS
$(':input[name*=type]').on('change', function() {
  $('#signup-form').yiiActiveForm('resetForm');
  if (this.value === '1') {
    $('button[name=scenario]').prop('value', 'entrepreneur');
    $('input[name*=itn]').prop('disabled', false);
    $('input[name*=organization]').prop('disabled', true);
  } else if (this.value === '2') {
    $('button[name=scenario]').prop('value', 'legalEntity');
    $('input[name*=itn], input[name*=organization]').prop('disabled', false);
  } else {
    $('button[name=scenario]').prop('value', 'person');
    $('input[name*=itn], input[name*=organization]').prop('disabled', true);
  }
});
JS
);
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <?php $form = ActiveForm::begin(['id' => 'signup-form', 'layout' => 'horizontal']) ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'type')->dropDownList(UserProfile::types()) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'passwordRepeat')->passwordInput() ?>

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patronymic')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'itn')->textInput([
        'maxlength' => true,
        'disabled' => !in_array($model->scenario, ['entrepreneur', 'legalEntity'])
    ]) ?>

    <?= $form->field($model, 'organization')->textInput([
        'maxlength' => true,
        'disabled' => !in_array($model->scenario, ['legalEntity'])
    ]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'scenario', 'value' => 'person']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

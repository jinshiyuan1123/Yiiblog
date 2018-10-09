<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Commentstatus;
use common\models\User;
use common\models\Post;
/* @var $this yii\web\View */
/* @var $model common\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')
        ->dropDownList(Commentstatus::find()->select(['name','id'])->indexBy('id')->orderBy('position')->column(),['prompt'=>'请选择状态'])

    ?>


    <?= $form->field($model, 'userid')
        ->dropDownList(User::find()->select(['username','id'])->orderBy('id')->indexBy('id')->column(),['prompt'=>'请选择评论人'])
    ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post_id')
        ->dropDownList(Post::find()->select(['title','id'])->orderBy('create_time')->indexBy('id')->column(),['prompt'=>'请选择要评论的文章'])
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新增' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

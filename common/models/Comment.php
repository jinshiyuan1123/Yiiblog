<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $content
 * @property integer $status
 * @property integer $create_time
 * @property integer $userid
 * @property string $email
 * @property string $url
 * @property integer $post_id
 * @property integer $remind
 * @property Post $post
 * @property Commentstatus $status0
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content', 'status', 'userid', 'email', 'post_id'], 'required'],
            [['content'], 'string'],
            [['status', 'create_time', 'userid', 'post_id', 'remind'], 'integer'],
            [['email', 'url'], 'string', 'max' => 128],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['status'], 'exist', 'skipOnError' => true, 'targetClass' => Commentstatus::className(), 'targetAttribute' => ['status' => 'id']],
            [['userid'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userid' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => '评论内容',
            'status' => '状态',
            'create_time' => '评论时间',
            'userid' => '评论者',
            'email' => '邮箱',
            'url' => 'Url地址',
            'post_id' => '文章',
	        'remind' => '是否提醒',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Commentstatus::className(), ['id' => 'status']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userid']);
    }

	/**
	 * @desc
	 * @author guomin
	 * @date 2018/7/13  21:50
	 * @return string
	 */
    public function getBeginning(){
    	$tmpStr=strip_tags($this->content);
    	$tmpLen=mb_strlen($tmpStr);
    	return mb_substr($tmpStr,0,20,'utf-8').($tmpLen>20 ? '...' : '');
    }

	/**
	 * @desc
	 * @author guomin
	 * @date 2018/7/14  9:04
	 * @return bool
	 */
    public function approve(){
    	$this->status=2;
    	return ($this->save() ? true : false);
    }

	/**
	 * @desc
	 * @author guomin
	 * @date 2018/7/14  9:07
	 * @return int|string  评论未审核的条数
	 */
    public static function getPengdingCommentCount(){
    	return Comment::find()->where(['status'=>1])->count();
    }

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)){
			if ($insert){
				$this->create_time=time();
			}
			return true;
		}else{
			return false;
		}
	}

	public static function findRecentComments($limits=20){
    	return Comment::find()->where(['status'=>2])->orderBy('create_time desc')->limit($limits)->all();
	}
}

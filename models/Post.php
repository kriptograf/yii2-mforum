<?php

namespace kriptograf\mforum\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $thread_id
 * @property integer $author_id
 * @property integer $editor_id
 * @property string $content
 * @property integer $created
 * @property integer $updated
 *
 * @property Thread $thread
 * @property User $author
 * @property User $editor
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['thread_id', 'author_id', 'editor_id', 'content'], 'required'],
            [['thread_id', 'author_id', 'editor_id', 'created', 'updated'], 'integer'],
            [['content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thread_id' => 'Thread ID',
            'author_id' => 'Author ID',
            'editor_id' => 'Editor ID',
            'content' => 'Message',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getThread()
    {
        return $this->hasOne(Thread::className(), ['id' => 'thread_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEditor()
    {
        return $this->hasOne(User::className(), ['id' => 'editor_id']);
    }

    public function behaviors()
    {
        return [
           'timestampBehavior' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created', 
                'updatedAtAttribute' => 'updated',
            ],
            'fileBehavior' => [
                'class' => \nemmo\attachments\behaviors\FileBehavior::className()
            ],
        ];
    }
}

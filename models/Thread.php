<?php

namespace kriptograf\mforum\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "thread".
 *
 * @property integer $id
 * @property integer $forum_id
 * @property string $subject
 * @property integer $is_locked
 * @property string $view_count
 * @property integer $created
 *
 * @property Post[] $posts
 * @property Forum $forum
 */
class Thread extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'thread';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['forum_id', 'subject', 'is_locked', 'view_count'], 'required'],
            [['forum_id', 'is_locked', 'view_count', 'created'], 'integer'],
            [['subject'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'forum_id' => 'Forum ID',
            'subject' => 'Subject',
            'is_locked' => 'Is Locked',
            'view_count' => 'View Count',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['thread_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getForum()
    {
        return $this->hasOne(Forum::className(), ['id' => 'forum_id']);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
}

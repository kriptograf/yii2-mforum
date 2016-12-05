<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 * Has foreign keys to the tables:
 *
 * - `thread`
 * - `user`
 */
class m161205_204044_create_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'thread_id' => $this->integer(11)->notNull(),
            'author_id' => $this->integer(11)->notNull(),
            'editor_id' => $this->integer(11),
            'content' => $this->text(),
            'created' => $this->integer(11),
            'updated' => $this->integer(11),
        ]);

        // creates index for column `thread_id`
        $this->createIndex(
            'idx-post-thread_id',
            'post',
            'thread_id'
        );

        // add foreign key for table `thread`
        $this->addForeignKey(
            'fk-post-thread_id',
            'post',
            'thread_id',
            'thread',
            'id',
            'CASCADE'
        );

        // creates index for column `author_id`
        $this->createIndex(
            'idx-post-author_id',
            'post',
            'author_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-post-author_id',
            'post',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `thread`
        $this->dropForeignKey(
            'fk-post-thread_id',
            'post'
        );

        // drops index for column `thread_id`
        $this->dropIndex(
            'idx-post-thread_id',
            'post'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-post-author_id',
            'post'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-post-author_id',
            'post'
        );

        $this->dropTable('post');
    }
}

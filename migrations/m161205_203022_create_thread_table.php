<?php

use yii\db\Migration;

/**
 * Handles the creation of table `thread`.
 * Has foreign keys to the tables:
 *
 * - `forum`
 */
class m161205_203022_create_thread_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('thread', [
            'id' => $this->primaryKey(),
            'forum_id' => $this->integer(11)->notNull(),
            'subject' => $this->string(255)->notNull(),
            'is_locked' => $this->integer(1)->defaultValue(0),
            'view_count' => $this->integer(20)->defaultValue(0),
            'created' => $this->integer(11)->defaultValue(0),
        ]);

        // creates index for column `forum_id`
        $this->createIndex(
            'idx-thread-forum_id',
            'thread',
            'forum_id'
        );

        // add foreign key for table `forum`
        $this->addForeignKey(
            'fk-thread-forum_id',
            'thread',
            'forum_id',
            'forum',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `forum`
        $this->dropForeignKey(
            'fk-thread-forum_id',
            'thread'
        );

        // drops index for column `forum_id`
        $this->dropIndex(
            'idx-thread-forum_id',
            'thread'
        );

        $this->dropTable('thread');
    }
}

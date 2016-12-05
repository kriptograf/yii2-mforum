<?php

use yii\db\Migration;

/**
 * Handles the creation of table `forum`.
 */
class m161205_201651_create_forum_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('forum', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(11)->defaultValue(0),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'is_locked' => $this->integer(1)->defaultValue(0),
        ]);

        $this->createIndex(
            'idx-forum-parent_id',
            'forum',
            'parent_id'
            );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('forum');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%uacs_object_codes}}`.
 */
class m231018_070636_create_uacs_object_codes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%uacs_object_codes}}', [
            'object_code' => $this->string(255)->notNull()->append('PRIMARY KEY'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%uacs_object_codes}}');
    }
}

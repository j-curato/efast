<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%office}}`.
 */
class m221223_003427_create_office_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%office}}', [
            'id' => $this->primaryKey(),
            'office_name' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%office}}');
    }
}

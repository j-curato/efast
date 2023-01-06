<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%allotment_type}}`.
 */
class m230106_032355_create_allotment_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%allotment_type}}', [
            'id' => $this->primaryKey(),
            'type' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%allotment_type}}');
    }
}

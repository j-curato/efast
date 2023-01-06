<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%division_program_unit}}`.
 */
class m221223_003501_create_division_program_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%division_program_unit}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%division_program_unit}}');
    }
}

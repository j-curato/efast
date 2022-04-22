<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%alphalist}}`.
 */
class m220420_010348_create_alphalist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%alphalist}}', [
            'id' => $this->primaryKey(),
            'alphalist_number' => $this->string()->notNull()->unique(),
            'check_range' => $this->string(20)->notNull(),
            'province' => $this->string(20)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('alphalist', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%alphalist}}');
    }
}

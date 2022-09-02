<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ppmp_non_cse}}`.
 */
class m220901_014259_create_ppmp_non_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ppmp_non_cse}}', [
            'id' => $this->primaryKey(),
            'ppmp_number' => $this->string()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('ppmp_non_cse', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ppmp_non_cse}}');
    }
}

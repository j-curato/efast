<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acic_in_bank}}`.
 */
class m230530_075046_create_acic_in_bank_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acic_in_bank}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('acic_in_bank', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%acic_in_bank}}');
    }
}

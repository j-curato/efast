<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mode_of_payment}}`.
 */
class m230515_013620_create_mode_of_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mode_of_payments}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%mode_of_payments}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%trial_balance}}`.
 */
class m220331_082435_create_trial_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%trial_balance}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(20)->notNull(),
            'book_id'=>$this->integer()->notNull(),
            'entry_type'=>$this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%trial_balance}}');
    }
}

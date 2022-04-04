<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sub_trial_balance}}`.
 */
class m220331_093923_create_sub_trial_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sub_trial_balance}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(20)->notNull(),
            'book_id'=>$this->integer()->notNull(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sub_trial_balance}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%conso_trial_balance}}`.
 */
class m220401_023250_create_conso_trial_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%conso_trial_balance}}', [
            'id' => $this->primaryKey(),
            'reporting_period' => $this->string(20)->notNull(),
            'entry_type' => $this->string()->notNull(),
            'type' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%conso_trial_balance}}');
    }
}

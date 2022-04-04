<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%conso_sub_trial_balance}}`.
 */
class m220404_004140_create_conso_sub_trial_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%conso_sub_trial_balance}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(20)->notNull(),
            'book_type'=>$this->string(20)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%conso_sub_trial_balance}}');
    }
}

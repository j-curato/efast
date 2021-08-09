<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cdr_advances_balance}}`.
 */
class m210809_034846_create_cdr_advances_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cdr_advances_balance}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(20),
            'book'=>$this->integer(),
            'advance_type'=>$this->string(),
            'amount'=>$this->decimal(10,2)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cdr_advances_balance}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%advances_balances}}`.
 */
class m210808_083849_create_advances_balances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%advances_balances}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(),
            'balance'=>$this->decimal(19,2),
            'province'=>$this->string(50)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%advances_balances}}');
    }
}

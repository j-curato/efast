<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%liquidation_balances}}`.
 */
class m210808_131909_create_liquidation_balances_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%liquidation_balances}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(50),
            'province'=>$this->string(50),
            'balance'=>$this->decimal(20,2)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%liquidation_balances}}');
    }
}

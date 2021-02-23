<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_flow}}`.
 */
class m210223_023742_create_cash_flow_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_flow}}', [
            'id' => $this->primaryKey(),
            'major_cashflow' => $this->string(255),
            'sub_cashflow1' => $this->string(255),
            'sub_cashflow2' => $this->string(255),
            'specific_cashflow' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cash_flow}}');
    }
}

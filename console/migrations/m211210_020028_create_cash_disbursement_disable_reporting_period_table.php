<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cash_disbursement_disable_reporting_period}}`.
 */
class m211210_020028_create_cash_disbursement_disable_reporting_period_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%cash_disbursement_disable_reporting_period}}', [
            'id' => $this->primaryKey(),
            'reporting_period'=>$this->string(25),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cash_disbursement_disable_reporting_period}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ro_liquidation_report}}`.
 */
class m220603_013255_create_ro_liquidation_report_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ro_liquidation_report}}', [
            'id' => $this->primaryKey(),
            'liquidation_report_number' => $this->string()->unique()->notNull(),
            'date' => $this->date(),
            'reporting_period' => $this->string(20),
            'updated_at' => $this->timestamp()->defaultValue(null)->append('ON UPDATE CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')

        ]);
        $this->alterColumn('ro_liquidation_report', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%ro_liquidation_report}}');
    }
}

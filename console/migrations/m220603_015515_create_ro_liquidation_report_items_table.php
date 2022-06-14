<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ro_liquidation_report_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%ro_liquidation_report}}`
 */
class m220603_015515_create_ro_liquidation_report_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ro_liquidation_report_items}}', [
            'id' => $this->primaryKey(),
            'fk_ro_liquidation_report_id' => $this->bigInteger(),
            'fk_cash_disbursement_id' => $this->bigInteger(),
            'amount' => $this->decimal(15, 2)->defaultValue(0),
            'object_code' => $this->string(),
            'reporting_period' => $this->string(20),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_ro_liquidation_report_id`
        $this->createIndex(
            '{{%idx-ro_liquidation_report_items-fk_ro_liquidation_report_id}}',
            '{{%ro_liquidation_report_items}}',
            'fk_ro_liquidation_report_id'
        );

        // add foreign key for table `{{%ro_liquidation_report}}`
        $this->addForeignKey(
            '{{%fk-ro_liquidation_report_items-fk_ro_liquidation_report_id}}',
            '{{%ro_liquidation_report_items}}',
            'fk_ro_liquidation_report_id',
            '{{%ro_liquidation_report}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%ro_liquidation_report}}`
        $this->dropForeignKey(
            '{{%fk-ro_liquidation_report_items-fk_ro_liquidation_report_id}}',
            '{{%ro_liquidation_report_items}}'
        );

        // drops index for column `fk_ro_liquidation_report_id`
        $this->dropIndex(
            '{{%idx-ro_liquidation_report_items-fk_ro_liquidation_report_id}}',
            '{{%ro_liquidation_report_items}}'
        );

        $this->dropTable('{{%ro_liquidation_report_items}}');
    }
}

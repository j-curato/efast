<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_lgu_liquidation_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_lgu_liquidation}}`
 */
class m231124_012642_create_tbl_fmi_lgu_liquidation_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_lgu_liquidation_items}}', [
            'id' => $this->primaryKey(),
            'fk_fmi_lgu_liquidation_id' => $this->bigInteger(),
            'reporting_period' => $this->string(),
            'date' => $this->date()->notNull(),
            'check_number' => $this->string()->notNull(),
            'payee' => $this->string()->notNull(),
            'particular' => $this->text(),
            'grant_amount' => $this->decimal(15, 2),
            'equity_amount' => $this->decimal(15, 2),
            'other_fund_amount' => $this->decimal(15, 2),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_fmi_lgu_liquidation_items', 'id', $this->bigInteger());
        // creates index for column `fk_fmi_lgu_liquidation_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_lgu_liquidation_items-fk_fmi_lgu_liquidation_id}}',
            '{{%tbl_fmi_lgu_liquidation_items}}',
            'fk_fmi_lgu_liquidation_id'
        );

        // add foreign key for table `{{%tbl_fmi_lgu_liquidation}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_lgu_liquidation_items-fk_fmi_lgu_liquidation_id}}',
            '{{%tbl_fmi_lgu_liquidation_items}}',
            'fk_fmi_lgu_liquidation_id',
            '{{%tbl_fmi_lgu_liquidations}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tbl_fmi_lgu_liquidation}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_lgu_liquidation_items-fk_fmi_lgu_liquidation_id}}',
            '{{%tbl_fmi_lgu_liquidation_items}}'
        );

        // drops index for column `fk_fmi_lgu_liquidation_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_lgu_liquidation_items-fk_fmi_lgu_liquidation_id}}',
            '{{%tbl_fmi_lgu_liquidation_items}}'
        );

        $this->dropTable('{{%tbl_fmi_lgu_liquidation_items}}');
    }
}

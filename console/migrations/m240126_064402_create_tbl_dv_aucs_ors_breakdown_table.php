<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_dv_aucs_ors_breakdown}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%dv_aucs}}`
 * - `{{%process_ors_entries}}`
 */
class m240126_064402_create_tbl_dv_aucs_ors_breakdown_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_dv_aucs_ors_breakdown}}', [
            'id' => $this->primaryKey(),
            'fk_dv_aucs_id' => $this->integer()->notNull(),
            'fk_process_ors_entry_id' => $this->integer()->notNull(),
            'amount_disbursed' => $this->decimal(10, 2)->defaultValue(0),
            'vat_nonvat' => $this->decimal(10, 2)->defaultValue(0),
            'ewt_goods_services' => $this->decimal(10, 2)->defaultValue(0),
            'compensation' => $this->decimal(10, 2)->defaultValue(0),
            'other_trust_liabilities' => $this->decimal(10, 2)->defaultValue(0),
            'total_withheld' => $this->decimal(10, 2)->defaultValue(0),
            'process_ors_id' => $this->decimal(10, 2)->defaultValue(0),
            'liquidation_damage' => $this->decimal(10, 2)->defaultValue(0),
            'tax_portion_of_post' => $this->decimal(10, 2)->defaultValue(0),
            'is_deleted' => $this->boolean()->defaultValue(0),

        ]);

        // creates index for column `fk_dv_aucs_id`
        $this->createIndex(
            '{{%idx-tbl_dv_aucs_ors_breakdown-fk_dv_aucs_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}',
            'fk_dv_aucs_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        $this->addForeignKey(
            '{{%fk-tbl_dv_aucs_ors_breakdown-fk_dv_aucs_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}',
            'fk_dv_aucs_id',
            '{{%dv_aucs}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_process_ors_entry_id`
        $this->createIndex(
            '{{%idx-tbl_dv_aucs_ors_breakdown-fk_process_ors_entry_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}',
            'fk_process_ors_entry_id'
        );

        // add foreign key for table `{{%process_ors_entries}}`
        $this->addForeignKey(
            '{{%fk-tbl_dv_aucs_ors_breakdown-fk_process_ors_entry_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}',
            'fk_process_ors_entry_id',
            '{{%process_ors_entries}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-tbl_dv_aucs_ors_breakdown-fk_dv_aucs_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}'
        );

        // drops index for column `fk_dv_aucs_id`
        $this->dropIndex(
            '{{%idx-tbl_dv_aucs_ors_breakdown-fk_dv_aucs_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}'
        );

        // drops foreign key for table `{{%process_ors_entries}}`
        $this->dropForeignKey(
            '{{%fk-tbl_dv_aucs_ors_breakdown-fk_process_ors_entry_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}'
        );

        // drops index for column `fk_process_ors_entry_id`
        $this->dropIndex(
            '{{%idx-tbl_dv_aucs_ors_breakdown-fk_process_ors_entry_id}}',
            '{{%tbl_dv_aucs_ors_breakdown}}'
        );

        $this->dropTable('{{%tbl_dv_aucs_ors_breakdown}}');
    }
}

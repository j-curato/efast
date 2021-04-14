<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dv_aucs_entries}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%dv_aucs}}`
 * - `{{%raouds}}`
 */
class m210406_020706_create_dv_aucs_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dv_aucs_entries}}', [
            'id' => $this->primaryKey(),
            'dv_aucs_id' => $this->integer()->notNull(),
            'raoud_id' => $this->integer()->notNull(),
             'amount_disbursed'=> $this->float(),
             'vat_nonvat'=> $this->float(),
             'ewt_goods_services'=> $this->float(),
             'compensation'=> $this->float(),
             'other_trust_liabilities'=> $this->float(),
             'total_withheld'=> $this->float(),
 
        ]);

        // creates index for column `dv_aucs_id`
        $this->createIndex(
            '{{%idx-dv_aucs_entries-dv_aucs_id}}',
            '{{%dv_aucs_entries}}',
            'dv_aucs_id'
        );

        // add foreign key for table `{{%dv_aucs}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs_entries-dv_aucs_id}}',
            '{{%dv_aucs_entries}}',
            'dv_aucs_id',
            '{{%dv_aucs}}',
            'id',
            'CASCADE'
        );

        // creates index for column `raoud_id`
        $this->createIndex(
            '{{%idx-dv_aucs_entries-raoud_id}}',
            '{{%dv_aucs_entries}}',
            'raoud_id'
        );

        // add foreign key for table `{{%raouds}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs_entries-raoud_id}}',
            '{{%dv_aucs_entries}}',
            'raoud_id',
            '{{%raouds}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%dv_aucs}}`
        $this->dropForeignKey(
            '{{%fk-dv_aucs_entries-dv_aucs_id}}',
            '{{%dv_aucs_entries}}'
        );

        // drops index for column `dv_aucs_id`
        $this->dropIndex(
            '{{%idx-dv_aucs_entries-dv_aucs_id}}',
            '{{%dv_aucs_entries}}'
        );

        // drops foreign key for table `{{%raouds}}`
        $this->dropForeignKey(
            '{{%fk-dv_aucs_entries-raoud_id}}',
            '{{%dv_aucs_entries}}'
        );

        // drops index for column `raoud_id`
        $this->dropIndex(
            '{{%idx-dv_aucs_entries-raoud_id}}',
            '{{%dv_aucs_entries}}'
        );

        $this->dropTable('{{%dv_aucs_entries}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_rfq_mfos}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%pr_rfq}}`
 * - `{{%mfo_pap_code}}`
 */
class m240118_014954_create_tbl_rfq_mfos_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_rfq_mfos}}', [
            'id' => $this->primaryKey(),
            'fk_rfq_id' => $this->bigInteger(),
            'fk_mfo_pap_code_id' => $this->integer(),
            'is_deleted' => $this->boolean()->defaultValue(false)
        ]);
        $this->alterColumn('{{%tbl_rfq_mfos}}', 'id', $this->bigInteger());
        // creates index for column `fk_rfq_id`
        $this->createIndex(
            '{{%idx-tbl_rfq_mfos-fk_rfq_id}}',
            '{{%tbl_rfq_mfos}}',
            'fk_rfq_id'
        );

        // add foreign key for table `{{%pr_rfq}}`
        $this->addForeignKey(
            '{{%fk-tbl_rfq_mfos-fk_rfq_id}}',
            '{{%tbl_rfq_mfos}}',
            'fk_rfq_id',
            '{{%pr_rfq}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_mfo_pap_code_id`
        $this->createIndex(
            '{{%idx-tbl_rfq_mfos-fk_mfo_pap_code_id}}',
            '{{%tbl_rfq_mfos}}',
            'fk_mfo_pap_code_id'
        );

        // add foreign key for table `{{%mfo_pap_code}}`
        $this->addForeignKey(
            '{{%fk-tbl_rfq_mfos-fk_mfo_pap_code_id}}',
            '{{%tbl_rfq_mfos}}',
            'fk_mfo_pap_code_id',
            '{{%mfo_pap_code}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%pr_rfq}}`
        $this->dropForeignKey(
            '{{%fk-tbl_rfq_mfos-fk_rfq_id}}',
            '{{%tbl_rfq_mfos}}'
        );

        // drops index for column `fk_rfq_id`
        $this->dropIndex(
            '{{%idx-tbl_rfq_mfos-fk_rfq_id}}',
            '{{%tbl_rfq_mfos}}'
        );

        // drops foreign key for table `{{%mfo_pap_code}}`
        $this->dropForeignKey(
            '{{%fk-tbl_rfq_mfos-fk_mfo_pap_code_id}}',
            '{{%tbl_rfq_mfos}}'
        );

        // drops index for column `fk_mfo_pap_code_id`
        $this->dropIndex(
            '{{%idx-tbl_rfq_mfos-fk_mfo_pap_code_id}}',
            '{{%tbl_rfq_mfos}}'
        );

        $this->dropTable('{{%tbl_rfq_mfos}}');
    }
}

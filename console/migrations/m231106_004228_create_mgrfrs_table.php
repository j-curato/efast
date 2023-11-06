<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mgrfrs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bank_branch_details}}`
 * - `{{%municipalities}}`
 * - `{{%barangays}}`
 */
class m231106_004228_create_mgrfrs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mgrfrs}}', [
            'id' => $this->primaryKey(),
            'serial_number'=>$this->string()->unique()->notNull(),
            'fk_bank_branch_detail_id' => $this->integer(),
            'fk_municipality_id' => $this->integer(),
            'fk_barangay_id' => $this->integer(),
            'purok'=>$this->string(),
            'created_at'=>$this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_bank_branch_detail_id`
        $this->createIndex(
            '{{%idx-mgrfrs-fk_bank_branch_detail_id}}',
            '{{%mgrfrs}}',
            'fk_bank_branch_detail_id'
        );

        // add foreign key for table `{{%bank_branch_details}}`
        $this->addForeignKey(
            '{{%fk-mgrfrs-fk_bank_branch_detail_id}}',
            '{{%mgrfrs}}',
            'fk_bank_branch_detail_id',
            '{{%bank_branch_details}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_municipality_id`
        $this->createIndex(
            '{{%idx-mgrfrs-fk_municipality_id}}',
            '{{%mgrfrs}}',
            'fk_municipality_id'
        );

        // add foreign key for table `{{%municipalities}}`
        $this->addForeignKey(
            '{{%fk-mgrfrs-fk_municipality_id}}',
            '{{%mgrfrs}}',
            'fk_municipality_id',
            '{{%municipalities}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_barangay_id`
        $this->createIndex(
            '{{%idx-mgrfrs-fk_barangay_id}}',
            '{{%mgrfrs}}',
            'fk_barangay_id'
        );

        // add foreign key for table `{{%barangays}}`
        $this->addForeignKey(
            '{{%fk-mgrfrs-fk_barangay_id}}',
            '{{%mgrfrs}}',
            'fk_barangay_id',
            '{{%barangays}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bank_branch_details}}`
        $this->dropForeignKey(
            '{{%fk-mgrfrs-fk_bank_branch_detail_id}}',
            '{{%mgrfrs}}'
        );

        // drops index for column `fk_bank_branch_detail_id`
        $this->dropIndex(
            '{{%idx-mgrfrs-fk_bank_branch_detail_id}}',
            '{{%mgrfrs}}'
        );

        // drops foreign key for table `{{%municipalities}}`
        $this->dropForeignKey(
            '{{%fk-mgrfrs-fk_municipality_id}}',
            '{{%mgrfrs}}'
        );

        // drops index for column `fk_municipality_id`
        $this->dropIndex(
            '{{%idx-mgrfrs-fk_municipality_id}}',
            '{{%mgrfrs}}'
        );

        // drops foreign key for table `{{%barangays}}`
        $this->dropForeignKey(
            '{{%fk-mgrfrs-fk_barangay_id}}',
            '{{%mgrfrs}}'
        );

        // drops index for column `fk_barangay_id`
        $this->dropIndex(
            '{{%idx-mgrfrs-fk_barangay_id}}',
            '{{%mgrfrs}}'
        );

        $this->dropTable('{{%mgrfrs}}');
    }
}

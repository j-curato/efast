<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%mgrfrs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bank_branch_details}}`
 * - `{{%municipalities}}`
 * - `{{%barangays}}`
 * - `{{%office}}`
 */
class m231107_010206_create_rapid_mg_mgrfrs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%mgrfrs}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'organization_name' => $this->text(),
            'fk_bank_branch_detail_id' => $this->integer(),
            'fk_province_id' => $this->integer(),
            'fk_municipality_id' => $this->integer(),
            'fk_barangay_id' => $this->integer(),
            'fk_office_id' => $this->integer(),
            'purok' => $this->string(),
            'authorized_personnel' => $this->string(),
            'contact_number' => $this->string(),
            'saving_account_number' => $this->string(),
            'email_address' => $this->string(),
            'investment_type' => $this->text(),
            'investment_description' => $this->text(),
            'project_consultant' => $this->text(),
            'project_objective' => $this->text(),
            'project_beneficiary' => $this->text(),
            'matching_grant_amount' => $this->decimal(15, 2)->notNull(),
            'equity_amount' => $this->decimal(15, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('mgrfrs', 'id', $this->bigInteger());

        // create index for column fk_province_id
        $this->createIndex('idx-mgrfrs-fk_province_id', 'mgrfrs', 'fk_province_id');
        // create foreign key for column fk_province_id
        $this->addForeignKey('idx-mgrfrs-fk_province_id', 'mgrfrs', 'fk_province_id', 'provinces', 'id', 'RESTRICT');
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

        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-mgrfrs-fk_office_id}}',
            '{{%mgrfrs}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-mgrfrs-fk_office_id}}',
            '{{%mgrfrs}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for column fk_province_id
        $this->dropForeignKey('idx-mgrfrs-fk_province_id', 'mgrfrs');
        // drop index for column fk_province_id
        $this->dropIndex('idx-mgrfrs-fk_province_id', 'mgrfrs');
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

        // drops foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-mgrfrs-fk_office_id}}',
            '{{%mgrfrs}}'
        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-mgrfrs-fk_office_id}}',
            '{{%mgrfrs}}'
        );

        $this->dropTable('{{%mgrfrs}}');
    }
}

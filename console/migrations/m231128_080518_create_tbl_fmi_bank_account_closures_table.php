<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_bank_account_closures}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_subprojects}}`
 */
class m231128_080518_create_tbl_fmi_bank_account_closures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_bank_account_closures}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->unique()->notNull(),
            'fk_fmi_subproject_id' => $this->bigInteger()->notNull(),
            'fk_office_id' => $this->integer()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'date' => $this->date()->notNull(),
            'bank_certification_link' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_fmi_bank_account_closures', 'id', $this->bigInteger());
        // creates index for column `fk_fmi_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_bank_account_closures-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_account_closures}}',
            'fk_fmi_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_bank_account_closures-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_account_closures}}',
            'fk_fmi_subproject_id',
            '{{%tbl_fmi_subprojects}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_bank_account_closures-fk_office_id}}',
            '{{%tbl_fmi_bank_account_closures}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_bank_account_closures-fk_office_id}}',
            '{{%tbl_fmi_bank_account_closures}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_bank_account_closures-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_account_closures}}'
        );

        // drops index for column `fk_fmi_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_bank_account_closures-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_account_closures}}'
        );

        // drop foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_bank_account_closures-fk_office_id}}',
            '{{%tbl_fmi_bank_account_closures}}'

        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_bank_account_closures-fk_office_id}}',
            '{{%tbl_fmi_bank_account_closures}}'
        );

        $this->dropTable('{{%tbl_fmi_bank_account_closures}}');
    }
}

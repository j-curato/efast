<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_bank_deposits}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_bank_deposit_types}}`
 * - `{{%tbl_fmi_subprojects}}`
 */
class m231123_032816_create_tbl_fmi_bank_deposits_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_bank_deposits}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'deposit_date' => $this->date()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'fk_fmi_bank_deposit_type_id' => $this->integer()->notNull(),
            'fk_fmi_subproject_id' => $this->bigInteger()->notNull(),
            'particular' => $this->text(),
            'deposit_amount' => $this->decimal(15, 2),
            'fk_office_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_fmi_bank_deposits', 'id', $this->bigInteger());
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_bank_deposits-fk_office_id}}',
            '{{%tbl_fmi_bank_deposits}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_bank_deposits-fk_office_id}}',
            '{{%tbl_fmi_bank_deposits}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_fmi_bank_deposit_type_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_bank_deposits-fk_fmi_bank_deposit_type_id}}',
            '{{%tbl_fmi_bank_deposits}}',
            'fk_fmi_bank_deposit_type_id'
        );

        // add foreign key for table `{{%tbl_fmi_bank_deposit_types}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_bank_deposits-fk_fmi_bank_deposit_type_id}}',
            '{{%tbl_fmi_bank_deposits}}',
            'fk_fmi_bank_deposit_type_id',
            '{{%tbl_fmi_bank_deposit_types}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_fmi_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_bank_deposits-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_deposits}}',
            'fk_fmi_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_bank_deposits-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_deposits}}',
            'fk_fmi_subproject_id',
            '{{%tbl_fmi_subprojects}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tbl_fmi_bank_deposit_types}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_bank_deposits-fk_fmi_bank_deposit_type_id}}',
            '{{%tbl_fmi_bank_deposits}}'
        );

        // drops index for column `fk_fmi_bank_deposit_type_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_bank_deposits-fk_fmi_bank_deposit_type_id}}',
            '{{%tbl_fmi_bank_deposits}}'
        );

        // drops foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_bank_deposits-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_deposits}}'
        );

        // drops index for column `fk_fmi_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_bank_deposits-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_bank_deposits}}'
        );
        // drop foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_bank_deposits-fk_office_id}}',
            '{{%tbl_fmi_bank_deposits}}'
        );
        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_bank_deposits-fk_office_id}}',
            '{{%tbl_fmi_bank_deposits}}'
        );

        $this->dropTable('{{%tbl_fmi_bank_deposits}}');
    }
}

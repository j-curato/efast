<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%due_diligence_reports}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mgrfrs}}`
 * - `{{%employee}}`
 * - `{{%employee}}`
 */
class m231108_030009_create_rapid_mg_due_diligence_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%due_diligence_reports}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->unique()->notNull(),
            'supplier_name' => $this->string()->notNull(),
            'supplier_address' => $this->text()->notNull(),
            'contact_person' => $this->string()->notNull(),
            'contact_number' => $this->string()->notNull(),
            'supplier_is_registered' => $this->boolean()->notNull(),
            'supplier_has_business_permit' => $this->boolean()->notNull(),
            'supplier_is_bir_registered' => $this->boolean()->notNull(),
            'supplier_has_officer_connection' => $this->boolean()->notNull(),
            'supplier_is_financial_capable' => $this->boolean()->notNull(),
            'supplier_is_authorized_dealer' => $this->boolean()->notNull(),
            'supplier_has_quality_material' => $this->boolean()->notNull(),
            'supplier_can_comply_specs' => $this->boolean()->notNull(),
            'supplier_has_legal_issues' => $this->boolean()->notNull(),
            'supplier_nursery' => $this->text(),
            'comments' => $this->text()->notNull(),
            'fk_mgrfr_id' => $this->bigInteger(),
            'fk_conducted_by' => $this->bigInteger(),
            'fk_noted_by' => $this->bigInteger(),
            'fk_office_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('due_diligence_reports', 'id', $this->bigInteger());
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-due_diligence_reports-fk_office_id}}',
            '{{%due_diligence_reports}}',
            'fk_office_id'
        );
        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-due_diligence_reports-fk_office_id}}',
            '{{%due_diligence_reports}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_mgrfr_id`
        $this->createIndex(
            '{{%idx-due_diligence_reports-fk_mgrfr_id}}',
            '{{%due_diligence_reports}}',
            'fk_mgrfr_id'
        );

        // add foreign key for table `{{%mgrfrs}}`
        $this->addForeignKey(
            '{{%fk-due_diligence_reports-fk_mgrfr_id}}',
            '{{%due_diligence_reports}}',
            'fk_mgrfr_id',
            '{{%mgrfrs}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_conducted_by`
        $this->createIndex(
            '{{%idx-due_diligence_reports-fk_conducted_by}}',
            '{{%due_diligence_reports}}',
            'fk_conducted_by'
        );

        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-due_diligence_reports-fk_conducted_by}}',
            '{{%due_diligence_reports}}',
            'fk_conducted_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );

        // creates index for column `fk_noted_by`
        $this->createIndex(
            '{{%idx-due_diligence_reports-fk_noted_by}}',
            '{{%due_diligence_reports}}',
            'fk_noted_by'
        );

        // add foreign key for table `{{%employee}}`
        $this->addForeignKey(
            '{{%fk-due_diligence_reports-fk_noted_by}}',
            '{{%due_diligence_reports}}',
            'fk_noted_by',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drop foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-due_diligence_reports-fk_office_id}}',
            '{{%due_diligence_reports}}',

        );
        // drop index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-due_diligence_reports-fk_office_id}}',
            '{{%due_diligence_reports}}',
        );

        // drops foreign key for table `{{%mgrfrs}}`
        $this->dropForeignKey(
            '{{%fk-due_diligence_reports-fk_mgrfr_id}}',
            '{{%due_diligence_reports}}'
        );

        // drops index for column `fk_mgrfr_id`
        $this->dropIndex(
            '{{%idx-due_diligence_reports-fk_mgrfr_id}}',
            '{{%due_diligence_reports}}'
        );

        // drops foreign key for table `{{%employee}}`
        $this->dropForeignKey(
            '{{%fk-due_diligence_reports-fk_conducted_by}}',
            '{{%due_diligence_reports}}'
        );

        // drops index for column `fk_conducted_by`
        $this->dropIndex(
            '{{%idx-due_diligence_reports-fk_conducted_by}}',
            '{{%due_diligence_reports}}'
        );

        // drops foreign key for table `{{%employee}}`
        $this->dropForeignKey(
            '{{%fk-due_diligence_reports-fk_noted_by}}',
            '{{%due_diligence_reports}}'
        );

        // drops index for column `fk_noted_by`
        $this->dropIndex(
            '{{%idx-due_diligence_reports-fk_noted_by}}',
            '{{%due_diligence_reports}}'
        );

        $this->dropTable('{{%due_diligence_reports}}');
    }
}

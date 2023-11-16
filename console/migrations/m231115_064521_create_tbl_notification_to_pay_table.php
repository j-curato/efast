<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_notification_to_pay}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%due_diligence_reports}}`
 */
class m231115_064521_create_tbl_notification_to_pay_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_notification_to_pay}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'fk_due_diligence_report_id' => $this->bigInteger(),
            'matching_grant_amount' => $this->decimal(15, 2),
            'equity_amount' => $this->decimal(15, 2),
            'other_amount' => $this->decimal(15, 2),
            'fk_office' => $this->integer(),
            'fk_coordinator' => $this->bigInteger(),
            'fk_provincial_director' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        $this->alterColumn('tbl_notification_to_pay', 'id', $this->bigInteger());
        // creates index for column `fk_due_diligence_report_id`
        $this->createIndex(
            '{{%idx-tbl_notification_to_pay-fk_due_diligence_report_id}}',
            '{{%tbl_notification_to_pay}}',
            'fk_due_diligence_report_id'
        );
        // add foreign key for table `{{%due_diligence_reports}}`
        $this->addForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_due_diligence_report_id}}',
            '{{%tbl_notification_to_pay}}',
            'fk_due_diligence_report_id',
            '{{%due_diligence_reports}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_office`
        $this->createIndex(
            '{{%idx-tbl_notification_to_pay-fk_office}}',
            '{{%tbl_notification_to_pay}}',
            'fk_office'
        );
        // add foreign key for table `{{%due_diligence_reports}}`
        $this->addForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_office}}',
            '{{%tbl_notification_to_pay}}',
            'fk_office',
            '{{%office}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_coordinator`
        $this->createIndex(
            '{{%idx-tbl_notification_to_pay-fk_coordinator}}',
            '{{%tbl_notification_to_pay}}',
            'fk_coordinator'
        );
        // add foreign key for table `{{%due_diligence_reports}}`
        $this->addForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_coordinator}}',
            '{{%tbl_notification_to_pay}}',
            'fk_coordinator',
            '{{%employee}}',
            'employee_id',
            'RESTRICT'
        );
        // creates index for column `fk_provincial_director`
        $this->createIndex(
            '{{%idx-tbl_notification_to_pay-fk_provincial_director}}',
            '{{%tbl_notification_to_pay}}',
            'fk_provincial_director'
        );
        // add foreign key for table `{{%due_diligence_reports}}`
        $this->addForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_provincial_director}}',
            '{{%tbl_notification_to_pay}}',
            'fk_provincial_director',
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
        // drops foreign key for table `{{%due_diligence_reports}}`
        $this->dropForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_due_diligence_report_id}}',
            '{{%tbl_notification_to_pay}}'
        );

        // drops index for column `fk_due_diligence_report_id`
        $this->dropIndex(
            '{{%idx-tbl_notification_to_pay-fk_due_diligence_report_id}}',
            '{{%tbl_notification_to_pay}}'
        );

        //  foreign key for table `{{%due_diligence_reports}}`
        $this->dropForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_office}}',
            '{{%tbl_notification_to_pay}}',
        );
        // drop index for column `fk_office`
        $this->dropIndex(
            '{{%idx-tbl_notification_to_pay-fk_office}}',
            '{{%tbl_notification_to_pay}}'
        );
        //  foreign key for table `{{%due_diligence_reports}}`
        $this->dropForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_coordinator}}',
            '{{%tbl_notification_to_pay}}',
        );
        // drop index for column `fk_coordinator`
        $this->dropIndex(
            '{{%idx-tbl_notification_to_pay-fk_coordinator}}',
            '{{%tbl_notification_to_pay}}',
        );

        //  foreign key for table `{{%due_diligence_reports}}`
        $this->dropForeignKey(
            '{{%fk-tbl_notification_to_pay-fk_provincial_director}}',
            '{{%tbl_notification_to_pay}}'
        );
        // drop index for column `fk_provincial_director`
        $this->dropIndex(
            '{{%idx-tbl_notification_to_pay-fk_provincial_director}}',
            '{{%tbl_notification_to_pay}}'
        );
        $this->dropTable('{{%tbl_notification_to_pay}}');
    }
}

<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_project_completions}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%office}}`
 * - `{{%tbl_fmi_subprojects}}`
 */
class m231129_012431_create_tbl_fmi_project_completions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_project_completions}}', [
            'id' => $this->primaryKey(),
            'fk_office_id' => $this->integer(),
            'fk_fmi_subproject_id' => $this->bigInteger(),
            'serial_number' => $this->string()->unique()->notNull(),
            'completion_date' => $this->date()->notNull(),
            'turnover_date' => $this->date()->notNull(),
            'spcr_link' => $this->text(),
            'certificate_of_project_link' => $this->text(),
            'certificate_of_turnover_link' => $this->text(),
            'reporting_period' => $this->string()->notNull(),
            'date' => $this->date(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_fmi_project_completions', 'id', $this->bigInteger());
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_project_completions-fk_office_id}}',
            '{{%tbl_fmi_project_completions}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_project_completions-fk_office_id}}',
            '{{%tbl_fmi_project_completions}}',
            'fk_office_id',
            '{{%office}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_fmi_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_project_completions-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_project_completions}}',
            'fk_fmi_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_project_completions-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_project_completions}}',
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
        // drops foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_project_completions-fk_office_id}}',
            '{{%tbl_fmi_project_completions}}'
        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_project_completions-fk_office_id}}',
            '{{%tbl_fmi_project_completions}}'
        );

        // drops foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_project_completions-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_project_completions}}'
        );

        // drops index for column `fk_fmi_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_project_completions-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_project_completions}}'
        );

        $this->dropTable('{{%tbl_fmi_project_completions}}');
    }
}

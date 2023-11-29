<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_actual_date_of_starts}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_subprojects}}`
 */
class m231128_012931_create_tbl_fmi_actual_date_of_starts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_actual_date_of_starts}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'fk_tbl_fmi_subproject_id' => $this->bigInteger()->notNull(),
            'fk_office_id' => $this->integer(),
            'actual_date_of_start' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('tbl_fmi_actual_date_of_starts', 'id', $this->bigInteger());
        // creates index for column `fk_tbl_fmi_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_actual_date_of_starts-fk_tbl_fmi_subproject_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}',
            'fk_tbl_fmi_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_actual_date_of_starts-fk_tbl_fmi_subproject_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}',
            'fk_tbl_fmi_subproject_id',
            '{{%tbl_fmi_subprojects}}',
            'id',
            'RESTRICT'
        );
        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_actual_date_of_starts-fk_office_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_actual_date_of_starts-fk_office_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}',
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
            '{{%fk-tbl_fmi_actual_date_of_starts-fk_tbl_fmi_subproject_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}'
        );

        // drops index for column `fk_tbl_fmi_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_actual_date_of_starts-fk_tbl_fmi_subproject_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}'
        );
        // drop foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_actual_date_of_starts-fk_office_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}'

        );
        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_actual_date_of_starts-fk_office_id}}',
            '{{%tbl_fmi_actual_date_of_starts}}'
        );


        $this->dropTable('{{%tbl_fmi_actual_date_of_starts}}');
    }
}

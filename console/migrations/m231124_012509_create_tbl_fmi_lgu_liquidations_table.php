<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_lgu_liquidations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_subprojects}}`
 * - `{{%office}}`
 */
class m231124_012509_create_tbl_fmi_lgu_liquidations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_lgu_liquidations}}', [
            'id' => $this->primaryKey(),
            'fk_fmi_subproject_id' => $this->bigInteger(),
            'serial_number' => $this->string()->unique(),
            'fk_office_id' => $this->integer()->notNull(),
            'reporting_period' => $this->string()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('tbl_fmi_lgu_liquidations','id',$this->bigInteger());
        // creates index for column `fk_fmi_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_lgu_liquidations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_lgu_liquidations}}',
            'fk_fmi_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_lgu_liquidations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_lgu_liquidations}}',
            'fk_fmi_subproject_id',
            '{{%tbl_fmi_subprojects}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `fk_office_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_lgu_liquidations-fk_office_id}}',
            '{{%tbl_fmi_lgu_liquidations}}',
            'fk_office_id'
        );

        // add foreign key for table `{{%office}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_lgu_liquidations-fk_office_id}}',
            '{{%tbl_fmi_lgu_liquidations}}',
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
            '{{%fk-tbl_fmi_lgu_liquidations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_lgu_liquidations}}'
        );

        // drops index for column `fk_fmi_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_lgu_liquidations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_lgu_liquidations}}'
        );

        // drops foreign key for table `{{%office}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_lgu_liquidations-fk_office_id}}',
            '{{%tbl_fmi_lgu_liquidations}}'
        );

        // drops index for column `fk_office_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_lgu_liquidations-fk_office_id}}',
            '{{%tbl_fmi_lgu_liquidations}}'
        );

        $this->dropTable('{{%tbl_fmi_lgu_liquidations}}');
    }
}

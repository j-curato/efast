<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tbl_fmi_subproject_organizations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tbl_fmi_subprojects}}`
 */
class m231122_014238_create_tbl_fmi_subproject_organizations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tbl_fmi_subproject_organizations}}', [
            'id' => $this->primaryKey(),
            'fk_fmi_subproject_id' => $this->bigInteger(),
            'organization_name' => $this->text(),
            'is_deleted' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('{{%tbl_fmi_subproject_organizations}}', 'id', $this->bigInteger());
        // creates index for column `fk_fmi_subproject_id`
        $this->createIndex(
            '{{%idx-tbl_fmi_subproject_organizations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_subproject_organizations}}',
            'fk_fmi_subproject_id'
        );

        // add foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->addForeignKey(
            '{{%fk-tbl_fmi_subproject_organizations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_subproject_organizations}}',
            'fk_fmi_subproject_id',
            '{{%tbl_fmi_subprojects}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tbl_fmi_subprojects}}`
        $this->dropForeignKey(
            '{{%fk-tbl_fmi_subproject_organizations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_subproject_organizations}}'
        );

        // drops index for column `fk_fmi_subproject_id`
        $this->dropIndex(
            '{{%idx-tbl_fmi_subproject_organizations-fk_fmi_subproject_id}}',
            '{{%tbl_fmi_subproject_organizations}}'
        );

        $this->dropTable('{{%tbl_fmi_subproject_organizations}}');
    }
}

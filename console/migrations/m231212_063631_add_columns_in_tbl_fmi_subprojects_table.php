<?php

use yii\db\Migration;

/**
 * Class m231212_063631_add_columns_in_tbl_fmi_subprojects_table
 */
class m231212_063631_add_columns_in_tbl_fmi_subprojects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tbl_fmi_subprojects', 'project_name', $this->text());
        $this->addColumn('tbl_fmi_subprojects', 'fk_bank_branch_detail_id', $this->integer());

        $this->createIndex('idx-fmi_subprojects-fk_bank_branch_detail_id', 'tbl_fmi_subprojects', 'fk_bank_branch_detail_id');
        $this->addForeignKey(
            'fk-fmi_subprojects-fk_bank_branch_detail_id',
            'tbl_fmi_subprojects',
            'fk_bank_branch_detail_id',
            'bank_branch_details',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-fmi_subprojects-fk_bank_branch_detail_id',
            'tbl_fmi_subprojects'
        );
        $this->dropIndex('idx-fmi_subprojects-fk_bank_branch_detail_id', 'tbl_fmi_subprojects');
        $this->dropColumn('tbl_fmi_subprojects', 'fk_bank_branch_detail_id');
        $this->dropColumn('tbl_fmi_subprojects', 'project_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231212_063631_add_columns_in_tbl_fmi_subprojects_table cannot be reverted.\n";

        return false;
    }
    */
}

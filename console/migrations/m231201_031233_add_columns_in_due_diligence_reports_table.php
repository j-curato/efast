<?php

use yii\db\Migration;

/**
 * Class m231201_031233_add_columns_in_due_diligence_reports_table
 */
class m231201_031233_add_columns_in_due_diligence_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('due_diligence_reports', 'supplier_name', $this->string());
        $this->addColumn('due_diligence_reports', 'supplier_address', $this->text());
        $this->addColumn('due_diligence_reports', 'supplier_contact_number', $this->string());
        $this->addColumn('due_diligence_reports', 'supplier_contact_person', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('due_diligence_reports', 'supplier_name');
        $this->dropColumn('due_diligence_reports', 'supplier_address');
        $this->dropColumn('due_diligence_reports', 'supplier_contact_number');
        $this->dropColumn('due_diligence_reports', 'supplier_contact_person');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231201_031233_add_columns_in_due_diligence_reports_table cannot be reverted.\n";

        return false;
    }
    */
}

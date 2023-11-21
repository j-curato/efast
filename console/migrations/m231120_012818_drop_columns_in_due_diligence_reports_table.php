<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%columns_in_due_diligence_reports}}`.
 */
class m231120_012818_drop_columns_in_due_diligence_reports_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('due_diligence_reports', 'supplier_name');
        $this->dropColumn('due_diligence_reports', 'supplier_address');
        $this->dropColumn('due_diligence_reports', 'contact_person');
        $this->dropColumn('due_diligence_reports', 'contact_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('due_diligence_reports', 'supplier_name', $this->string());
        $this->addColumn('due_diligence_reports', 'supplier_address', $this->string());
        $this->addColumn('due_diligence_reports', 'contact_person', $this->string());
        $this->addColumn('due_diligence_reports', 'contact_number', $this->string());
    }
}

<?php

use yii\db\Migration;

/**
 * Class m230721_010247_add_fk_office_id_in_pr_aoq_table
 */
class m230721_010247_add_fk_office_id_in_pr_aoq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_aoq', 'fk_office_id', $this->integer());
        $this->createIndex('idx-aoq-fk_office_id', 'pr_aoq', 'fk_office_id');
        $this->addForeignKey('fk-aoq-fk_office_id', 'pr_aoq', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-aoq-fk_office_id', 'pr_aoq');
        $this->dropIndex('idx-aoq-fk_office_id', 'pr_aoq');
        $this->dropColumn('pr_aoq', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230721_010247_add_fk_office_id_in_pr_aoq_table cannot be reverted.\n";

        return false;
    }
    */
}

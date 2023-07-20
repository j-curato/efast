<?php

use Symfony\Component\Console\DependencyInjection\AddConsoleCommandPass;
use yii\db\Migration;

/**
 * Class m230720_070718_add_fk_office_id_in_pr_rfq_table
 */
class m230720_070718_add_fk_office_id_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('pr_rfq', 'fk_office_id', $this->integer());
        $this->createIndex('idx-pr_rfq-fk_office_id', 'pr_rfq', 'fk_office_id');
        $this->addForeignKey('fk-pr_rfq-fk_office_id', 'pr_rfq', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-pr_rfq-fk_office_id', 'pr_rfq', 'fk_office_id');
        $this->dropIndex('idx-pr_rfq-fk_office_id', 'pr_rfq');
        $this->dropColumn('pr_rfq', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230720_070718_add_fk_office_id_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}

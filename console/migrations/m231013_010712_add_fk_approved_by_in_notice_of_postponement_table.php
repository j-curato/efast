<?php

use yii\db\Migration;

/**
 * Class m231013_010712_add_fk_approved_by_in_notice_of_postponement_table
 */
class m231013_010712_add_fk_approved_by_in_notice_of_postponement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('notice_of_postponement', 'fk_approved_by', $this->bigInteger());
        $this->createIndex('idx-notice_of_postponement-fk_approved_by', 'notice_of_postponement', 'fk_approved_by');
        $this->addForeignKey('idx-notice_of_postponement-fk_approved_by', 'notice_of_postponement', 'fk_approved_by', 'employee', 'employee_id', 'RESTRICT', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('idx-notice_of_postponement-fk_approved_by', 'notice_of_postponement');
        $this->dropIndex('idx-notice_of_postponement-fk_approved_by', 'notice_of_postponement');
        $this->dropColumn('notice_of_postponement', 'fk_approved_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231013_010712_add_fk_approved_by_in_notice_of_postponement_table cannot be reverted.\n";

        return false;
    }
    */
}

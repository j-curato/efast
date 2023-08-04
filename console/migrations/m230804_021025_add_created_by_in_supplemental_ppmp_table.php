<?php

use yii\db\Migration;

/**
 * Class m230804_021025_add_created_by_in_supplemental_ppmp_table
 */
class m230804_021025_add_created_by_in_supplemental_ppmp_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('supplemental_ppmp', 'fk_created_by', $this->bigInteger());
        $this->createIndex('idx->ppmp-fk_created_by', 'supplemental_ppmp', 'fk_created_by');
        $this->addForeignKey('fk->ppmp-fk_created_by', 'supplemental_ppmp', 'fk_created_by', 'user', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk->ppmp-fk_created_by', 'supplemental_ppmp');
        $this->dropIndex('idx->ppmp-fk_created_by', 'supplemental_ppmp');
        $this->dropColumn('supplemental_ppmp', 'fk_created_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230804_021025_add_created_by_in_supplemental_ppmp_table cannot be reverted.\n";

        return false;
    }
    */
}

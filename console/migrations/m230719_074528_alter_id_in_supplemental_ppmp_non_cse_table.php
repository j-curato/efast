<?php

use yii\db\Migration;

/**
 * Class m230719_074528_alter_id_in_supplemental_ppmp_non_cse_table
 */
class m230719_074528_alter_id_in_supplemental_ppmp_non_cse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items');
        $this->dropIndex('idx-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items');

        $this->alterColumn('supplemental_ppmp_non_cse_items', 'fk_supplemental_ppmp_non_cse_id', $this->bigInteger());
        $this->alterColumn('supplemental_ppmp_non_cse', 'id', $this->bigInteger());
        $this->createIndex('idx-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items', 'fk_supplemental_ppmp_non_cse_id');
        $this->addForeignKey('fk-fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse_items', 'fk_supplemental_ppmp_non_cse_id', 'supplemental_ppmp_non_cse', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230719_074528_alter_id_in_supplemental_ppmp_non_cse_table cannot be reverted.\n";

        return false;
    }
    */
}

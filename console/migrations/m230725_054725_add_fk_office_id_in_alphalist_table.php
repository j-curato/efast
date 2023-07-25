<?php

use yii\db\Migration;

/**
 * Class m230725_054725_add_fk_office_id_in_alphalist_table
 */
class m230725_054725_add_fk_office_id_in_alphalist_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('alphalist', 'fk_office_id', $this->integer());
        $this->createIndex('idx-alphalist-fk_office_id', 'alphalist', 'fk_office_id');
        $this->addForeignKey('fk-alphalist-fk_office_id', 'alphalist', 'fk_office_id', 'office', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-alphalist-fk_office_id', 'alphalist');
        $this->dropIndex('idx-alphalist-fk_office_id', 'alphalist');
        $this->dropColumn('alphalist', 'fk_office_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230725_054725_add_fk_office_id_in_alphalist_table cannot be reverted.\n";

        return false;
    }
    */
}

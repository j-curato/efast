<?php

use yii\db\Migration;

/**
 * Class m230906_080411_add_columns_in_record_allotment_entries_table
 */
class m230906_080411_add_columns_in_record_allotment_entries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {




        $this->addColumn('record_allotment_entries', 'fk_office_id', $this->integer());
        $this->addColumn('record_allotment_entries', 'fk_division_id', $this->bigInteger());
        $this->addColumn('record_allotment_entries', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));


        $this->createIndex('idx-alltmentEnt-fk_office_id', 'record_allotment_entries', 'fk_office_id');
        $this->addForeignKey('fk-alltmentEnt-fk_office_id', 'record_allotment_entries', 'fk_office_id', 'office', 'id', 'RESTRICT');

        $this->createIndex('idx-alltmentEnt-fk_division_id', 'record_allotment_entries', 'fk_division_id');
        $this->addForeignKey('fk-alltmentEnt-fk_division_id', 'record_allotment_entries', 'fk_division_id', 'divisions', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-alltmentEnt-fk_office_id', 'record_allotment_entries');
        $this->dropForeignKey('fk-alltmentEnt-fk_division_id', 'record_allotment_entries');

        $this->dropIndex('idx-alltmentEnt-fk_office_id', 'record_allotment_entries');
        $this->dropIndex('idx-alltmentEnt-fk_division_id', 'record_allotment_entries');

        $this->dropColumn('record_allotment_entries', 'fk_office_id');
        $this->dropColumn('record_allotment_entries', 'fk_division_id');
        $this->dropColumn('record_allotment_entries', 'created_at');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230906_080411_add_columns_in_record_allotment_entries_table cannot be reverted.\n";

        return false;
    }
    */
}

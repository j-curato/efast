<?php

use yii\db\Migration;

/**
 * Class m230106_032225_add_office_id_and_division_id_and_allotment_type_in_record_allotment_table
 */
class m230106_032225_add_office_id_and_division_id_and_allotment_type_in_record_allotment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('record_allotments', 'office_id', $this->integer());
        $this->addColumn('record_allotments', 'division_id', $this->bigInteger());
        $this->addColumn('record_allotments', 'allotment_type_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230106_032225_add_office_id_and_division_id_and_allotment_type_in_record_allotment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230106_032225_add_office_id_and_division_id_and_allotment_type_in_record_allotment_table cannot be reverted.\n";

        return false;
    }
    */
}

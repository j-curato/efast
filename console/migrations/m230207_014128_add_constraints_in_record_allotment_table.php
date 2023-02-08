<?php

use yii\db\Migration;

/**
 * Class m230207_014128_add_constraints_in_record_allotment_table
 */
class m230207_014128_add_constraints_in_record_allotment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // serial_number
        // book_id
        // responsibility_center_id
        // office_id
        // division_id
        // allotment_type_id
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=0")->execute();
        $this->createIndex('idx-serial_number', 'record_allotments', 'serial_number', true);
        $this->createIndex('idx-book_id', 'record_allotments', 'book_id');
        $this->createIndex('idx-responsibility_center_id', 'record_allotments', 'responsibility_center_id');
        $this->createIndex('idx-office_id', 'record_allotments', 'office_id');
        $this->createIndex('idx-allotment_type_id', 'record_allotments', 'allotment_type_id');

        $this->addForeignKey('fk-rec-allot-book_id', 'record_allotments', 'book_id', 'books', 'id', 'RESTRICT');
        $this->addForeignKey('fk-rec-allot-responsibility_center_id', 'record_allotments', 'responsibility_center_id', 'responsibility_center', 'id', 'RESTRICT');
        $this->addForeignKey('fk-rec-allot-office_id', 'record_allotments', 'office_id', 'office', 'id', 'RESTRICT');
        $this->addForeignKey('fk-rec-allot-division_id', 'record_allotments', 'division_id', 'divisions', 'id', 'RESTRICT');
        $this->addForeignKey('fk-rec-allot-allotment_type_id', 'record_allotments', 'allotment_type_id', 'allotment_type', 'id', 'RESTRICT');
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS=1")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


        $this->dropForeignKey('fk-rec-allot-book_id', 'record_allotments');
        $this->dropForeignKey('fk-rec-allot-responsibility_center_id', 'record_allotments');
        $this->dropForeignKey('fk-rec-allot-office_id', 'record_allotments');
        $this->dropForeignKey('fk-rec-allot-division_id', 'record_allotments');
        $this->dropForeignKey('fk-rec-allot-allotment_type_id', 'record_allotments');

        $this->dropIndex('idx-serial_number', 'record_allotments', true);
        $this->dropIndex('idx-book_id', 'record_allotments');
        $this->dropIndex('idx-responsibility_center_id', 'record_allotments');
        $this->dropIndex('idx-office_id', 'record_allotments');
        $this->dropIndex('idx-allotment_type_id', 'record_allotments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230207_014128_add_constraints_in_record_allotment_table cannot be reverted.\n";

        return false;
    }
    */
}

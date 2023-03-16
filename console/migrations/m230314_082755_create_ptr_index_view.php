<?php

use yii\db\Migration;

/**
 * Class m230314_082755_create_ptr_index_view
 */
class m230314_082755_create_ptr_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
        DROP VIEW IF EXISTS ptr_index;
        CREATE VIEW ptr_index as 
            SELECT 
            ptr.id,
            ptr.ptr_number,
            ptr.date,
            office.office_name,
            property.property_number,
            property.description,
            rcv_by.employee_name as receive_by,
            IFNULL(property_articles.article_name,property.article) as article,
            par.par_number
            FROM ptr
            JOIN property ON ptr.fk_property_id = property.id
            LEFT JOIN office ON ptr.fk_office_id = office.id
            LEFT JOIN employee_search_view as rcv_by ON  ptr.fk_received_by = rcv_by.employee_id
            LEFT JOIN property_articles ON property.fk_property_article_id = property_articles.id
            LEFT JOIN par ON ptr.id = par.fk_ptr_id
        SQL;
        $this->execute($sql);
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
        echo "m230314_082755_create_ptr_index_view cannot be reverted.\n";

        return false;
    }
    */
}

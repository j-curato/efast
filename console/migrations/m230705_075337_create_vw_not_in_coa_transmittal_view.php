<?php

use yii\db\Migration;

/**
 * Class m230705_075337_create_vw_not_in_coa_transmittal_view
 */
class m230705_075337_create_vw_not_in_coa_transmittal_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_not_in_coa_transmittal;
        CREATE VIEW vw_not_in_coa_transmittal as 
        
        SELECT 
        po_transmittal.id,
        po_transmittal.transmittal_number
         FROM po_transmittal
        WHERE 
        po_transmittal.is_accepted = 1
        AND NOT EXISTS (SELECT po_transmittal_to_coa_entries.fk_po_transmittal_id  FROM po_transmittal_to_coa_entries WHERE po_transmittal_to_coa_entries.is_deleted = 0
        AND po_transmittal_to_coa_entries.fk_po_transmittal_id = po_transmittal.id)")->query();
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
        echo "m230705_075337_create_vw_not_in_coa_transmittal_view cannot be reverted.\n";

        return false;
    }
    */
}

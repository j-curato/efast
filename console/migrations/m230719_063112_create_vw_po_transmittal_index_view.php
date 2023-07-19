<?php

use yii\db\Migration;

/**
 * Class m230719_063112_create_vw_po_transmittal_index_view
 */
class m230719_063112_create_vw_po_transmittal_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS vw_po_transmittal_index;
                CREATE VIEW vw_po_transmittal_index AS 
                SELECT 
                po_transmittal.id,
                po_transmittal.transmittal_number,
                po_transmittal.date,
                po_transmittal.is_accepted,
                (CASE
                    WHEN po_transmittal.is_accepted= 1 AND transmitted_to_coa.fk_po_transmittal_to_coa_id IS NULL THEN 'At RO'
                    WHEN transmitted_to_coa.fk_po_transmittal_to_coa_id IS NOT NULL THEN 'At COA'
                    ELSE 'Pending at RO'
                END) as `status`,
                
                po_transmittal.fk_office_id
                FROM 
                po_transmittal
                LEFT JOIN (
                SELECT 
                po_transmittal_to_coa_entries.fk_po_transmittal_id,
                po_transmittal_to_coa_entries.fk_po_transmittal_to_coa_id
                FROM po_transmittal_to_coa_entries
                WHERE po_transmittal_to_coa_entries.is_deleted = 0
                )as transmitted_to_coa
                ON po_transmittal.id = transmitted_to_coa.fk_po_transmittal_id")
            ->query();
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
        echo "m230719_063112_create_vw_po_transmittal_index_view cannot be reverted.\n";

        return false;
    }
    */
}

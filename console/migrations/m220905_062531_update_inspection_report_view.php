<?php

use yii\db\Migration;

/**
 * Class m220905_062531_update_inspection_report_view
 */
class m220905_062531_update_inspection_report_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS inspection_report_index ;
        CREATE VIEW inspection_report_index as SELECT 


        inspection_report.id,
        inspection_report.ir_number,
        request_for_inspection.rfi_number,
        responsibility_center.`name` as division,


       CONCAT(requested_by.f_name,' ',LEFT(requested_by.m_name,1),'. ',requested_by.l_name,' ',IFNULL(requested_by.suffix,'')) as requested_by,
        CONCAT(inspector.f_name,' ',LEFT(inspector.m_name,1),'. ',inspector.l_name,' ',IFNULL(inspector.suffix,'')) as inspector,
        CONCAT(chairperson.f_name,' ',LEFT(chairperson.m_name,1),'. ',chairperson.l_name,' ',IFNULL(chairperson.suffix,'')) as chairperson,
        CONCAT(property_unit.f_name,' ',LEFT(property_unit.m_name,1),'. ',property_unit.l_name,' ',IFNULL(property_unit.suffix,'')) as property_unit,
        pr_purchase_order_item.serial_number as po_number,
        payee.account_name as payee


        FROM inspection_report
        LEFT JOIN inspection_report_items ON inspection_report.id = inspection_report_items.fk_inspection_report_id
        LEFT JOIN request_for_inspection_items ON inspection_report_items.fk_request_for_inspection_item_id  = request_for_inspection_items.id
        LEFT JOIN request_for_inspection ON request_for_inspection_items.fk_request_for_inspection_id = request_for_inspection.id
       
            LEFT JOIN employee as requested_by ON request_for_inspection.fk_requested_by = requested_by.employee_id
        LEFT JOIN employee as inspector ON request_for_inspection.fk_inspector = inspector.employee_id
        LEFT JOIN employee as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
        LEFT JOIN employee as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
        LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id  = pr_purchase_order_items_aoq_items.id
        LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
LEFT JOIN responsibility_center ON request_for_inspection.fk_responsibility_center_id = responsibility_center.id
        GROUP BY
        inspection_report.id,
        inspection_report.ir_number,
        request_for_inspection.rfi_number,
        responsibility_center.`name`,
                     CONCAT(requested_by.f_name,' ',LEFT(requested_by.m_name,1),'. ',requested_by.l_name,' ',IFNULL(requested_by.suffix,'')),
        CONCAT(inspector.f_name,' ',LEFT(inspector.m_name,1),'. ',inspector.l_name,' ',IFNULL(inspector.suffix,'')),
        CONCAT(chairperson.f_name,' ',LEFT(chairperson.m_name,1),'. ',chairperson.l_name,' ',IFNULL(chairperson.suffix,'')) ,
        CONCAT(property_unit.f_name,' ',LEFT(property_unit.m_name,1),'. ',property_unit.l_name,' ',IFNULL(property_unit.suffix,'')),
        pr_purchase_order_item.serial_number ,
        payee.account_name  ")->query();
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
        echo "m220905_062531_update_inspection_report_view cannot be reverted.\n";

        return false;
    }
    */
}

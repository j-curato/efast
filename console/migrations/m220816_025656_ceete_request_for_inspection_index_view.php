<?php

use yii\db\Migration;

/**
 * Class m220816_025656_ceete_request_for_inspection_index_view
 */
class m220816_025656_ceete_request_for_inspection_index_view extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("CREATE VIEW request_for_inspection_index as SELECT 


        request_for_inspection.id,
        
        request_for_inspection.rfi_number,
        pr_office.division,
        pr_office.unit,
        
        CONCAT(unit_head.f_name,' ',LEFT(unit_head.m_name,1),'. ',unit_head.l_name,' ',IFNULL(unit_head.suffix,'')) as unit_head,
        CONCAT(inspector.f_name,' ',LEFT(inspector.m_name,1),'. ',inspector.l_name,' ',IFNULL(inspector.suffix,'')) as inspector,
        CONCAT(chairperson.f_name,' ',LEFT(chairperson.m_name,1),'. ',chairperson.l_name,' ',IFNULL(chairperson.suffix,'')) as chairperson,
        CONCAT(property_unit.f_name,' ',LEFT(property_unit.m_name,1),'. ',property_unit.l_name,' ',IFNULL(property_unit.suffix,'')) as property_unit,
        pr_purchase_order_item.serial_number as po_number,
        payee.account_name as payee,
        pr_purchase_request.purpose,
        pr_project_procurement.title as project_name,
        request_for_inspection.is_final,
        request_for_inspection.date
        
        FROM  request_for_inspection 
        LEFT JOIN request_for_inspection_items ON request_for_inspection.id  = request_for_inspection_items.fk_request_for_inspection_id
        LEFT JOIN pr_office ON request_for_inspection.fk_pr_office_id = pr_office.id
        LEFT JOIN employee  as unit_head ON pr_office.fk_unit_head = unit_head.employee_id
        LEFT JOIN employee as inspector ON request_for_inspection.fk_inspector = inspector.employee_id
        LEFT JOIN employee as chairperson ON request_for_inspection.fk_chairperson = chairperson.employee_id
        LEFT JOIN employee as property_unit ON request_for_inspection.fk_property_unit = property_unit.employee_id
        LEFT JOIN pr_purchase_order_items_aoq_items ON request_for_inspection_items.fk_pr_purchase_order_items_aoq_item_id  = pr_purchase_order_items_aoq_items.id
        LEFT JOIN pr_purchase_order_item ON pr_purchase_order_items_aoq_items.fk_purchase_order_item_id = pr_purchase_order_item.id
        LEFT JOIN pr_aoq_entries ON pr_purchase_order_items_aoq_items.fk_aoq_entries_id = pr_aoq_entries.id
        LEFT JOIN payee ON pr_aoq_entries.payee_id = payee.id
        LEFT JOIN pr_rfq_item ON pr_aoq_entries.pr_rfq_item_id = pr_rfq_item.id
        LEFT JOIN pr_purchase_request_item ON pr_rfq_item.pr_purchase_request_item_id = pr_purchase_request_item.id
        LEFT JOIN pr_purchase_request ON pr_purchase_request_item.pr_purchase_request_id = pr_purchase_request.id
        LEFT JOIN pr_project_procurement ON pr_purchase_request.pr_project_procurement_id = pr_project_procurement.id
        GROUP BY
        request_for_inspection.id,
        
        request_for_inspection.rfi_number,
        pr_office.division,
        pr_office.unit,
        CONCAT(unit_head.f_name,' ',LEFT(unit_head.m_name,1),'. ',unit_head.l_name,' ',IFNULL(unit_head.suffix,'')),
        CONCAT(inspector.f_name,' ',LEFT(inspector.m_name,1),'. ',inspector.l_name,' ',IFNULL(inspector.suffix,'')),
        CONCAT(chairperson.f_name,' ',LEFT(chairperson.m_name,1),'. ',chairperson.l_name,' ',IFNULL(chairperson.suffix,'')) ,
        CONCAT(property_unit.f_name,' ',LEFT(property_unit.m_name,1),'. ',property_unit.l_name,' ',IFNULL(property_unit.suffix,'')),
        pr_purchase_order_item.serial_number ,
        payee.account_name,
        pr_purchase_request.purpose,
        pr_project_procurement.title,
        request_for_inspection.is_final,
        request_for_inspection.date ")->query();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Yii::$app->db->createCommand("DROP VIEW IF EXISTS request_for_inspection_index")->query();
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220816_025656_ceete_request_for_inspection_index_view cannot be reverted.\n";

        return false;
    }
    */
}

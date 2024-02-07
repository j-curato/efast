<?php

use yii\db\Migration;

/**
 * Class m240206_081430_add_constraints_in_property_table
 */
class m240206_081430_add_constraints_in_property_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex("idx-property-book_id", 'property', 'book_id');
        $this->addForeignKey("fk-property-book_id", 'property', 'book_id', 'books', 'id', 'RESTRICT');

        $this->createIndex("idx-property-unit_of_measure_id", 'property', 'unit_of_measure_id');
        $this->addForeignKey("fk-property-unit_of_measure_id", 'property', 'unit_of_measure_id', 'unit_of_measure', 'id', 'RESTRICT');

        $this->createIndex("idx-property-employee_id", 'property', 'employee_id');
        $this->addForeignKey("fk-property-employee_id", 'property', 'employee_id', 'employee', 'employee_id', 'RESTRICT');

        $this->createIndex("idx-property-fk_ssf_category_id", 'property', 'fk_ssf_category_id');
        $this->addForeignKey("fk-property-fk_ssf_category_id", 'property', 'fk_ssf_category_id', 'ssf_category', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-property-book_id", 'property');
        $this->dropIndex("idx-property-book_id", 'property');

        $this->dropForeignKey("fk-property-unit_of_measure_id", 'property');
        $this->dropIndex("idx-property-unit_of_measure_id", 'property');

        $this->dropForeignKey("fk-property-employee_id", 'property');
        $this->dropIndex("idx-property-employee_id", 'property');

        $this->dropForeignKey("fk-property-fk_ssf_category_id", 'property');
        $this->dropIndex("idx-property-fk_ssf_category_id", 'property');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_081430_add_constraints_in_property_table cannot be reverted.\n";

        return false;
    }
    */
}

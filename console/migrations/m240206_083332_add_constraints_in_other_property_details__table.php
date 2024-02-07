<?php

use yii\db\Migration;

/**
 * Class m240206_083332_add_constraints_in_other_property_details__table
 */
class m240206_083332_add_constraints_in_other_property_details__table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS =0")->query();
        $this->createIndex("idx-other_property_details-fk_chart_of_account_id", 'other_property_details', 'fk_chart_of_account_id');
        $this->addForeignKey("fk-other_property_details-fk_chart_of_account_id", 'other_property_details', 'fk_chart_of_account_id', 'chart_of_accounts', 'id', 'RESTRICT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey("fk-other_property_details-fk_chart_of_account_id", 'other_property_details');
        $this->dropIndex("idx-other_property_details-fk_chart_of_account_id", 'other_property_details');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240206_083332_add_constraints_in_other_property_details__table cannot be reverted.\n";

        return false;
    }
    */
}

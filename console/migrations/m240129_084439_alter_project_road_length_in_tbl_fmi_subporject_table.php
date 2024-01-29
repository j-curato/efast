<?php

use yii\db\Migration;

/**
 * Class m240129_084439_alter_project_road_length_in_tbl_fmi_subporject_table
 */
class m240129_084439_alter_project_road_length_in_tbl_fmi_subporject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tbl_fmi_subprojects', 'project_road_length', $this->decimal(10, 2));
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
        echo "m240129_084439_alter_project_road_length_in_tbl_fmi_subporject_table cannot be reverted.\n";

        return false;
    }
    */
}

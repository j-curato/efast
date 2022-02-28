<?php

use yii\db\Migration;

/**
 * Class m220228_030806_rename_rbac_composition_id_in_pr_rfq_table
 */
class m220228_030806_rename_rbac_composition_id_in_pr_rfq_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('pr_rfq', 'rbac_composition_id', 'bac_composition_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('pr_rfq', 'bac_composition_id', 'rbac_composition_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220228_030806_rename_rbac_composition_id_in_pr_rfq_table cannot be reverted.\n";

        return false;
    }
    */
}

<?php

use yii\db\Migration;

/**
 * Class m210329_053432_add_nature_of_transaction_id_to_dv_aucs_table
 */
class m210329_053432_add_nature_of_transaction_id_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('dv_aucs','nature_of_transaction_id',$this->integer());

        $this->createIndex(
            '{{%idx-dv_aucs-nature_of_transaction_id}}',
            '{{%dv_aucs}}',
            'nature_of_transaction_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs-nature_of_transaction_id}}',
            '{{%dv_aucs}}',
            'nature_of_transaction_id',
            '{{%nature_of_transaction}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-dv_aucs-nature_of_transaction_id}}',
            '{{%dv_aucs}}'
        );

        // drops index for column `nature_of_transaction_id`
        $this->dropIndex(
            '{{%idx-dv_aucs-nature_of_transaction_id}}',
            '{{%dv_aucs}}'
        );
        $this->dropColumn('dv_aucs','nature_of_transaction_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210329_053432_add_nature_of_transaction_id_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}

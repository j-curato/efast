<?php

use yii\db\Migration;

/**
 * Class m210329_052745_add_mrd_classification_id_to_dv_aucs_table
 */
class m210329_052745_add_mrd_classification_id_to_dv_aucs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn('dv_aucs','mrd_classification_id',$this->integer());

        $this->createIndex(
            '{{%idx-dv_aucs-mrd_classification_id}}',
            '{{%dv_aucs}}',
            'mrd_classification_id'
        );

        // add foreign key for table `{{%process_ors}}`
        $this->addForeignKey(
            '{{%fk-dv_aucs-mrd_classification_id}}',
            '{{%dv_aucs}}',
            'mrd_classification_id',
            '{{%mrd_classification}}',
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
            '{{%fk-dv_aucs-mrd_classification_id}}',
            '{{%dv_aucs}}'
        );

        // drops index for column `mrd_classification_id`
        $this->dropIndex(
            '{{%idx-dv_aucs-mrd_classification_id}}',
            '{{%dv_aucs}}'
        );
        $this->dropColumn('dv_aucs','mrd_classification_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210329_052745_add_mrd_classification_id_to_dv_aucs_table cannot be reverted.\n";

        return false;
    }
    */
}

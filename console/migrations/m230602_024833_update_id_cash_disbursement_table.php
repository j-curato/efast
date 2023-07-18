<?php

use yii\db\Migration;

/**
 * Class m230602_024833_update_id_cash_disbursement_table
 */
class m230602_024833_update_id_cash_disbursement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("SET FOREIGN_KEY_CHECKS = 0")->query();

        $this->alterColumn('cash_disbursement', 'parent_disbursement', $this->bigInteger());

        // DROP acic_cash_items fk_cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}'

        );
        // DROP acic_cash_items fk_cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}'
        );


        // DROP advances_entries cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}'

        );
        // DROP advances_entries cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}'
        );
        // DROP cash_disbursement_items cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}'

        );
        // DROP cash_disbursement_items cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}'
        );
        // DROP lddap_adas cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}'

        );
        // DROP lddap_adas cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}'
        );
        // DROP sliies cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}'

        );
        // DROP sliies cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}'
        );
        // DROP acic_cancelled_items cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}'

        );
        // DROP acic_cancelled_items cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}'
        );
        // DROP transmittal_entries cash_disbursement_id foreign_key
        $this->dropForeignKey(
            '{{%fk-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}'

        );
        // DROP transmittal_entries cash_disbursement_id index
        $this->dropIndex(
            '{{%idx-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}'
        );


        $this->alterColumn('acics_cash_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('advances_entries', 'cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('cash_disbursement_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('jev_preparation', 'cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('lddap_adas', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('sliies', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('acic_cancelled_items', 'fk_cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('transmittal_entries', 'cash_disbursement_id', $this->bigInteger());
        $this->alterColumn('cash_disbursement', 'id', $this->bigInteger());


        // CREATE acic_cash_items fk_cash_disbursement_id index
        $this->createIndex(
            '{{%idx-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}',
            'fk_cash_disbursement_id'
        );
        //    CREATE acic_cash_items fk_cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-acics_cash_items-fk_cash_disbursement_id}}',
            '{{%acics_cash_items}}',
            'fk_cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );


        // CREATE advances_entries cash_disbursement_id index
        $this->createIndex(
            '{{%idx-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}',
            'cash_disbursement_id'
        );
        // CREATE advances_entries cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-advances_entries-cash_disbursement_id}}',
            '{{%advances_entries}}',
            'cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );
        // CREATE cash_disbursement_items cash_disbursement_id index
        $this->createIndex(
            '{{%idx-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}',
            'fk_cash_disbursement_id'
        );
        // CREATE cash_disbursement_items cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-cash_disbursement_items-fk_cash_disbursement_id}}',
            '{{%cash_disbursement_items}}',
            'fk_cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );
        // CREATE lddap_adas cash_disbursement_id index
        $this->createIndex(
            '{{%idx-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}',
            'fk_cash_disbursement_id',
        );
        // CREATE lddap_adas cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-lddap_adas-fk_cash_disbursement_id}}',
            '{{%lddap_adas}}',
            'fk_cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );
        // CREATE sliies cash_disbursement_id index
        $this->createIndex(
            '{{%idx-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}',
            'fk_cash_disbursement_id',
        );
        // CREATE sliies cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-sliies-fk_cash_disbursement_id}}',
            '{{%sliies}}',
            'fk_cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );
        // CREATE acic_cancelled_items cash_disbursement_id index
        $this->createIndex(
            '{{%idx-acic_cancelled_items-fk_cash_disbursement_id}}',
            '{{%acic_cancelled_items}}',
            'fk_cash_disbursement_id',
        );
        // CREATE acic_cancelled_items cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-acic_cancelled_items-fk_cash_disbursement_idd}}',
            '{{%acic_cancelled_items}}',
            'fk_cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );
        // CREATE transmittal_entries cash_disbursement_id index
        $this->createIndex(
            '{{%idx-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}',
            'cash_disbursement_id',
        );
        // CREATE transmittal_entries cash_disbursement_id foreign_key
        $this->addForeignKey(
            '{{%fk-transmittal_entries-cash_disbursement_id}}',
            '{{%transmittal_entries}}',
            'cash_disbursement_id',
            'cash_disbursement',
            'id',
            'RESTRICT'

        );
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
        echo "m230602_024833_update_id_cash_disbursement_table cannot be reverted.\n";

        return false;
    }
    */
}

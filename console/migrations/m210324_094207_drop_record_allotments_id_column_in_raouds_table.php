<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%record_allotments_id_column_in_raouds}}`.
 */
class m210324_094207_drop_record_allotments_id_column_in_raouds_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            '{{%fk-raouds-record_allotment_id}}',
            '{{%raouds}}'
        );

        // drops index for column `record_allotment_id`
        $this->dropIndex(
            '{{%idx-raouds-record_allotment_id}}',
            '{{%raouds}}'
        );
        $this->dropColumn('raouds','record_allotment_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('raouds','record_allotment_id',$this->integer());


        // creates index for column `record_allotment_id`
        $this->createIndex(
            '{{%idx-raouds-record_allotment_id}}',
            '{{%raouds}}',
            'record_allotment_id'
        );
        $this->addForeignKey(
            '{{%fk-raouds-record_allotment_id}}',
            '{{%raouds}}',
            'record_allotment_id',
            '{{%record_allotments}}',
            'id',
            'CASCADE'
        );
    }
}

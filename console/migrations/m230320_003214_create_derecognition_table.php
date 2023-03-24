<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%derecognition}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%iirup}}`
 */
class m230320_003214_create_derecognition_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%derecognition}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'date' => $this->date()->notNull(),
            'fk_property_id' => $this->bigInteger(),
            // 'fk_iirup_id' => $this->bigInteger(),
            // 'fk_rlsddp_id' => $this->bigInteger(),
            'type' => $this->integer(),
            'last_mth_dep' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->alterColumn('derecognition', 'id', $this->bigInteger());

        // // creates index for column `fk_property_id`
        $this->createIndex(
            '{{%idx-derecognition-fk_property_id}}',
            '{{%derecognition}}',
            'fk_property_id'
        );

        // // add foreign key for table `{{%iirup}}`
        $this->addForeignKey(
            '{{%fk-derecognition-fk_property_id}}',
            '{{%derecognition}}',
            'fk_property_id',
            '{{%property}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%iirup}}`
        $this->dropForeignKey(
            '{{%fk-derecognition-fk_property_id}}',
            '{{%derecognition}}'
        );

        // drops index for column `fk_property_id`
        $this->dropIndex(
            '{{%idx-derecognition-fk_property_id}}',
            '{{%derecognition}}'
        );

        $this->dropTable('{{%derecognition}}');
    }
}

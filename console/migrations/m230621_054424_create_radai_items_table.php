<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%radai_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%radai}}`
 * - `{{%lddap_adas}}`
 */
class m230621_054424_create_radai_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%radai_items}}', [
            'id' => $this->primaryKey(),
            'fk_radai_id' => $this->bigInteger(),
            'fk_lddap_ada_id' => $this->integer(),
            'is_deleted' => $this->boolean()->defaultValue(0)
        ]);

        // creates index for column `fk_radai_id`
        $this->createIndex(
            '{{%idx-radai_items-fk_radai_id}}',
            '{{%radai_items}}',
            'fk_radai_id'
        );

        // add foreign key for table `{{%radai}}`
        $this->addForeignKey(
            '{{%fk-radai_items-fk_radai_id}}',
            '{{%radai_items}}',
            'fk_radai_id',
            '{{%radai}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_lddap_ada_id`
        $this->createIndex(
            '{{%idx-radai_items-fk_lddap_ada_id}}',
            '{{%radai_items}}',
            'fk_lddap_ada_id'
        );

        // add foreign key for table `{{%lddap_adas}}`
        $this->addForeignKey(
            '{{%fk-radai_items-fk_lddap_ada_id}}',
            '{{%radai_items}}',
            'fk_lddap_ada_id',
            '{{%lddap_adas}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%radai}}`
        $this->dropForeignKey(
            '{{%fk-radai_items-fk_radai_id}}',
            '{{%radai_items}}'
        );

        // drops index for column `fk_radai_id`
        $this->dropIndex(
            '{{%idx-radai_items-fk_radai_id}}',
            '{{%radai_items}}'
        );

        // drops foreign key for table `{{%lddap_adas}}`
        $this->dropForeignKey(
            '{{%fk-radai_items-fk_lddap_ada_id}}',
            '{{%radai_items}}'
        );

        // drops index for column `fk_lddap_ada_id`
        $this->dropIndex(
            '{{%idx-radai_items-fk_lddap_ada_id}}',
            '{{%radai_items}}'
        );

        $this->dropTable('{{%radai_items}}');
    }
}

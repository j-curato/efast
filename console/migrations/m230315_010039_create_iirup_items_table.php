<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%iirup_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%irrupp}}`
 * - `{{%par}}`
 */
class m230315_010039_create_iirup_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%iirup_items}}', [
            'id' => $this->primaryKey(),
            'fk_iirup_id' => $this->bigInteger()->notNull(),
            'fk_par_id' => $this->bigInteger()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_iirup_id`
        $this->createIndex(
            '{{%idx-iirup_items-fk_iirup_id}}',
            '{{%iirup_items}}',
            'fk_iirup_id'
        );

        // add foreign key for table `{{%irrupp}}`
        $this->addForeignKey(
            '{{%fk-iirup_items-fk_iirup_id}}',
            '{{%iirup_items}}',
            'fk_iirup_id',
            '{{%iirup}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_par_id`
        $this->createIndex(
            '{{%idx-iirup_items-fk_par_id}}',
            '{{%iirup_items}}',
            'fk_par_id'
        );

        // add foreign key for table `{{%par}}`
        $this->addForeignKey(
            '{{%fk-iirup_items-fk_par_id}}',
            '{{%iirup_items}}',
            'fk_par_id',
            '{{%par}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%irrupp}}`
        $this->dropForeignKey(
            '{{%fk-iirup_items-fk_iirup_id}}',
            '{{%iirup_items}}'
        );

        // drops index for column `fk_iirup_id`
        $this->dropIndex(
            '{{%idx-iirup_items-fk_iirup_id}}',
            '{{%iirup_items}}'
        );

        // drops foreign key for table `{{%par}}`
        $this->dropForeignKey(
            '{{%fk-iirup_items-fk_par_id}}',
            '{{%iirup_items}}'
        );

        // drops index for column `fk_par_id`
        $this->dropIndex(
            '{{%idx-iirup_items-fk_par_id}}',
            '{{%iirup_items}}'
        );

        $this->dropTable('{{%iirup_items}}');
    }
}

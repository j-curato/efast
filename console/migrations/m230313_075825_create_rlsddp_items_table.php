<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%rlsddp_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%rlsddp}}`
 * - `{{%par}}`
 */
class m230313_075825_create_rlsddp_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%rlsddp_items}}', [
            'id' => $this->primaryKey(),
            'fk_rlsddp_id' => $this->bigInteger()->notNull(),
            'fk_par_id' => $this->bigInteger()->notNull(),
            'is_deleted' => $this->boolean()->defaultValue(0),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);

        // creates index for column `fk_rlsddp_id`
        $this->createIndex(
            '{{%idx-rlsddp_items-fk_rlsddp_id}}',
            '{{%rlsddp_items}}',
            'fk_rlsddp_id'
        );

        // add foreign key for table `{{%rlsddp}}`
        $this->addForeignKey(
            '{{%fk-rlsddp_items-fk_rlsddp_id}}',
            '{{%rlsddp_items}}',
            'fk_rlsddp_id',
            '{{%rlsddp}}',
            'id',
            'CASCADE'
        );

        // creates index for column `fk_par_id`
        $this->createIndex(
            '{{%idx-rlsddp_items-fk_par_id}}',
            '{{%rlsddp_items}}',
            'fk_par_id'
        );

        // add foreign key for table `{{%par}}`
        $this->addForeignKey(
            '{{%fk-rlsddp_itm-fk_par_id}}',
            '{{%rlsddp_items}}',
            'fk_par_id',
            '{{%par}}',
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
        // drops foreign key for table `{{%rlsddp}}`
        $this->dropForeignKey(
            '{{%fk-rlsddp_items-fk_rlsddp_id}}',
            '{{%rlsddp_items}}'
        );

        // drops index for column `fk_rlsddp_id`
        $this->dropIndex(
            '{{%idx-rlsddp_items-fk_rlsddp_id}}',
            '{{%rlsddp_items}}'
        );

        // drops foreign key for table `{{%par}}`
        $this->dropForeignKey(
            '{{%fk-rlsddp_itm-fk_par_id}}',
            '{{%rlsddp_items}}'
        );

        // drops index for column `fk_par_id`
        $this->dropIndex(
            '{{%idx-rlsddp_items-fk_par_id}}',
            '{{%rlsddp_items}}'
        );

        $this->dropTable('{{%rlsddp_items}}');
    }
}

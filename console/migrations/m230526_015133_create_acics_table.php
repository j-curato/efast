<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acics}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%books}}`
 */
class m230526_015133_create_acics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acics}}', [
            'id' => $this->primaryKey(),
            'serial_number' => $this->string()->notNull()->unique(),
            'fk_book_id' => $this->integer()->notNull(),
            'date_issued' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('acics', 'id', $this->bigInteger());

        // creates index for column `fk_book_id`
        $this->createIndex(
            '{{%idx-acics-fk_book_id}}',
            '{{%acics}}',
            'fk_book_id'
        );

        // add foreign key for table `{{%books}}`
        $this->addForeignKey(
            '{{%fk-acics-fk_book_id}}',
            '{{%acics}}',
            'fk_book_id',
            '{{%books}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%books}}`
        $this->dropForeignKey(
            '{{%fk-acics-fk_book_id}}',
            '{{%acics}}'
        );

        // drops index for column `fk_book_id`
        $this->dropIndex(
            '{{%idx-acics-fk_book_id}}',
            '{{%acics}}'
        );

        $this->dropTable('{{%acics}}');
    }
}

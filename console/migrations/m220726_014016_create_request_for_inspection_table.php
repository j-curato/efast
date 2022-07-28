<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request_for_inspection}}`.
 */
class m220726_014016_create_request_for_inspection_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%request_for_inspection}}', [
            'id' => $this->primaryKey(),
            'rfi_number' => $this->string()->notNull()->unique(),
            'date' => $this->date(),
            'fk_chairperson' => $this->bigInteger(),
            'fk_inspector' => $this->bigInteger(),
            'fk_property_unit' => $this->bigInteger(),
            'fk_requested_by' => $this->bigInteger(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP')
        ]);
        $this->alterColumn('request_for_inspection', 'id', $this->bigInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%request_for_inspection}}');
    }
}

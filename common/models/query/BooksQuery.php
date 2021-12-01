<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\Books]].
 *
 * @see \common\models\Books
 */
class BooksQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\Books[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\Books|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

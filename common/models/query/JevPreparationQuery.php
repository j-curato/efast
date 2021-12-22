<?php

namespace common\models\query;

/**
 * This is the ActiveQuery class for [[\common\models\JevPreparation]].
 *
 * @see \common\models\JevPreparation
 */
class JevPreparationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \common\models\JevPreparation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\JevPreparation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}

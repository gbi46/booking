<?php

class CustomHtml extends ParentModel
{

    public function i18nFields()
    {
        return array(
            'body' => 'text null',
        );
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{custom_html}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
            array('status', 'numerical'),
            array($this->getI18nFieldSafe(), 'safe'),
            array('id, name, date_created, date_updated', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => tt('Name', 'customHtml'),
            'body' => tt('Body', 'customHtml'),
            'date_created' => 'Date Created',
            'date_updated' => 'Date Updated',
        );
    }

    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('date_created', $this->date_created, true);
        $criteria->compare('date_updated', $this->date_updated, true);

        return new CustomActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => $this->getTableAlias() . '.id ASC',
            ),
            'pagination' => array(
                'pageSize' => param('adminPaginationPageSize', 20),
            ),
        ));
    }

    public function getCode()
    {
        return '{customHtml-' . $this->id . '}';
    }

    public static function parseText($text)
    {
        $req = '/{customHtml-(\d{1,5}?)}/i';

        preg_match_all($req, $text, $match);

        if (isset($match[1])) {
            foreach ($match[1] as $id) {
                $model = CustomHtml::model()->findByPk($id);

                if ($model) {
                    $text = str_replace("{customHtml-{$id}}", $model->body, $text);
                } else {
                    $text = str_replace("{customHtml-{$id}}", '', $text);
                }
            }
        }

        return $text;
    }

    public function getBody()
    {
        return $this->getStrByLang('body');
    }

    public function getCodeForInsertTpl()
    {
        return CHtml::encode("<?php Yii::app()->controller->widget('application.modules.customHtml.components.CustomHtmlWidget', array('id' => {$this->id})); ?>");
    }
}

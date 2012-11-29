<?
/**
 * @property $id
 * @property $sherdog_id
 * @property $name
 * @property $nickname
 * @property $birthdate
 * @property $city
 * @property $height
 * @property $weight
 * @property $class
 * @property $association
 * @property $wins
 * @property $losses
 * @property $win_ko
 * @property $win_submissions
 * @property $win_decisions
 * @property $loss_ko
 * @property $loss_submissions
 * @property $loss_decisions
 * @property $image
 * @property $date_create
 * @property $date_update
 */
class Fighter extends ActiveRecord
{
    const PAGE_SIZE = 20;

    const IMAGE_DIR = 'upload/fighters/';

    const IMAGE_SIZE_NORMAL = 130;


    public function name()
    {
        return 'Боец';
    }


    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public function tableName()
    {
        return 'fighters';
    }


    public function rules()
    {
        return array(
            array(
                'sherdog_id, name',
                'required'
            ),
            array(
                'name, nickname, city, association, image',
                'length',
                'max' => 100
             ),
            array(
                'class',
                'length',
                'max' => 20
             ),

            array(
                'sherdog_id',
                'unique'
            ),
            array(
                'id, sherdog_id, wins, losses, win_ko, win_submissions, win_decisions, loss_ko, loss_submissions, loss_decisions',
                'numerical',
                'integerOnly' => true
            ),
            array(
                'height, weight',
                'numerical',
                'integerOnly' => false
            )
        );
    }


    public function relations()
    {
        return array(
        );
    }


    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->compare('id', $this->id, true);
        $criteria->compare('sherdog_id', $this->sherdog_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('nickname', $this->nickname, true);
        $criteria->compare('birthdate', $this->birthdate, true);
        $criteria->compare('city', $this->city, true);
        $criteria->compare('height', $this->height, true);
        $criteria->compare('weight', $this->weight, true);
        $criteria->compare('class', $this->class, true);
        $criteria->compare('association', $this->association, true);
        $criteria->compare('wins', $this->wins, true);
        $criteria->compare('losses', $this->losses, true);
        $criteria->compare('win_ko', $this->win_ko, true);
        $criteria->compare('win_submissions', $this->win_submissions, true);
        $criteria->compare('win_decisions', $this->win_decisions, true);
        $criteria->compare('loss_ko', $this->loss_ko, true);
        $criteria->compare('loss_submissions', $this->loss_submissions, true);
        $criteria->compare('loss_decisions', $this->loss_decisions, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('date_update', $this->date_update, true);

        return new ActiveDataProvider(get_class($this), array(
            'criteria'   => $criteria,
            'pagination' =>array(
                'pageSize' => self::PAGE_SIZE
            )
        ));
    }


    public function uploadFiles()
    {
        return array(
            'image' => array(
                'dir' => self::IMAGE_DIR
            ),
        );
    }


    public function getLink()
    {
        return CHtml::link($this->full_name, array('/mma/fighter/view', 'id' => $this->id));
    }


    public function getFullName()
    {
        $name = $this->name;
        if ($this->nickname)
        {
            $name.= ' "' . $this->nickname . '"';
        }

        return $name;
    }


    public function getImageSrc($size = IMAGE_SIZE_NORMAL)
    {
        if ($this->image)
        {
            $path = DOCUMENT_ROOT . self::IMAGE_DIR . $this->image;
            if (file_exists($path))
            {
                return ImageHelper::thumbSrc(self::IMAGE_DIR, $this->image, array('width' => $size, 'height' => null));
            }
        }
    }


    public function getImage($size = IMAGE_SIZE_NORMAL)
    {
        return CHtml::image($this->getImageSrc($size));
    }


    public function getImageLink($size = self::IMAGE_SIZE_NORMAL)
    {
        return CHtml::link($this->getImage($size), $this->url, array('title' => $this->full_name, 'alt' => $this->full_name));
    }


    public function afterFind()
    {
        $attrs_zero_if_null = array(
            'wins',
            'win_ko',
            'win_submissions',
            'win_decisions',
            'losses',
            'loss_ko',
            'loss_submissions',
            'loss_decisions'
        );

        foreach ($attrs_zero_if_null as $attr)
        {
            if (is_null($this->$attr))
            {
                $this->$attr = 0;
            }
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 20.03.18
 * Time: 17:34
 */

namespace shop\forms\manage\shop;


use shop\entities\shop\Characteristic;
use shop\helpers\CharacteristicHelper;
use yii\base\Model;

/**
 * @property array $variants
 */
class CharacteristicForm extends Model
{

    public $name;
    public $type;
    public $required;
    public $default;
    public $textVariants;
    public $sort;

    private $_characteristic;

    /**
     * CharacteristicForm constructor.
     * @param Characteristic|null $characteristic
     * @param array $config
     */

    public function __construct(Characteristic $characteristic = null, $config = [])
    {
        if ($characteristic) {
            $this->name = $characteristic->name;
            $this->type = $characteristic->type;
            $this->required = $characteristic->required;
            $this->default = $characteristic->default;
            $this->textVariants = implode(PHP_EOL, $characteristic->variants);
            $this->sort = $characteristic->sort;

            $this->_characteristic = $characteristic;

        } else {

            $this->sort = Characteristic::find()->max('sort') + 1;
        }
        parent::__construct($config);
    }

    /**
     * @return array
     */

    public function rules()
    {
        return [
            [['name', 'type', 'sort'], 'required'],
            [['required'], 'boolean'],
            [['default'], 'string', 'max' => 255],
            [['textVariants'], 'string'],
            [['sort'], 'integer'],
            [['name'], 'unique', 'targetClass' => Characteristic::class, 'filter' => $this->_characteristic ? ['<>', 'id', $this->_characteristic->id] : null]
        ];
    }

    /**
     * @return array
     */
    public function typesList()
    {
        return CharacteristicHelper::typeList();
    }

    /**
     * @return array|false|string[]
     */
    public function getVariants()
    {
        return preg_split('#\s+#i', $this->textVariants);
    }

}
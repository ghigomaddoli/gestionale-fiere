<?php

class Stati extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $descrizionestato;

    /**
     *
     * @var string
     */
    public $descrizionebreve;

    /**
     *
     * @var string
     */
    public $colore;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("falacosagiusta");
        $this->setSource("stati");
        $this->hasMany('id', 'LogStatiReservations', 'stati_id', ['alias' => 'LogStatiReservations']);
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Stati[]|Stati|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Stati|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return [
            'id' => 'id',
            'descrizionestato' => 'descrizionestato',
            'colore' => 'colore',
            'descrizionebreve' => 'descrizionebreve'
        ];
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'stati';
    }

}

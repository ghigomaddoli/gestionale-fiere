<?php

class Areas extends \Phalcon\Mvc\Model
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
    public $nome;

    /**
     *
     * @var integer
     */
    public $events_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("falacosagiusta");
        $this->setSource("areas");
        $this->hasMany('id', 'Reservations', 'areas_id', ['alias' => 'Reservations']);
        $this->belongsTo('events_id', 'Events', 'id', ['alias' => 'Events']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'areas';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Areas[]|Areas|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Areas|\Phalcon\Mvc\Model\ResultInterface
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
            'nome' => 'nome',
            'events_id' => 'events_id'
        ];
    }

}

<?php

class Events extends \Phalcon\Mvc\Model
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
    public $descrizione;

    /**
     *
     * @var integer
     */
    public $attivo;

    /**
     *
     * @var string
     */
    public $data_creazione;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("events");
        $this->hasMany('id', 'Areas', 'events_id', ['alias' => 'Areas','reusable' => true]);
        $this->hasMany('id', 'Reservations', 'events_id', ['alias' => 'Reservations','reusable' => true]);
        $this->hasMany('id', 'Services', 'events_id', ['alias' => 'Services','reusable' => true]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'events';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Events[]|Events|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Events|\Phalcon\Mvc\Model\ResultInterface
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
            'descrizione' => 'descrizione',
            'attivo' => 'attivo',
            'data_creazione' => 'data_creazione'
        ];
    }

}

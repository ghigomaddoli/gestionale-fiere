<?php


class Services extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $events_id;

    /**
     *
     * @var integer
     */
    public $tipologia;

    /**
     *
     * @var string
     */
    public $descrizione;

    /**
     *
     * @var string
     */
    public $descrizionebreve;

    /**
     *
     * @var double
     */
    public $prezzofasciaa;

    /**
     *
     * @var double
     */
    public $prezzofasciab;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("services");
        $this->hasMany('id', 'ReservationServices', 'services_id', ['alias' => 'ReservationServices']);
        $this->belongsTo('events_id', 'Events', 'id', ['alias' => 'Events']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'services';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Services[]|Services|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Services|\Phalcon\Mvc\Model\ResultInterface
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
            'events_id' => 'events_id',
            'tipologia' => 'tipologia',
            'descrizione' => 'descrizione',
            'descrizionebreve' => 'descrizionebreve',
            'prezzofasciaa' => 'prezzofasciaa',
            'prezzofasciab' => 'prezzofasciab'
        ];
    }

}

<?php


class Exhibitors extends \Phalcon\Mvc\Model
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
    public $ragionesociale;

    /**
     *
     * @var string
     */
    public $indirizzo;

    /**
     *
     * @var string
     */
    public $cap;

    /**
     *
     * @var string
     */
    public $citta;

    /**
     *
     * @var string
     */
    public $provincia;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $emailaziendale;

    /**
     *
     * @var string
     */
    public $piva;

    /**
     *
     * @var string
     */
    public $codfisc;

    /**
     *
     * @var string
     */
    public $pec;

    /**
     *
     * @var string
     */
    public $codicesdi;

    /**
     *
     * @var string
     */
    public $referentenome;

    /**
     *
     * @var string
     */
    public $referentetelefono;

    /**
     *
     * @var string
     */
    public $referenteemail;

    /**
     *
     * @var string
     */
    public $prodottiesposti;

    /**
     *
     * @var string
     */
    public $fasciadiprezzo;

    /**
     *
     * @var string
     */
    public $numerocoespositore;

    /**
     *
     * @var string
     */
    public $nomecoespositore;

    /**
     *
     * @var string
     */
    public $catalogonome;

    /**
     *
     * @var string
     */
    public $catalogoindirizzo;

    /**
     *
     * @var string
     */
    public $catalogocap;

    /**
     *
     * @var string
     */
    public $catalogocitta;

    /**
     *
     * @var string
     */
    public $catalogoprovincia;

    /**
     *
     * @var string
     */
    public $catalogotelefono;

    /**
     *
     * @var string
     */
    public $catalogoemail;

    /**
     *
     * @var string
     */
    public $catalogositoweb;

    /**
     *
     * @var string
     */
    public $catalogofacebook;

    /**
     *
     * @var string
     */
    public $catalogoinstagram;

    /**
     *
     * @var string
     */
    public $catalogotwitter;

    /**
     *
     * @var string
     */
    public $catalogodescrizione;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("exhibitors");
        $this->hasMany('id', 'Reservations', 'exhibitors_id', ['alias' => 'Reservations', 'reusable' => true]);
        $this->hasMany('id', 'Reservations', 'padre_id', ['alias' => 'Padri']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'exhibitors';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Exhibitors[]|Exhibitors|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Exhibitors|\Phalcon\Mvc\Model\ResultInterface
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
            'ragionesociale' => 'ragionesociale',
            'indirizzo' => 'indirizzo',
            'cap' => 'cap',
            'citta' => 'citta',
            'provincia' => 'provincia',
            'telefono' => 'telefono',
            'emailaziendale' => 'emailaziendale',
            'piva' => 'piva',
            'codfisc' => 'codfisc',
            'pec' => 'pec',
            'codicesdi' => 'codicesdi',
            'referentenome' => 'referentenome',
            'referentetelefono' => 'referentetelefono',
            'referenteemail' => 'referenteemail',
            'prodottiesposti' => 'prodottiesposti',
            'fasciadiprezzo' => 'fasciadiprezzo',
            'numerocoespositore' => 'numerocoespositore',
            'nomecoespositore' => 'nomecoespositore',
            'catalogonome' => 'catalogonome',
            'catalogoindirizzo' => 'catalogoindirizzo',
            'catalogocap' => 'catalogocap',
            'catalogocitta' => 'catalogocitta',
            'catalogoprovincia' => 'catalogoprovincia',
            'catalogotelefono' => 'catalogotelefono',
            'catalogoemail' => 'catalogoemail',
            'catalogositoweb' => 'catalogositoweb',
            'catalogofacebook' => 'catalogofacebook',
            'catalogoinstagram' => 'catalogoinstagram',
            'catalogotwitter' => 'catalogotwitter',
            'catalogodescrizione' => 'catalogodescrizione'
        ];
    }

}

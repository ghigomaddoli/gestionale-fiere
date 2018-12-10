<?php

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Users extends \Phalcon\Mvc\Model
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
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $nome;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $telefono;

    /**
     *
     * @var string
     */
    public $datacreazione;

    /**
     *
     * @var string
     */
    public $attivo;

    /**
     *
     * @var string
     */
    public $ruolo;

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Perfavore, immetti un indirizzo email valido',
                ]
            )
        );
        $validator->add(
            'email',
            new UniquenessValidator([
            'message' => 'Spiacente, email già utilizzato'
        ]));
        $validator->add(
            'username',
            new UniquenessValidator([
            'message' => 'Spiacente, username già utilizzato'
        ]));

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("c5_espositori");
        $this->setSource("users");
        $this->hasMany('id', 'NoteReservations', 'users_id', ['alias' => 'NoteReservations','reusable' => true]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}

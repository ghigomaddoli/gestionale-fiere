<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\StringLength as StringLength;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Validation\Validator\InclusionIn;

use Phalcon\Forms\Element;


class ReservationsForm extends Form
{
    public function initialize($entity = null, $options = array())
    {
        
        if (isset($options['edit'])) {
            $this->add(new Hidden("id"));
        }

        $events_id = new Text("events_id");
        $events_id->setLabel("events_id");
        $events_id->setFilters(['striptags', 'string']);
        $events_id->addValidators([
            new PresenceOf([
                'message' => 'events_id è obbligatoria'
            ])
        ]);
        $this->add($events_id);


        $exhibitors_id = new Text("exhibitors_id");
        $exhibitors_id->setLabel("exhibitors_id");
        $exhibitors_id->setFilters(['striptags', 'string']);
        $exhibitors_id->addValidators([
            new PresenceOf([
                'message' => 'exhibitors_id è obbligatoria'
            ])
        ]);
        $this->add($exhibitors_id);

        $areas_id = new Select("areas_id",
            Areas::find(),
            [
                'using'      => [
                    'id',
                    'nome',
                ],
                'useEmpty'   => true,
                'emptyText'  => 'Seleziona un\'area tematica...',
                'emptyValue' => '',
            ]
        );
        $areas_id->setLabel("Scelta dell'area tematica");
        $areas_id->setFilters(['striptags', 'string']);
        $this->add($areas_id);

        $codicestand = new Text("codicestand");
        $codicestand->setLabel("codice stand");
        $codicestand->setFilters(['striptags', 'string']);
        $this->add($codicestand);

        $padiglione = new Text("padiglione");
        $padiglione->setLabel("Padiglione");
        $padiglione->setFilters(['striptags', 'string']);
        $this->add($padiglione);

        $users_id = new Select("users_id",
            Users::find("attivo = 1"),
            [
                'using'      => [
                    'id',
                    'username',
                ],
                'useEmpty'   => true,
                'emptyText'  => 'Seleziona il commerciale di riferimento',
                'emptyValue' => '',
            ]
        );
        $users_id->setLabel("Commerciale di riferimento");
        $users_id->setFilters(['striptags', 'string']);
        $this->add($users_id);

        $padre_id = new Select("padre_id",
            Exhibitors::find(),
            [
                'using'      => [
                    'id',
                    'ragionesociale',
                ],
                'useEmpty'   => true,
                'emptyText'  => 'Seleziona un co-espositore',
                'emptyValue' => '',
            ]
        );
        $padre_id->setLabel("Co-espositore di..");
        $padre_id->setFilters(['striptags', 'string']);
        $this->add($padre_id);

        $prezzofinale = new Text("prezzofinale");
        $prezzofinale->setLabel("prezzo finale");
        $prezzofinale->setFilters(['striptags', 'string']);
        $this->add($prezzofinale);

        $notepagamento = new Text("notepagamento");
        $notepagamento->setLabel("note pagamento");
        $notepagamento->setFilters(['striptags', 'string']);
        $this->add($notepagamento);

        $notecondivise = new Text("notecondivise");
        $notecondivise->setLabel("note condivise");
        $notecondivise->setFilters(['striptags', 'string']);
        $notecondivise->addValidators([
            new StringLength(
                [
                    "max"            => 300,
                    "messageMaximum" => "Il campo Note condivise è troppo lungo (max 300 caratteri)",
                    "allowEmpty" => true,
                ]
            )
        ]);
        $this->add($notecondivise);

        $altriservizi = new Text("altriservizi");
        $altriservizi->setLabel("altri servizi");
        $altriservizi->setFilters(['striptags', 'string']);
        $this->add($altriservizi);

        $prezzoaltriservizi = new Text("prezzoaltriservizi");
        $prezzoaltriservizi->setLabel("prezzo altri servizi");
        $prezzofinale->setFilters(['striptags', 'string']);
        $this->add($prezzoaltriservizi);

        $optionspc = array(
            'name'  => "interventoprogrammaculturale",
            'class' => 'form-control',
            'value' => 1,
        );

        $interventoprogrammaculturale = new Check("interventoprogrammaculturale", $optionspc);
        $interventoprogrammaculturale->setLabel("intervento programma culturale");
        $this->add($interventoprogrammaculturale);      

        $prezzostandpersonalizzato = new Text("prezzostandpersonalizzato");
        $prezzostandpersonalizzato->setLabel("prezzo stand personalizzato");
        $prezzostandpersonalizzato->setFilters(['striptags', 'string']);
        $this->add($prezzostandpersonalizzato);

        $standpersonalizzato = new Text("standpersonalizzato");
        $standpersonalizzato->setLabel("descrizione stand personalizzato");
        $standpersonalizzato->setFilters(['striptags', 'string','trim']);
        $this->add($standpersonalizzato);

        $stato = new Text("stato");
        $stato->setLabel("stato della richiesta");
        $stato->setFilters(['striptags', 'string']);
        $stato->addValidators([
            new PresenceOf([
                'message' => 'Lo stato della richiesta è obbligatorio'
            ])
        ]);
        $this->add($stato);

        $numerofattura = new Text("numerofattura");
        $numerofattura->setLabel("Numero Fattura");
        $numerofattura->setFilters(['striptags', 'string', 'trim']);
        $this->add($numerofattura);

        $anticiporichiesto = new Text("anticiporichiesto");
        $anticiporichiesto->setLabel("richiesta anticipo inviata");
        $anticiporichiesto->setFilters(['striptags', 'string','trim']);
        $this->add($anticiporichiesto);

        $anticipopagato = new Text("anticipopagato");
        $anticipopagato->setLabel("anticipo pagato");
        $anticipopagato->setFilters(['striptags', 'string','trim']);
        $this->add($anticipopagato);

        $pagamentocompleto = new Text("pagamentocompleto");
        $pagamentocompleto->setLabel("pagamento completo");
        $pagamentocompleto->setFilters(['striptags', 'string','trim']);
        $this->add($pagamentocompleto);

    }
}
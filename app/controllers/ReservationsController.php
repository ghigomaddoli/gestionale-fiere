<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;


class ReservationsController extends ControllerBase
{
    public function indexAction()
    {
        $this->view->areas = Areas::find("events_id = ".$this->evento->id);
        $this->view->stati = $this->stati;
        $numberPage = 1;
        $par = array();
        $parameters = array();
        $orderby = '';

        if ($this->request->isPost()) {

            $areas_id = $this->request->getPost('areas_id', 'int');
            $stato = $this->request->getPost('stato', 'int');
            $ragionesociale = $this->request->getPost('ragionesociale', 'string');
            $interventoprogrammaculturale = $this->request->getPost('interventoprogrammaculturale', 'int');
            $orderby = $this->request->getPost('orderby', 'string');

            if(!empty($areas_id)){
                $par["areas_id"] = $areas_id;
            }
            if(!empty($stato)){
                $par["stato"] = $stato;
            }
            if(!empty($ragionesociale)){
                $par["ragionesociale"] = $ragionesociale;
            }
            if(!empty($interventoprogrammaculturale)){
                $par["interventoprogrammaculturale"] = $interventoprogrammaculturale;
            }            
            if(!empty($orderby)){
                $par["orderby"] = $orderby;
            }
          //  \PhalconDebug::info($par);
            $this->persistent->searchParams = $par;
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $builder = $this->modelsManager->createBuilder()
        ->from('Reservations')
        ->join('exhibitors')
        ->where('1 = 1');

        if ($this->persistent->searchParams && count($this->persistent->searchParams)) {
            $parameters = $this->persistent->searchParams;
        }

       // \PhalconDebug::info("this->persistent->searchParams",$this->persistent->searchParams);

        foreach($parameters as $campo => $valore){
            switch($campo){
                case "ragionesociale":
                    $builder->andWhere("exhibitors.{$campo} like '%".$valore."%'");
                break;
                case "orderby":
                    $builder->orderBy($valore);
                break;
                default:
                    $builder->andWhere("{$campo} = {$valore}");
                break;
            }
        }

        if(empty($parameters['orderby'])){
            $builder->orderBy('Reservations.id DESC');
        }

        

       $paginator = new PaginatorQueryBuilder(
            [
                'builder' => $builder,
                'limit'   => 10,
                'page'    => $numberPage,
            ]
        );

        $this->view->page = $paginator->getPaginate();

        if ($this->view->page->total_items == 0) {
            $this->flash->notice("Nessun espositore da mostrare con questi criteri di ricerca");
            $this->persistent->searchParams = null;
        }     

    }

    public function initialize()
    {
        $this->tag->setTitle('Prenotazioni');
        parent::initialize();
    }

    public function editAction($id)
    {
        $reservation = Reservations::findFirstById($id);
        if (!$reservation) {
            $this->flash->error("Dettaglio della richiesta stand non trovato");

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "index",
                ]
            );
        }

        $this->view->reservation = $reservation;
        $this->view->areas = Areas::find("events_id = ".$reservation->events_id);
        $this->tag->setDefault('areas_id', $reservation->areas_id);
        $this->view->stands = Services::find("events_id = ".$reservation->events_id." AND tipologia IN (1,2)");
        $this->view->services = Services::find("events_id = ".$reservation->events_id." AND tipologia = 3");
        $reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);
        $this->view->reservationservices = $reservationservices;

        $prezzocalcolato = 0;
        $campoprezzo = $reservation->getExhibitors()->fasciadiprezzo == 'a' ? 'prezzofasciaa' : 'prezzofasciab';

        //calcolo del prezzo totale teorico:
        foreach($reservationservices as $servizio){
            $prezzo = $servizio->getServices()->$campoprezzo * $servizio->quantita;
            \PhalconDebug::info($servizio->getServices()->descrizione.':'.$prezzo.', quantita:'.$servizio->quantita);
            $prezzocalcolato = $prezzocalcolato + $prezzo;
        }
        $this->view->prezzocalcolato = $prezzocalcolato;

        $stati = Stati::find();
        $this->view->stati = $stati;
        $this->view->statimax = count($stati);

        $this->assets->addJs('js/reservations-edit.js');
    }



    /**
     * salva la prenotazione corrente
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->flash->error("Richiesta con metodo non valido");
            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");


        // Inizio della transaction
        $this->db->begin();

        $reservation = Reservations::findFirstById($id);
        if (!$reservation) {
            $this->flash->error("la Prenotazione stand non esiste");

            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }

        $form = new ReservationsForm;
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (!$form->isValid($data, $reservation)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }


        if ($reservation->save() == false) {
            foreach ($reservation->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        // cancello i records su reservation_services e li riinserisco aggiornati    
        $rs = ReservationServices::find("reservations_id='{$id}'");

        if($rs->delete()== false){
            foreach ($rs->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }
        $servizi = $this->request->getPost("services");
        \PhalconDebug::info("servizi",$servizi);
        if(is_array($servizi) && count($servizi)){
            foreach($servizi as $idservizio => $quantita){
                if($quantita == 0) continue;
                $rs = new ReservationServices;
                $rs->reservations_id = $id;
                $rs->services_id = $idservizio;
                $rs->quantita = $quantita;
                if ($rs->save() == false) {
                    foreach ($rs->getMessages() as $message) {
                        $this->flash->error($message);
                    }

                    $this->db->rollback();
        
                    return $this->dispatcher->forward(
                        [
                            "controller" => "reservations",
                            "action"     => "edit",
                            "params"     => [$id]
                        ]
                    );
                }
            }
        }
        
        // Commit the transaction
        $this->db->commit();

        $form->clear();    

        $this->flash->success("I dati della prenotazione dell'espositore {$reservation->getExhibitors()->ragionesociale} sono stati aggiornati");

        return $this->dispatcher->forward(
            [
                "controller" => "reservations",
                "action"     => "edit",
                "params"     => [$id]
            ]
        );
    }

}


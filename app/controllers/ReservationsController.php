<?php

class ReservationsController extends ControllerBase
{
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
        $this->view->reservationservices = ReservationServices::find("reservations_id = ".$reservation->id);

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
        $rs = Reservationservices::find("reservations_id='{$id}'");

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

        $this->flash->success("I dati della prenotazione sono stati aggiornati");

        return $this->dispatcher->forward(
            [
                "controller" => "reservations",
                "action"     => "edit",
                "params"     => [$id]
            ]
        );
    }

}


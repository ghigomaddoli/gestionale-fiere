<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ExhibitorsController extends ControllerBase
{

    public function indexAction()
    {

        $numberPage = 1;

        if ($this->request->isPost()) {
            $this->persistent->searchParams = null; 
            $query = Criteria::fromInput($this->di, "Reservations", $this->request->getPost());
            //$this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $reservations = reservations::find($parameters);
        if (count($reservations) == 0) {
            $this->flash->notice("Nessun espositore da mostrare");

            return $this->dispatcher->forward(
                [
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }
        $this->view->richieste = count($reservations);

        $paginator = new Paginator(array(
            "data"  => $reservations,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    public function newAction()
    {
        $this->view->form = new ExhibitorsForm();
    }
    

    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "index",
                ]
            );
        }
        
        // inizio della transaction
        $this->db->begin();

        $form = new ExhibitorsForm;
        $exhibitors = new Exhibitors();
        $data = $this->request->getPost();


        if (!$form->isValid($data, $exhibitors)) {

            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "new",
                ]
            );
        }

        $this->miologger->log("passo prima del save");

        if ($exhibitors->save() === false) {

            $this->miologger->log("errore nel salvataggio exhibitors!");

            foreach ($exhibitors->getMessages() as $message) {
                $this->flash->error("dal model: ".$message);
            }
            
            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "new",
                ]
            );
        }

        $this->miologger->log(" save del model reservations..");
        $reservations = new Reservations();
        $reservations->exhibitors_id = $exhibitors->id;
        $reservations->events_id = $this->evento->id;
        $reservations->areas_id = $this->request->getPost('areas_id','int!');
        $reservations->codicestand = $this->request->getPost('codicestand','alphanum');

        if ($reservations->save() === false) {

            foreach ($reservations->getMessages() as $message) {
                $this->flash->error("dal model reservations: ".$message);
            }

            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "new",
                ]
            );
        }

        /* facciamo la insert dei servizi richiesti nella tabella reservation_services */
        $this->miologger->log(" save del model reservations_services..");
        $servizirichiesti = $this->request->getPost('services');
        // \PhalconDebug::info($servizirichiesti);
        foreach($servizirichiesti as $services_id => $quantita){
            if($quantita > 0){
                $reservationservices = new ReservationServices();
                $reservationservices->reservations_id = $reservations->id;
                $reservationservices->services_id = $services_id;
                $reservationservices->quantita = $quantita;
                if ($reservationservices->save() === false) {

                    foreach ($reservationservices->getMessages() as $message) {
                        $this->flash->error("dal model reservationservices: ".$message);
                    }

                    $this->db->rollback();
        
                    return $this->dispatcher->forward(
                        [
                            "controller" => "exhibitors",
                            "action"     => "new",
                        ]
                    );
                }
            }
        }

        /* facciamo una insert anche nella tabella degli stati */
        $logstatireservations = new LogStatiReservations();
        $logstatireservations->reservations_id = $reservations->id;
        $logstatireservations->stati_id = 1; // 1= richiesta prenotazione pendente
        $logstatireservations->dataora = date("Y-m-d H:i:s");
        $logstatireservations->messaggio = "Nuova Prenotazione Inserita";
        if ($logstatireservations->save() === false) {

            foreach ($logstatireservations->getMessages() as $message) {
                $this->flash->error("dal model logstatireservations: ".$message);
            }

            $this->db->rollback();

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "new",
                ]
            );
        }

        // Commit the transaction
        $this->db->commit();

        $form->clear();

        $this->flash->success("I Dati della domanda di partecipazione dell'espositore sono stati inseriti con successo!");
        $this->persistent->searchParams = null;
        return $this->dispatcher->forward(
            [
                "controller" => "exhibitors",
                "action"     => "index",
            ]
        );
    }


    /**
     * Cancella i dati anagrafici di un espositore
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $exhibitors = Exhibitors::findFirstById($id);
        if (!$exhibitors) {
            $this->flash->error("Espositore non trovato");

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "index",
                ]
            );
        }

        if (!$exhibitors->delete()) {
            foreach ($exhibitors->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "index",
                ]
            );
        }

        $this->flash->success("Espositore cancellato definitivamente!");

            return $this->dispatcher->forward(
                [
                    "controller" => "exhibitors",
                    "action"     => "index",
                ]
            );
    }

}


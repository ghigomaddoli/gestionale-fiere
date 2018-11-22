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
        //$this->view->form = new ExhibitorsForm();
        $this->view->province = Province::find();
        $this->view->areas = Areas::find("events_id = ".$this->evento->id);
        $this->assets->addCss('css/style.css');
        $this->view->stands = Services::find("events_id = ".$this->evento->id." AND tipologia IN (1,2)");
        $this->view->services = Services::find("events_id = ".$this->evento->id." AND tipologia = 3");
        $this->assets->addJs('js/exhibitors-new.js');
        foreach($this->view->stands as $stand){
            $arraystand[] = $stand->id;
        }
        $this->view->arraystand = implode(",",$arraystand);
        foreach($this->view->services as $servizio){
            $arrayservizi[] = $servizio->id;
        }
        $this->view->arrayservizi = implode(",",$arrayservizi);
    }    

    /* metodo che chiamo prima del create per la valizazione del form in ajax (evento jquery beforesubmit) */
    public function validateAction(){
        
        if($this->request->isAjax()){

            $form = new ExhibitorsForm;
            $exhibitors = new Exhibitors();
            $data = $this->request->getPost();
    
            if (!$form->isValid($data, $exhibitors)) {

                foreach($form->getElements() as $elemento){

                    if($elemento->hasMessages()){
                        $nomeelemento = $elemento->getName();
                        
                        foreach($elemento->getMessages() as $msg){
                            $aa = $msg->getMessage();
                        }
                        $ris[$nomeelemento] = $aa;
                        
                    }
                }
                $ris["status"] = "KO";
                return $this->response->setJsonContent($ris);
            }

            // verifico che almeno uno stand sia stato selezionato
            $arrayservizi = $this->request->getPost('services');
         //   \PhalconDebug::info('array dei seervizi ricevuto dal form',$arrayservizi);
            $services = Services::find("tipologia=2");
            $check = false;
            foreach($services as $servizio){
                if(in_array($servizio->id,$arrayservizi)){
                    $check = true;
                    break;
                }
            }
            if($check == false && empty($this->request->getPost('standpersonalizzato','trim'))){
                $ris["status"] = "KO";
                $ris["stand"] = "Selezionare almeno uno stand o riempire il campo per uno stand personalizzato";
                return $this->response->setJsonContent($ris);
            }
       
            
            return $this->response->setJsonContent(
                [
                    "status" => "OK"
                ]
            );
            

        }
    }

    public function createAction()
    {

        if (!$this->request->isPost() ) {
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

        \PhalconDebug::info("passo prima del save");
        \PhalconDebug::info($data);

        if ($exhibitors->save() === false) {

            \PhalconDebug::info("errore nel salvataggio exhibitors");

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

        \PhalconDebug::info(" save del model reservations..");
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
        \PhalconDebug::info(" save del model reservations_services..");

        $servizirichiesti = $this->request->getPost('services');

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
        \PhalconDebug::info(" facciamo una insert anche nella tabella degli stati ");
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
        \PhalconDebug::info(" tutto ok faccio la commit ");
        // Commit the transaction
        $this->db->commit();

        $form->clear();

        $this->flash->success("I Dati della domanda di partecipazione dell'espositore sono stati inseriti con successo!");
        
        return $this->dispatcher->forward(
            [
                "controller" => "index",
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


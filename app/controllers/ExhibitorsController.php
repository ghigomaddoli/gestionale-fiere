<?php
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class ExhibitorsController extends ControllerBase
{

    public function indexAction()
    {


    }

    public function newAction()
    {
        //$this->view->form = new ExhibitorsForm();
        $this->view->province = Province::find();
        $this->view->provinciadefault = 'PG';
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

    public function createAction()
    {
        if (!$this->request->isAjax()) {
            $ris["incima"] = "Richiesta non valida";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }

        // inizio della transaction
        $this->db->begin();

        $form = new ExhibitorsForm;
        $exhibitors = new Exhibitors();
        $data = $this->request->getPost();

        \PhalconDebug::info("passo prima di validare i dati del form");

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

        // verifico che uno dei due campi tra partita iva e codicefiscale siano stati compilati
        $piva = $this->request->getPost('piva');
        $codfisc = $this->request->getPost('codfisc');
        \PhalconDebug::info('piva: '.$piva.' codfisc: '.$codfisc);
        if(empty($piva) && empty($codfisc)){
            $ris["piva"] = "&Egrave; obbligatorio compilare almeno uno dei due campi Partita iva e codice Fiscale";
            $ris["codfisc"] = "&Egrave; obbligatorio compilare almeno uno dei due campi Partita iva e codice Fiscale";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        if(!empty($codfisc)){
            // proviamo a verificare la validità formale del codice fiscale con regex presa da internet? boh
            if(preg_match("/^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/i",$codfisc)==false){
                $ris["codfisc"] = "Il formato del codice fiscale non è valido";
                $ris["status"] = "KO";
                return $this->response->setJsonContent($ris);
            }

        }

        // verifico che almeno uno stand sia stato selezionato
        $arrayservizi = $this->request->getPost('services');
        $arrayserviziselezionati = array();
        foreach($arrayservizi as $idservizio => $quantita){
            if($quantita > 0) $arrayserviziselezionati[] = $idservizio;
        }
        \PhalconDebug::info('array dei seervizi ricevuto dal form',$arrayservizi);
        $services = Services::find("events_id = ".$this->evento->id." AND tipologia=2");
        $check = false;
        foreach($services as $servizio){
            \PhalconDebug::info('verifico se il servizio '.$servizio->id.' rientra tra questi:',$arrayserviziselezionati);
            if(in_array($servizio->id,$arrayserviziselezionati)){
                $check = true;
                break;
            }
        }
        if($check == false && $this->request->getPost('standpersonalizzato','trim')==''){
            $ris["stand"] = "Selezionare almeno uno stand o riempire il campo per uno stand personalizzato";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        // fine verifica che almeno uno stand sia stato selezionato

        \PhalconDebug::info("passo prima del save");
        \PhalconDebug::info($data);

        if ($exhibitors->save() === false) {

            \PhalconDebug::info("errore nel salvataggio exhibitors");
            $i = 0;
            foreach ($exhibitors->getMessages() as $message) {
                $this->flash->error("dal model: ".$message);
                $ris['incima-'+$i] = $message;
                $i++;
            }
            
            $this->db->rollback();

            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);

        }

        \PhalconDebug::info(" save del model reservations..");
        $reservations = new Reservations();
        $reservations->exhibitors_id = $exhibitors->id;
        $reservations->events_id = $this->evento->id;
        $reservations->areas_id = $this->request->getPost('areas_id','int!');
        $reservations->codicestand = $this->request->getPost('codicestand','alphanum');
        $reservations->standpersonalizzato = $this->request->getPost('standpersonalizzato','string');
        

        if ($reservations->save() === false) {

            $i=0;
            foreach ($reservations->getMessages() as $message) {
                $this->flash->error("dal model: ".$message);
                $ris['incima-'+$i] = $message;
                $i++;
            }

            $this->db->rollback();

            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
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

                    $i=0;
                    foreach ($reservationservices->getMessages() as $message) {
                        $this->flash->error("dal model: ".$message);
                        $ris['incima-'+$i] = $message;
                        $i++;
                    }

                    $this->db->rollback();

                    $ris["status"] = "KO";
                    return $this->response->setJsonContent($ris);
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

            $i=0;
            foreach ($logstatireservations->getMessages() as $message) {
                $this->flash->error("dal model: ".$message);
                $ris['incima-'+$i] = $message;
                $i++;
            }

            $this->db->rollback();

            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        \PhalconDebug::info(" tutto ok faccio la commit ");
        // Commit the transaction
        $this->db->commit();

        $form->clear();

        $ris["status"] = "OK";
        $ris["modale"] = "I Dati della domanda di partecipazione dell'espositore sono stati inseriti con successo!";
        return $this->response->setJsonContent($ris);

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
                    "controller" => "reservations",
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
                    "controller" => "reservations",
                    "action"     => "index",
                ]
            );
        }

        $this->flash->success("Espositore cancellato definitivamente!");

            return $this->dispatcher->forward(
                [
                    "controller" => "reservations",
                    "action"     => "index",
                ]
            );
    }

    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $this->view->province = Province::find();
            $this->assets->addCss('css/style.css');
            $this->assets->addJs('js/exhibitors-edit.js');
            $exhibitors = Exhibitors::findFirstById($id);
            $this->view->exhibitor = $exhibitors;
            if (!$this->view->exhibitor) {
                $this->flash->error("L'Espositore non è stato trovato");

                return $this->dispatcher->forward(
                    [
                        "controller" => "reservations",
                        "action"     => "index",
                    ]
                );
            }

        }

    }
    
    public function saveAction()
    {
        if (!$this->request->isAjax()) {
            $ris["incima"] = "Richiesta non valida";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }

        // inizio della transaction
        $this->db->begin();

        $form = new AnagraficaForm;
        $data = $this->request->getPost();
        $id = $this->request->getPost("id", "int");
        $exhibitors = Exhibitors::findFirstById($id);

        if (!$exhibitors) {
            $ris["incima"] = "L'Espositore non esiste";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }

        \PhalconDebug::info("passo prima di validare i dati del form");

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

        // verifico che uno dei due campi tra partita iva e codicefiscale siano stati compilati
        $piva = $this->request->getPost('piva');
        $codfisc = $this->request->getPost('codfisc');
        if(empty($piva) && empty($codfisc)){
            $ris["piva"] = "&Egrave; obbligatorio compilare almeno uno dei due campi Partita iva e codice Fiscale";
            $ris["codfisc"] = "&Egrave; obbligatorio compilare almeno uno dei due campi Partita iva e codice Fiscale";
            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);
        }
        if(!empty($codfisc)){
            // proviamo a verificare la validità formale del codice fiscale con regex presa da internet? boh
            if(preg_match("/^[A-Z]{6}\d{2}[A-Z]\d{2}[A-Z]\d{3}[A-Z]$/i",$codfisc)==false){
                $ris["codfisc"] = "Il formato del codice fiscale non è valido";
                $ris["status"] = "KO";
                return $this->response->setJsonContent($ris);
            }

        }

        if ($exhibitors->save() === false) {

            \PhalconDebug::info("errore nel salvataggio exhibitors");
            $i = 0;
            foreach ($exhibitors->getMessages() as $message) {
                $this->flash->error("dal model: ".$message);
                $ris['incima-'+$i] = $message;
                $i++;
            }
            
            $this->db->rollback();

            $ris["status"] = "KO";
            return $this->response->setJsonContent($ris);

        }

        \PhalconDebug::info(" tutto ok faccio la commit ");
        // Commit the transaction
        $this->db->commit();

        $form->clear();

        $ris["status"] = "OK";
        $ris["modale"] = "I Dati anagrafici dell'espositore sono stati modificati con successo!";
        return $this->response->setJsonContent($ris);

    }    

}


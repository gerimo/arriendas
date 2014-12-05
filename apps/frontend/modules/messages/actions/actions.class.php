<?php

/**
 * messages actions.
 *
 * @package    CarSharing
 * @subpackage messages
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class messagesActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function formatearHoraChilena($fecha) {
        $horaChilena = strftime("%Y-%m-%d %H:%M:%S", strtotime('-4 hours', strtotime($fecha)));
        return $horaChilena;
    }

    public function executeGuardarMensajeNuevoAjax(sfWebRequest $request) {

        $idConversacion = $request->getPostParameter('idConversacion');
        $idUserFrom = $request->getPostParameter('idUserFrom');
        $idUserTo = $request->getPostParameter('idUserTo');
        $mensajeNuevo = $request->getPostParameter('mensajeNuevo');
        $myId = $this->getUser()->getAttribute("userid"); //id del que envía el mensaje
        $horaFechaActual = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));

        $claseUsuario = Doctrine_Core::getTable('user')->findOneById($myId);
//        $looking_user_from = 1;
//        if ($claseUsuario->getBlocked() == 1) {
//            /* los usuarios blockeados envian mensajes, pero no son vistos por el destinatario */
//            $looking_user_from = 0;
//        }

        //validar, que los mensajes no respondidos (mensajes_noRespondidos), sean mas actuales que el ultimo respondido por la contraparte (mensajes_totalesContraparte)
        //si son mas actuales, se ejecuta el codigo, sino.. no se hace nada
        $mensajes_totalesContraparte = Doctrine_Core::getTable("Message")->findTodosLosMensajes_contraparte($this->getUser()->getAttribute("userid"), $idConversacion);
        $mensajes_noRespondidos = Doctrine_Core::getTable("Message")->findMensajesNoRespondidos_contraparte($this->getUser()->getAttribute("userid"), $idConversacion);

        //Caso que el from sea from y el to sea to (valga la redundancia)
        $q = "SELECT * FROM conversation WHERE id=" . $idConversacion . " and user_from_id=" . $idUserFrom . " and user_to_id=" . $idUserTo;
        $query = Doctrine_Query::create()->query($q);
        $conversacion = $query->toArray();
        if ($conversacion) {
            //echo "Existe si conversacion";
            $mess = new Message();
            $mess->setBody($mensajeNuevo);
            $mess->setDate($horaFechaActual);
            $mess->setConversationId($idConversacion);
            $mess->setUserId($myId);
            
            /* default visibility */
            $looking_user_from = 1;
            $looking_user_to = 1;
            
            /* set visibility */
            if ($conversacion[0]["user_from_id"] == $claseUsuario->getId() && $claseUsuario->getBlocked() == 1) {
                $looking_user_to = 0;
            }
            if ($conversacion[0]["user_to_id"] == $claseUsuario->getId() && $claseUsuario->getBlocked() == 1) {
                $looking_user_from = 0;
            }
            
            $mess->setLookingUserFrom($looking_user_from);
            $mess->setLookingUserTo($looking_user_to);
            
            
            $mess->save();
            if ($mensajes_totalesContraparte[0]->getId() && $mensajes_noRespondidos[0]->getId()) {
                if ($mensajes_totalesContraparte[count($mensajes_totalesContraparte) - 1]->getId() <= $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->getId()) {
                    //se considera que se está respondiendo por el ultimo mensaje recibido
                    $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->setAnswered(1);
                    $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->setDateAnswered($horaFechaActual);
                    $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->save();
                }
            }
        } else {
            $q = "SELECT * FROM conversation WHERE id=" . $idConversacion . " and user_to_id=" . $idUserFrom . " and user_from_id=" . $idUserTo;
            $query = Doctrine_Query::create()->query($q);
            $conversacion2 = $query->toArray();
            if ($conversacion2) {
                //echo "Existe si conversacion la conversacion, pero con usuarios cambiados";
                $mess = new Message();
                $mess->setBody($mensajeNuevo);
                $mess->setDate($horaFechaActual);
                $mess->setConversationId($idConversacion);
                $mess->setUserId($myId);
                
                /* default visibility */
                $looking_user_from = 1;
                $looking_user_to = 1;

                /* set visibility */
                if ($conversacion2[0]["user_from_id"] == $claseUsuario->getId() && $claseUsuario->getBlocked() == 1) {
                    $looking_user_to = 0;
                }
                if ($conversacion2[0]["user_to_id"] == $claseUsuario->getId() && $claseUsuario->getBlocked() == 1) {
                    $looking_user_from = 0;
                }

                $mess->setLookingUserFrom($looking_user_from);
                $mess->setLookingUserTo($looking_user_to);
                
                
                $mess->save();

                if ($mensajes_totalesContraparte[0]->getId() && $mensajes_noRespondidos[0]->getId()) {
                    if ($mensajes_totalesContraparte[count($mensajes_totalesContraparte) - 1]->getId() <= $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->getId()) {
                        //se considera que se está respondiendo por el ultimo mensaje recibido
                        $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->setAnswered(1);
                        $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->setDateAnswered($horaFechaActual);
                        $mensajes_noRespondidos[count($mensajes_noRespondidos) - 1]->save();
                    }
                }
            } else {
                //echo "No existe conversacion"; Se crea una conversacion nueva
                $conv = new Conversation();
                $conv->setUserFromId($idUserFrom);
                $conv->setUserToId($idUserTo);
                $conv->setStart($horaFechaActual);
                $conv->save();

                $mess = new Message();
                $mess->setBody($mensajeNuevo);
                $mess->setDate($horaFechaActual);
                $mess->setConversationId($conv->getId()); //Id nueva creada por el servidor
                $mess->setUserId($myId);
                
                /* default visibility */
                $looking_user_to = 1;

                /* set visibility */
                if ($claseUsuario->getBlocked() == 1) {
                    $looking_user_to = 0;
                }
                $mess->setLookingUserTo($looking_user_to);
                
                $mess->save();
            }
        }

        //enviar mail a $idUserTo, con el contenido $mensajeNuevo
        //obtener nombre from, nombre to, mail to
        $userFrom = Doctrine_Core::getTable('user')->findOneById($idUserFrom);
        $userTo = Doctrine_Core::getTable('user')->findOneById($idUserTo);

        require sfConfig::get('sf_app_lib_dir') . "/mail/mail.php";
        
        /* el mensaje se envia por mail solo a usuarios no bloqueados */
        if ($claseUsuario->getBlocked() != 1) {
            $mail = new Email();
            $mail->setSubject('Mensaje nuevo');
            $mail->setBody("<p>Hola $userTo:</p><p>$userFrom te ha enviado un mensaje:</p><p>\"$mensajeNuevo\"</p><p>Para contestarlo ingresa <a href='http://www.arriendas.cl/messages/inbox'>aquí</a></p>");
            $mail->setTo($userTo->getEmail());
            $mail->setFrom($userFrom->getEmail());
            $mail->submit();
        }
        

        //Copia de mensaje a Germán
        $emailUserFrom = $userFrom->getEmail();
        $emailUserTo = $userTo->getEmail();
        $mail = new Email();
        $mail->setSubject('Nuevo mensaje entre usuarios');
        $mail->setBody("<p>Enviado por: <b>$userFrom</b> | Recibido por: <b>$userTo</b></p><p>\"$mensajeNuevo\"</p><br><p>Contactar a <b>$userFrom</b>: 1) Desde <a href='http://www.arriendas.cl/messages/new/id/$idUserFrom'>Arriendas.cl</a> | 2) Correo personal: $emailUserFrom</p><p>Contactar a <b>$userTo</b>: 1) Desde <a href='http://www.arriendas.cl/messages/new/id/$idUserTo'>Arriendas.cl</a> | 2) Correo personal: $emailUserTo</p>");
        $mail->setTo('german@arriendas.cl');
        $mail->submit();



        if ($claseUsuario->getFacebookId() != null && $claseUsuario->getFacebookId() != "") {
            $urlFoto = $claseUsuario->getPictureFile();
        } else {
            if ($claseUsuario->getPictureFile() != "") {
                $urlPicture = $claseUsuario->getPictureFile();
                $urlPicture = explode("/", $urlPicture);
                $urlPicture = $urlPicture[count($urlPicture) - 1];
                $urlFoto = "http://www.arriendas.cl/images/users/" . $urlPicture;
            } else {
                $urlFoto = "http://www.arriendas.cl/images/img_calificaciones/tmp_user_foto.jpg";
            }
        }


        echo "<div class='newMensaje'>
            <div class='usuarioIzq'>
              <div class='msg_user_frame'><img src=" . $urlFoto . " class='imgFoto'></div>
              <div class='msg_user_nombre'>Tú</div>
            </div>
            <div class='puntaBlanca'></div> 
            <div class='marcoTextoIzq'>
              <div class='texto'>
                <div class='msg'><p>" . $mensajeNuevo . "</p></div>
                <div class='horaFecha'>" . $horaFechaActual . "</div>
              </div>
            </div>
          </div>";
        die();
    }

    public function verificarUsuarioFrom($idConversacion) {
        $idUsuario = $this->getUser()->getAttribute("userid");
        $q = "SELECT * FROM conversation WHERE id=" . $idConversacion . " and user_from_id=" . $idUsuario;
        $query = Doctrine_Query::create()->query($q);
        $resultado_userFrom = $query->toArray();
        if ($resultado_userFrom)
            return true;
        else
            return false;
    }

    public function obtenerMisConversaciones() {
        $tipo = "";
        $ListaDeConversaciones = Doctrine_Core::getTable("Conversation")->findConversation($this->getUser()->getAttribute("userid"));


        for ($k = 0; $k < count($ListaDeConversaciones); $k++) {
            $idConversacion = $ListaDeConversaciones[$k]->getId();
            $usuarioEsFrom = $this->verificarUsuarioFrom($idConversacion);
            if ($usuarioEsFrom) {//rescate de mensajes para usuarios como FROM
                $tipo = "looking_user_from";
                $mensajes = Doctrine_Core::getTable("Message")->findMensajesNoLeidos($this->getUser()->getAttribute("userid"), $tipo);
                $mensajes_leidos = Doctrine_Core::getTable("Message")->findMensajesLeidos($this->getUser()->getAttribute("userid"), $tipo);
                $mensajes_enviados = Doctrine_Core::getTable("Message")->findMensajesEnviados($this->getUser()->getAttribute("userid"), $tipo);
            } else {
                $tipo = "looking_user_to";
                $mensajes = Doctrine_Core::getTable("Message")->findMensajesNoLeidos($this->getUser()->getAttribute("userid"), $tipo);
                $mensajes_leidos = Doctrine_Core::getTable("Message")->findMensajesLeidos($this->getUser()->getAttribute("userid"), $tipo);
                $mensajes_enviados = Doctrine_Core::getTable("Message")->findMensajesEnviados($this->getUser()->getAttribute("userid"), $tipo);
            }
            //var_dump(count($mensajes));die();

            $MisConversaciones[$k]['idConversacion'] = $idConversacion;
            $MisConversaciones[$k]['idUser_from'] = $ListaDeConversaciones[$k]->getUserFromId();
            $MisConversaciones[$k]['idUser_to'] = $ListaDeConversaciones[$k]->getUserToId();
            $a = 0;
            $c = 0;
            $bandera = false;
            for ($j = 0; $j < count($mensajes); $j++) {//No leidos
                if ($mensajes[$j]->getConversationId() == $idConversacion) {
                    $MisConversaciones[$k][$a + $c]['tipoMensaje'] = "NoLeido";
                    $MisConversaciones[$k][$a + $c]['idMensaje'] = $mensajes[$j]->getId();
                    $MisConversaciones[$k][$a + $c]['idConversacion'] = $idConversacion;
                    $MisConversaciones[$k][$a + $c]['bodyMensaje'] = $mensajes[$j]->getBody();
                    $MisConversaciones[$k][$a + $c]['dateMensaje'] = $mensajes[$j]->getDate();
                    $MisConversaciones[$k][$a + $c]['userMensaje'] = $mensajes[$j]->getUserId();
                    $c++;
                    $bandera = true;
                }
            }
            if ($bandera) {
                //$a++;
                $bandera = false;
            }
            $r = $c;
            for ($l = 0; $l < count($mensajes_leidos); $l++) {//Leidos
                if ($mensajes_leidos[$l]->getConversationId() == $idConversacion) {
                    $MisConversaciones[$k][$a + $r]['tipoMensaje'] = "Leido";
                    $MisConversaciones[$k][$a + $r]['idMensaje'] = $mensajes_leidos[$l]->getId();
                    $MisConversaciones[$k][$a + $r]['idConversacion'] = $idConversacion;
                    $MisConversaciones[$k][$a + $r]['bodyMensaje'] = $mensajes_leidos[$l]->getBody();
                    $MisConversaciones[$k][$a + $r]['dateMensaje'] = $mensajes_leidos[$l]->getDate();
                    $MisConversaciones[$k][$a + $r]['userMensaje'] = $mensajes_leidos[$l]->getUserId();
                    $r++;
                    $bandera = true;
                }
            }
            if ($bandera) {
                //$a++;
                $bandera = false;
            }
            $g = $r;
            for ($p = 0; $p < count($mensajes_enviados); $p++) {//Enviados
                if ($mensajes_enviados[$p]->getConversationId() == $idConversacion) {
                    $MisConversaciones[$k][$a + $g]['tipoMensaje'] = "Enviado";
                    $MisConversaciones[$k][$a + $g]['idMensaje'] = $mensajes_enviados[$p]->getId();
                    $MisConversaciones[$k][$a + $g]['idConversacion'] = $idConversacion;
                    $MisConversaciones[$k][$a + $g]['bodyMensaje'] = $mensajes_enviados[$p]->getBody();
                    $MisConversaciones[$k][$a + $g]['dateMensaje'] = $mensajes_enviados[$p]->getDate();
                    $MisConversaciones[$k][$a + $g]['userMensaje'] = $mensajes_enviados[$p]->getUserId();
                    $g++;
                }
            }
        }//fin for padre
        //Ordenando los nodos internos
        for ($i = 0; $i < count($MisConversaciones); $i++) {
            $subArray = $MisConversaciones[$i];
            if (count($subArray) > 4) {
                $subArrayAux = array();
                $d = 0;
                $subArrayAux['idConversacion'] = $subArray['idConversacion'];
                $subArrayAux['idUser_from'] = $subArray['idUser_from'];
                $subArrayAux['idUser_to'] = $subArray['idUser_to'];
                unset($subArray['idConversacion']);
                unset($subArray['idUser_from']);
                unset($subArray['idUser_to']);
                do {
                    $menorSub = 0;
                    for ($f = 1; $f < count($subArray); $f++) {
                        if ($subArray[$f]['dateMensaje'] < $subArray[$menorSub]['dateMensaje']) {//si el 0 es mayor a 1
                            $menorSub = $f;
                        }
                    }
                    $subArrayAux[$d] = $subArray[$menorSub];
                    //elimina la posicion del array $comentarios
                    unset($subArray[$menorSub]);
                    $subArray = array_values($subArray);
                    $d++;
                } while ($subArray);
                $MisConversaciones[$i] = $subArrayAux;
            }
        }//fin for
        return $MisConversaciones;
    }

    public function executeEliminarConversation(sfWebRequest $request) {
        $idUsuario = $this->getUser()->getAttribute("userid");
        $idConversacion = $request->getPostParameter('opcionDelete');
        $usurio = "";
        $tipo = "";
        $usuarioEsFrom = $this->verificarUsuarioFrom($idConversacion);
        if ($usuarioEsFrom) {//Si se es usuario From
            $usuario = "user_from_id";
            $tipo = "looking_user_from";
            $mensajes = Doctrine_Core::getTable("Message")->findTodosMensajesDeLaConversacion($this->getUser()->getAttribute("userid"), $idConversacion, $usuario, $tipo);
            for ($i = 0; $i < count($mensajes); $i++) {
                $mensajes[$i]->setLookingUserFrom(0);
                $mensajes[$i]->save();
            }
        } else {//Sino, si se es usuario To
            $usuario = "user_to_id";
            $tipo = "looking_user_to";
            $mensajes = Doctrine_Core::getTable("Message")->findTodosMensajesDeLaConversacion($this->getUser()->getAttribute("userid"), $idConversacion, $usuario, $tipo);
            for ($i = 0; $i < count($mensajes); $i++) {
                $mensajes[$i]->setLookingUserTo(0);
                $mensajes[$i]->save();
            }
        }
        $this->redirect('messages/inbox');
    }

    public function executeInbox() {
        $this->user_id = $this->getUser()->getAttribute("userid");
        $ListaDeConversaciones = Doctrine_Core::getTable("Conversation")->findConversation($this->getUser()->getAttribute("userid"));

        $MisConversaciones = $this->obtenerMisConversaciones();

        $cantidad = count($MisConversaciones);

        for ($i = $cantidad - 1; $i >= 0; $i--) {//Quitando las conversaciones que no tienen mensajes
            if (count($MisConversaciones[$i]) == 3) {
                unset($MisConversaciones[$i]);
                $MisConversaciones = array_values($MisConversaciones);
            }
        }
        $MisConversacionesAux = $MisConversaciones;
        if ($MisConversacionesAux) {
            //ordenando los utimos mensajes por fecha
            $conversacionesAux = array(); //declarando el array como vacio
            $j = 0;
            do {//hacer-mientras exista el array de $MisConversacionesAux
                $menor = 0; //posicion menor
                for ($i = 0; $i < count($MisConversacionesAux); $i++) {//comenzamos del 0, evaluando        
                    $cantidadAux1 = count($MisConversacionesAux[$menor]) - 4; //ultima posicion $i-1
                    $cantidadAux2 = count($MisConversacionesAux[$i]) - 4; //ultima posicion $i
                    if ($MisConversacionesAux[$i][$cantidadAux2]['dateMensaje'] < $MisConversacionesAux[$menor][$cantidadAux1]['dateMensaje']) {
                        $menor = $i;
                        $cantidadAux1 = count($MisConversacionesAux[$i]) - 4;
                    }
                }
                $conversacionesAux[$j] = $MisConversacionesAux[$menor][$cantidadAux1];
                unset($MisConversacionesAux[$menor]);
                $MisConversacionesAux = array_values($MisConversacionesAux);
                $j++;
            } while ($MisConversacionesAux);
            $conversacionesOrdDes = array();
            for ($y = 0; $y < count($conversacionesAux); $y++) { //Ordeno del mas actual, al mas antiguo
                $conversacionesOrdDes[$y] = $conversacionesAux[(count($conversacionesAux) - 1) - $y];
            }

            for ($d = 0; $d < count($conversacionesOrdDes); $d++) { //Acortando los mensajes a un largo fijo...
                $conversacionesOrdDes[$d]['bodyMensaje'] = $this->recortar_texto($conversacionesOrdDes[$d]['bodyMensaje'], 25);
            }
        } else {
            $MisConversaciones = null;
            $conversacionesOrdDes = null;
        }
        $this->conversaciones = $MisConversaciones; //Todas conversaciones con sus respectivos mensajes
        $this->conversacionesOrdenadas = $conversacionesOrdDes; //Ultimos mensajes de c/conversacion, las conversaciones están ordenadas
        $this->listaConversaciones = $ListaDeConversaciones; //Conversaciones como objetos, contienen los datos de los usuarios, necesarios para su uso
    }

    public function recortar_texto($texto, $limite = 100) {
        $texto = trim($texto);
        $texto = strip_tags($texto);
        $tamano = strlen($texto);
        $resultado = '';
        if ($tamano <= $limite) {
            return $texto;
        } else {
            $texto = substr($texto, 0, $limite);
            $palabras = explode(' ', $texto);
            $resultado = implode(' ', $palabras);
            $resultado .= '...';
        }
        return $resultado;
    }

    public function setearReceived_aTodosLosMensajes($idConversacion) {
        $mensajes = Doctrine_Core::getTable("Message")->findOpenToUserId_byidConversation($this->getUser()->getAttribute("userid"), $idConversacion);
        for ($i = 0; $i < count($mensajes); $i++) {
            $mensajes[$i]->setReceived(1);
            $mensajes[$i]->save();
        }
    }

    public function executeConversation(sfWebRequest $request) {

        $this->user_id = $this->getUser()->getAttribute("userid");
        $idConversacion = $request->getParameter("id");
        
        $objetoConversacion = Doctrine_Core::getTable("Conversation")->findConversationWithId($this->getUser()->getAttribute("userid"), $idConversacion);
        
        // muestro precargado último mensaje enviado 
        // (sólo para arrendatarios y sólo si el último mensaje no pertenece a la conversación actual)
        $user = Doctrine_Core::getTable('user')->find($this->user_id);
        $message = Doctrine_Core::getTable("Message")->lastMessageSentByUserId($this->user_id);
        if(!$user->isPropietario()
                && !in_array($message->getId(), $objetoConversacion[0]->getMessage()->getPrimaryKeys())){            
            $this->comentarios = $message->getBody();
        }else{
            $this->comentarios = $request->getParameter("comentarios");
        }        

        if ($idConversacion) {//si la id existe
            $this->setearReceived_aTodosLosMensajes($idConversacion);

            $MisConversaciones = $this->obtenerMisConversaciones();
            $cantidadMisConversaciones = count($MisConversaciones);
            for ($i = 0; $i < $cantidadMisConversaciones; $i++) {
                if ($MisConversaciones[$i]['idConversacion'] == $idConversacion) {
                    $conversacion_conSusMensajes = $MisConversaciones[$i];
                }
            }
            $conversacionesOrdDes = array();
            $conversacionesOrdDes['idConversacion'] = $conversacion_conSusMensajes['idConversacion'];
            $conversacionesOrdDes['idUser_from'] = $conversacion_conSusMensajes['idUser_from'];
            $conversacionesOrdDes['idUser_to'] = $conversacion_conSusMensajes['idUser_to'];
            $cant = count($conversacion_conSusMensajes) - 3;
            for ($y = 0; $y < $cant; $y++) { //Ordeno del mas actual, al mas antiguo
                $conversacionesOrdDes[$y] = $conversacion_conSusMensajes[$cant - 1 - $y];
            }
            $this->objetoConversacion = $objetoConversacion;
            $this->conversacion = $conversacionesOrdDes;
        } else {//si no existe la id
            echo "Error al intentar mostrar conversacion.";
            //verificar efectivamente si la id existe
            //id no encontrada, crear una nueva
        }
    }

    public function executeNew(sfWebRequest $request) {
        $user_from = $this->getUser()->getAttribute("userid");
        $user_to = Doctrine_Core::getTable('user')->find($this->getRequestParameter("id"));
        $comentarios = $this->getRequestParameter("comentarios");

        $idConversacionNew = null;
        $q = "SELECT id FROM conversation WHERE user_to_id=" . $this->getRequestParameter("id") . " and user_from_id=" . $user_from;
        $query = Doctrine_Query::create()->query($q);
        $conversacion = $query->toArray();
        if ($conversacion) {//Si existe conversacion como yo como from y la otra persona como to
            $idConversacionNew = $conversacion[0]['id'];
        } else {//Si no existe, busco una conversacion que esté yo como to y la otra persona como from
            $q = "SELECT id FROM conversation WHERE user_to_id=" . $user_from . " and user_from_id=" . $this->getRequestParameter("id");
            $query = Doctrine_Query::create()->query($q);
            $conversacion2 = $query->toArray();
            if ($conversacion2) {//si la encuentro
                $idConversacionNew = $conversacion2[0]['id'];
            } else {//Creando una nueva conversación
                $horaFechaActual = $this->formatearHoraChilena(strftime("%Y-%m-%d %H:%M:%S"));
                $conv = new Conversation();
                $conv->setUserFromId($user_from);
                $conv->setUserToId($this->getRequestParameter("id"));
                $conv->setStart($horaFechaActual);
                $conv->save();
                $idConversacionNew = $conv->getId();
            }
        }
        $this->redirect('messages/conversation?id=' . $idConversacionNew . '&comentarios=' . $comentarios);
    }

    public function sendNotificationConversation($idconversation, $msg, $userid) {

        try {

            $receptor = Doctrine_Core::getTable("Message")->getUserConversation($idconversation, $userid);
            $user = Doctrine_Core::getTable("User")->find($userid);

            $to = $receptor->getEmail();
            $subject = "Arriendas.cl - Mensaje nuevo";

            // compose headers
            $headers = "From: \"Arriendas Reservas\" <no-reply@arriendas.cl>\n";
            $headers .= "Content-type: text/html\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\n";

            $mail = 'Estimado(a) ' . $receptor->getFirstName() . ' ' . $receptor->getLastName() . ':<br><br>
			
			Le han enviado un nuevo mensaje, de:<br><br>
			
			' . $user->getFirstName() . ' ' . $user->getLastName() . '<br><br>
			
			Mensaje:<br>
			
			' . $msg . '<br><br>
			
			El equipo de Arriendas.cl';

            // send email
            mail($to, $subject, $mail, $headers);
        } catch (Exception $e) {
            die($e);
        }
    }

    public function executeDelete() {

        try {
            $mess = Doctrine_Core::getTable("Message")->find($this->getRequestParameter("id"));
            $con_id = $mess->getConversationId();
            $mess->delete();
            $convs = Doctrine_Core::getTable("Message")->countByConversationId($con_id);
            if ($convs == 0) {
                $con = Doctrine_Core::getTable("Conversation")->find($con_id);
                $con->delete();
            }
            $this->getUser()->setFlash("message_ok", "Su mensaje fue enviado");
        } catch (Exception $e) {
            $this->getUser()->setFlash("message_error", "Su mensaje no pudo ser enviado por el siquiente motivo $e");
        }

        $this->redirect("messages/inbox");
    }

}

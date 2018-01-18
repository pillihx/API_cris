<?php 

namespace App\Controllers;

class RegistroController extends BaseController {

	public function agregar($request, $response) {
		try {
			$n_tarjeta = $request->getParsedBody()['n_tarjeta'];
			$message_response = "";
			//VERIFICAMOS QUE EXISTA Y PERTENEZCA A UN USUARIO LA TARJETA RECIBIDA EN LA TABLA TARJETAS
			$tarjeta = $this->DB->buildSelect("tarjeta",["id","usuario_id"],array("n_tarjeta" => $n_tarjeta));

			if(count($tarjeta) != 0){
				//CONSEGUIMOS AL USUARIO DE ESA TARJETA
				$usuario = $this->DB->buildSelect("usuario",["*"],array("id" => $tarjeta[0]["usuario_id"]));

				$values = array(
					"tarjeta_id" =>  $tarjeta[0]["id"],
					"datetime" => date('Y-m-d H:i:s')
				);
				//VERIFICAMOS SI LO QUE INGRESAREMOS CORRESPONDERÁ A UNA ENTRADA O SALIDA
				$registro = $this->DB->buildSelect("registro",["MAX(datetime) AS datetime"],array("tarjeta_id" => $tarjeta[0]["id"]));
				$registro = $this->DB->buildSelect("registro",["tipo"],array("tarjeta_id" => $tarjeta[0]["id"], "datetime" => $registro[0]["datetime"]));
				//NO EXISTE NINGÚN REGISTRO ANTIGUO
				if(count($registro) == 0){
					$values["tipo"] = 1;//SE SETEA LA ENTRADA
					$message_response = $usuario[0]["nombre"] . " entró con éxito";
				}
				else{
					//VERIFICACIÓN DEL TIPO DE REGISTRO
					if($registro[0]["tipo"] == "1"){ //LA PERSONA HA SALIDO
						$values["tipo"] = 0;
						$message_response = $usuario[0]["nombre"] . " Salio";
					}
					else{ //LA PERSONA HA ENTRADO
						$values["tipo"] = 1;
						$message_response = $usuario[0]["nombre"] . " Entro";
					}
				}
				$result = $this->DB->buildInsert("registro",$values);
				//return $response->withJson(array('status' => $message_response, "datetime" => date('Y-m-d H:i:s')), 200);
				return $response->write($n_tarjeta . " - " . date('Y-m-d H:i:s'));
			}
			else
				return $response->withJson(array('status' => 'El numero de la tarjeta ingresada no pertenece a ningun usuario', "datetime" => date('Y-m-d H:i:s')), 422);

		} catch(\Exception $ex) {
			return $response->withJson(array('error' => $ex->getMessage()), 422);
		}
	}
	public function obtener($request, $response) {
		try {
			$id = $request->getAttribute('id');

			$result = $this->container->db2->buildSelect("buzon",["*"],array("id"=>$id));

			if($result) {
				return $response->withJson(array('status' => 'true', 'result' => $result), 200);
			} else {
				return $response->withJson(array('status' => 'mensaje en el buzon no encontrado'), 422);
			}
		} catch(\Exception $ex) {
			return $response->withJson(array('error' => $ex->getMessage()), 422);
		}
	}
	public function actualizar($request, $response) {
		try{
			$id = $request->getAttribute('id');
			$values = array(
				'notificacion_id' => $request->getParam('notificacion_id'),
				'usuario_id' => $request->getParam('usuario_id'),
				'visto' => $request->getParam('visto'),
				'estado' => $request->getParam('estado')
			);
			
			$result = $this->container->db2->buildUpdate("buzon",$values,array("id"=>$id));

			if($result) {
				return $response->withJson(array('status' => 'Mensaje de buzon actualizado'), 200);
			}else{
				return $response->withJson(array('status' => 'Mensaje no encontrado'), 422);
			}
		} catch(\Exception $ex) {
			return $response->withJson(array('error' => $ex->getMessage()), 422);
		}
	}
	public function eliminar($request, $response) {
		try {
			$values = array(
				'id' => $id
			);

			$result = $this->container->db2->buildDelete("ROW",array("table-name"=>"buzon","where_delete"=>$values));
			
			if($result) {
				return $response->withJson(array('status' => 'Mensaje de buzon eliminado'), 200);
			} else {
				return $response->withJson(array('status' => 'Mensaje de buzon no encontrado'), 422);
			}
		} catch(\Exception $ex) {
			return $response->withJson(array('error' => $ex->getMessage()), 422);
		}
	}
}
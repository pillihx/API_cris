<?php 

namespace App\Controllers;

class UsuarioController extends BaseController {
	public function lista($request, $response) {
		$todos = $this->todosDatos();
		if($todos) {
			$messages_buzon = '';
			foreach($todos as $valor) {
				$messages_buzon .= '<tr>';
				$messages_buzon .= '<td>'.$valor['id'].'</td>';
				$messages_buzon .= '<td>'.$valor['notificacion_id'].'</td>';
				$messages_buzon .= '<td>'.$valor['usuario_id'].'</td>';
				$messages_buzon .= '<td>'.$valor['visto'].'</td>';
				$messages_buzon .= '<td>'.$valor['estado'].'</td>';
				$messages_buzon .= '</tr>';
			}
		}
		return $this->container->renderer->render($response, 'buzon.phtml',
			array(
				'lista_buzon' => $messages_buzon
			)
		);
	}
	public function formulario($request, $response) {
		// TODO: diferenciar si es agregar o modificar, en caso de que venga con ID
		return $this->container->renderer->render($response, 'index.phtml', array());
	}
	public function agregar($request, $response) {
		try {
			$values = array(
				'notificacion_id' => $request->getParam('notificacion_id'),
				'usuario_id' => $request->getParam('usuario_id'),
				'visto' => $request->getParam('visto'),
				'estado' => $request->getParam('estado')
			);
			$result = $this->container->db2->buildInsert("buzon",$values);

			$con = $this->container->db;
			$proyecto_id = $con->lastInsertId();

			$notificacion_id = $con->lastInsertId();

			return $response->withJson(array('status' => 'Mensaje agregado a buzon'), 200);
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
	private function todosDatos() {

		$result = $this->container->db2->buildSelect("buzon", ["*"], "*");

		if($result) {
			return $result;
		} else {
			return false;
		}
	}
	public function todos($request, $response) {
		try {
			$result = $this->todosDatos();

			if($result) {
				return $response->withJson(array('status' => 'true', 'result' => $result), 200);
			} else {
				return $response->withJson(array('status' => 'No hay mensajes en el buzon'), 422);
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
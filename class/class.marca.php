<?php
class marca{
	private $id;
	private $descripcion;
	private $pais;
	private $direccion;
	private $foto;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR MARCA<br><br>";
	}
	
	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->descripcion = NULL;
			$this->pais = NULL;
			$this->direccion = NULL;
			$this->foto = NULL;

			$flag = NULL;
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM marca WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1; //S
            
            if(!($bandera)){
                $mensaje = "tratar de actualizar la marca con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
				echo "<br>REGISTRO A MODIFICAR: <br>";
				/*	echo "<pre>";
						print_r($row);
					echo "</pre>";*/
			
             // ATRIBUTOS DE LA CLASE MARCA   
                $this->descripcion = $row['descripcion'];
                $this->pais = $row['pais'];
                $this->direccion = $row['direccion'];
                $this->foto = $row['foto'];
				
                //$flag = "disabled";
				$flag = "enabled"; //por el flag se puede o no odificar el campo
                $op = "act"; 
            }
	}
        
	if($bandera){

		$html = '
		<form name="Form_marca" method="POST" action="marca.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
			<table border="2" align="center" class="table-sm">
				<tr>
					<th colspan="2">DATOS MARCA</th>
				</tr>
				<tr>
					<td>Descripcion:</td>
					<td><input type="text" size="6" name="descripcion" value="' . $this->descripcion . '"></td>
				</tr>
				<tr>
					<td>Pais:</td>
					<td><input type="text" size="6" name="pais" value="' . $this->pais . '"></td>
				</tr>
				<tr>
					<td>Direccion:</td>
					<td><input type="text" size="6" name="direccion" value="' . $this->direccion . '"></td>
				</tr>	
				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" ' . $flag . '></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR" class="Boton"></th>
				</tr>
				<tr>
					<th colspan="2" style="background-color:black;"><a href="marca.php" class="Boton" id="regresar">Regresar</a></th>
				</tr>
			</table>';
		return $html;
		}
	}
	
	public function get_list(){
		$d_new = "new/0";                           //Línea agregada
        $d_new_final = base64_encode($d_new);       //Línea agregada
		
		$html = ' 
		<table border="1" align="center" class="table-sm table-dark">
			<tr>
				<th colspan="8">Lista de marcas</th>
			</tr>
			<tr>
				<th colspan="8"><a href="marca.php?d=' . $d_new_final . '" class="Boton" id="new">Nuevo</a></th>
			</tr>
			<tr>
				<th>Descripcion</th>
				<th>Pais</th>
				<th colspan="3">Acciones</th>
			</tr>';
		$sql = "SELECT id, descripcion, pais FROM marca";
		$res = $this->con->query($sql);
				
		// VERIFICA si existe TUPLAS EN EJECUCION DEL Query
		$num = $res->num_rows;
        if($num != 0){
		
		    while($row = $res->fetch_assoc()){
			/*
				echo "<br>VARIALE ROW ...... <br>";
				echo "<pre>";
						print_r($row);
				echo "</pre>";
			*/
		    		
				// URL PARA BORRAR
				$d_del = "del/" . $row['id'];
				$d_del_final = base64_encode($d_del);
				
				// URL PARA ACTUALIZAR
				$d_act = "act/" . $row['id'];
				$d_act_final = base64_encode($d_act);
				
				// URL PARA EL DETALLE
				$d_det = "det/" . $row['id'];
				$d_det_final = base64_encode($d_det);	
				
				$html .= '
					<tr>
						<td>' . $row['descripcion'] . '</td>
						<td>' . $row['pais'] . '</td>
						<td><a href="marca.php?d=' . $d_del_final . '" class="Boton" id="delete">Borrar</a></td>
						<td><a href="marca.php?d=' . $d_act_final . '" class="Boton" id="update">Actualizar</a></td>
						<td><a href="marca.php?d=' . $d_det_final . '" class="Boton" id="detail">Detalle</a></td>
					</tr>';
		    }
		}else{
			$mensaje = "Tabla Marca" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		$html .= '</table>';

		$html .= '
		<div>    
			<a href="index.html">
				INICIO
			</a>
  		</div>';
		return $html;
	}
	
//********************************************************************************************************
	/*
	 $tabla es la tabla de la base de datos
	 $valor es el nombre del campo que utilizaremos como valor del option
	 $etiqueta es nombre del campo que utilizaremos como etiqueta del option
	 $nombre es el nombre del campo tipo combo box (select)
	 * $defecto es el valor para que cargue el combo por defecto
	 */ 
	 
	 // _get_combo_db("marca","id","descripcion","marca",$this->marca)
	 // _get_combo_db("color","id","descripcion","color", $this->color)
	 
	 /*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		//$num = $res->num_rows;
		
			
		while($row = $res->fetch_assoc()){
		/*
			echo "<br>VARIABLE ROW <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
		*/	
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	//_get_combo_anio("anio",1950,$this->anio)
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_combo_anio($nombre,$anio_inicial,$defecto=NULL){
		$html = '<select name="' . $nombre . '">';
		$anio_actual = date('Y');
		for($i=$anio_inicial;$i<=$anio_actual;$i++){
			$html .= ($defecto == $i)? '<option value="' . $i . '" selected>' . $i . '</option>' . "\n":'<option value="' . $i . '">' . $i . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}
	
	
	//_get_radio($combustibles, "combustible",$this->combustible) 
	/*Aquí se agregó el parámetro:  $defecto*/
	private function _get_radio($arreglo,$nombre,$defecto=NULL){
		$html = '
		<table border=0 align="left" class="table-sm">';
		foreach($arreglo as $etiqueta){
			$html .= '
			<tr>
				<td>' . $etiqueta . '</td>
				<td>';
				$html .= ($defecto == $etiqueta)? '<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '" checked/></td>':'<input type="radio" value="' . $etiqueta . '" name="' . $nombre . '"/></td>';
			
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}

	private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original);
		$numElm = count($tmp);
		$ext = $tmp[$numElm-1];
		$cadena = "";
				for($i=1;$i<=$tamanio;$i++){
					$c = rand(65, 122);
					if(($c >= 91) && ($c <=96)){
						$c = NULL;
							$i--;
					}else{
						$cadena .= chr($c);
					}
				}
	return $cadena . "." . $ext;
	}

//****************************************** NUEVO CODIGO *****************************************

public function save_marca(){
	$this->descripcion=$_POST['descripcion'];
	$this->pais=$_POST['pais'];
	$this->direccion=$_POST['direccion'];
	
	/*echo "<br>FILES<br>";
	echo "<pre>";
		print_r($_FILES);
	echo "</pre>";*/
	$this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
				$path = "images/" . $this->foto;
   
	if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
		$mensaje="Cargar la imagen";
		echo $this->_message_error($mensaje);
	}

	$sql = "INSERT INTO marca
	VALUES (NULL, '$this->descripcion', '$this->pais', '$this->direccion', '$this->foto');";

		if($this->con->query($sql)){
			echo $this->_message_ok("guardo");
		}else{
			echo $this->_message_error("guardar<br>");
		}
}

public function get_detail_marca($id){
		$sql = "SELECT m.descripcion, m.pais, m.direccion, m.foto  FROM marca m WHERE id=$id";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle de la marca con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
				
    }else{ 
		/*
	    echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";*/
	
		$html = '
		<table border="1" align="center" class="table-sm">
			<tr>
				<th colspan="2">DATOS DE LA MARCA</th>
			</tr>
			<tr>
				<td>Marca: </td>
				<td>'. $row['descripcion'] .'</td>
			</tr>
			<tr>
				<td>Pais: </td>
				<td>'. $row['pais'] .'</td>
			</tr>
			<tr>
				<td>Direccion: </td>
				<td>'. $row['direccion'] .'</td>
			</tr>			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
			</tr>	
			<tr>
				<th colspan="2" style="background-color:black;"><a href="marca.php" class="Boton" id="regresar">Regresar</a></th>
			</tr>																						
		</table>';
		
		return $html;
	}	
}

	public function update_marca(){
		/*
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";*/
			
		$id = $_POST['id'];
		//como se va a la marca, se pierde el dato, asi que se vuelve a instanciar y carga el dato de nuevo
            $this->descripcion = $_POST['descripcion']; //Los datos que estan capturados van a cambiar por el nuevo envio
            $this->pais = $_POST['pais']; //de datos, enviados por post
            $this->direccion = $_POST['direccion'];

			$this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
				$path = "images/" . $this->foto;

				if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
					$mensaje="Cargar la imagen";
					echo $this->_message_error($mensaje);
				}
		
		//Query de actualizar en la base de datos
		$sql = "UPDATE marca 
				SET descripcion ='$this->descripcion', 
					pais ='$this->pais',
					direccion ='$this->direccion', 
					foto ='$this->foto'
				WHERE id=$id;";
				//WHERE id=$this->id; (PROBAR)
		
		if($this->con->query($sql)){
			echo $this->_message_ok("actualizo");
		}else{
			echo $this->_message_error("actualizar<br>");
		}
		
	}

	public function delete_marca($id){
	/*
		$mensaje = "PROXIMAMENTE SE ELIMINARA la marca con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
		*/
		
		$sql = "DELETE FROM marca WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}		
	}


	
//***************************************************************************************	

	
//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center" class="table-sm table-dark">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="../marca.php" class="Boton" id="regresar">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
	   $html = '
		<table border="0" align="center" class="table-sm table-dark">
			<tr>
				<th> NO existen registros en la ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
		</table>';
		return $html;
	
	
	}
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center" class="table-sm table-dark">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="marca.php" class="Boton" id="regresar">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************

 
}
?>


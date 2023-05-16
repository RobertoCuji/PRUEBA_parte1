<?php
class vehiculo{
	
	
	private $id;
	private $placa;
	private $marca;
	private $motor;
	private $chasis;
	private $combustible;
	private $anio;
	private $color;
	private $foto;
	private $avaluo;
	private $con;
	
	function __construct($cn){
		$this->con = $cn;
	    //echo "EJECUTANDOSE EL CONSTRUCTOR VEHICULO<br><br>";
	}
	

	public function get_form($id=NULL){
		// Código agregado -- //
	if(($id == NULL) || ($id == 0) ) {
			$this->placa = NULL;
			$this->marca = NULL;
			$this->motor = NULL;
			$this->chasis = NULL;
			$this->combustible = NULL;
			$this->anio = NULL;
			$this->color = NULL;
			$this->foto = NULL;
			$this->avaluo =NULL;
			
			$flag = "enabled";//algoritmo
			$op = "new";
			$bandera = 1;
	}else{
			$sql = "SELECT * FROM vehiculo WHERE id=$id;";
			$res = $this->con->query($sql);
			$row = $res->fetch_assoc();// saca las tuplas de la base de datos
            $num = $res->num_rows;
            $bandera = ($num==0) ? 0 : 1;
            
            if(!($bandera)){// Control de las filas para eliminar
                $mensaje = "tratar de actualizar el vehiculo con id= ".$id . "<br>";
                echo $this->_message_error($mensaje);
				
            }else{                
                
				
				/*echo "<br>REGISTRO A MODIFICAR: <br>";
					echo "<pre>";
						print_r($row);
					echo "</pre>";
			*/
		
             // ATRIBUTOS DE LA CLASE VEHICULO   
                $this->placa = $row['placa'];
                $this->marca = $row['marca'];
                $this->motor = $row['motor'];
                $this->chasis = $row['chasis'];
                $this->combustible = $row['combustible'];
                $this->anio = $row['anio'];
                $this->color = $row['color'];
                $this->foto = $row['foto'];
                $this->avaluo = $row['avaluo'];
				
                //$flag = "disabled";
				$flag = "enabled";
                $op = "update"; 
            }
	}
        
	if($bandera){
    
		$combustibles = ["Gasolina",
						 "Diesel",
						 "Eléctrico"
						 ];
		$html = '
		<form name="Form_vehiculo" method="POST" action="vehiculo.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
			<table border="2" align="center">
				<tr>
					<th colspan="2">DATOS VEHÍCULO</th>
				</tr>
				<tr>
					<td>Placa:</td>
					<td><input type="text" size="6" name="placa" value="' . $this->placa . '"></td>
				</tr>
				<tr>
					<td>Marca:</td>
					<td>' . $this->_get_combo_db("marca","id","descripcion","marca",$this->marca) . '</td>
				</tr>
				<tr>
					<td>Motor:</td>
					<td><input type="text" size="15" name="motor" value="' . $this->motor . '"></td>
				</tr>	
				<tr>
					<td>Chasis:</td>
					<td><input type="text" size="15" name="chasis" value="' . $this->chasis . '"></td>
				</tr>
				<tr>
					<td>Combustible:</td>
					<td>' . $this->_get_radio($combustibles, "combustible",$this->combustible) . '</td>
				</tr>
				<tr>
					<td>Año:</td>
					<td>' . $this->_get_combo_anio("anio",1950,$this->anio) . '</td>
				</tr>
				<tr>
					<td>Color:</td>
					<td>' . $this->_get_combo_db("color","id","descripcion","color", $this->color) . '</td>
				</tr>
				<tr>
					<td>Foto:</td>
					<td><input type="file" name="foto" ' . $flag . '></td>
				</tr>
				<tr>
					<td>Avalúo:</td>
					<td><input type="text" size="8" name="avaluo" value="' . $this->avaluo . '" ' . $flag . '></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" name="Guardar" value="GUARDAR" class="Boton"></th>
				</tr>	
				<tr>
				<th colspan="2" style="background-color:black;"><a href="vehiculo.php" class="Boton" id="regresar">Regresar</a></th>
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
				<th colspan="8">Lista de Vehículos</th>
			</tr>
			<tr>
				<th colspan="8"><a href="vehiculo.php?d=' . $d_new_final . '" class="Boton" id="new">Nuevo</a></th>
			</tr>
			<tr>
				<th>Placa</th>
				<th>Marca</th>
				<th>Color</th>
				<th>Año</th>
				<th>Avalúo</th>
				<th colspan="3">Acciones</th>
				
			</tr>

			';
			
		$sql = "SELECT v.id, v.placa, m.descripcion as marca, c.descripcion as color, v.anio, v.avaluo  
		        FROM vehiculo v, color c, marca m 
				WHERE v.marca=m.id AND v.color=c.id;";	
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
						<td>' . $row['placa'] . '</td>
						<td>' . $row['marca'] . '</td>
						<td>' . $row['color'] . '</td>
						<td>' . $row['anio'] . '</td>
						<td>' . $row['avaluo'] . '</td>
						<td><a href="vehiculo.php?d=' . $d_del_final . ' "class="Boton" id="delete">Borrar</a></td>
						<td><a href="vehiculo.php?d=' . $d_act_final . ' "class="Boton" id="update">Actualizar</a></td>
						<td><a href="vehiculo.php?d=' . $d_det_final . ' "class="Boton" id="detail">Detalle</a></td>
						
					</tr>
					
					'
					;
			 
		    }
		}else{
			$mensaje = "Tabla Vehiculo" . "<br>";
            echo $this->_message_BD_Vacia($mensaje);
			echo "<br><br><br>";
		}
		


		$html .= '
		

				
				</table>';
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
		<table border=0 align="left">';
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
	
	
//****************************************** NUEVO CODIGO *****************************************

public function get_detail_vehiculo($id){
		$sql = "SELECT v.placa, m.descripcion as marca, v.motor, v.chasis, v.combustible, v.anio, c.descripcion as color, v.foto, v.avaluo  
				FROM vehiculo v, color c, marca m 
				WHERE v.id=$id AND v.marca=m.id AND v.color=c.id;";
		$res = $this->con->query($sql);
		$row = $res->fetch_assoc();
		//en el row se guardan las tuplas 
		// VERIFICA SI EXISTE id
		$num = $res->num_rows;
        
	if($num == 0){
        $mensaje = "desplegar el detalle del vehiculo con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
				
    }
	else{ 
	//Muestra la tupla completa con el idseleccionado en el metodo actualizar
	    /*
		echo "<br>TUPLA<br>";
	    echo "<pre>";
				print_r($row);
		echo "</pre>";
		*/
	
		$html = '
		<table border="1" align="center">
			<tr>
				<th colspan="2">DATOS DEL VEHÍCULO</th>
			</tr>
			<tr>
				<td>Placa: </td>
				<td>'. $row['placa'] .'</td>
			</tr>
			<tr>
				<td>Marca: </td>
				<td>'. $row['marca'] .'</td>
			</tr>
			<tr>
				<td>Motor: </td>
				<td>'. $row['motor'] .'</td>
			</tr>
			<tr>
				<td>Chasis: </td>
				<td>'. $row['chasis'] .'</td>
			</tr>
			<tr>
				<td>Combustible: </td>
				<td>'. $row['combustible'] .'</td>
			</tr>
			<tr>
				<td>Anio: </td>
				<td>'. $row['anio'] .'</td>
			</tr>
			<tr>
				<td>Color: </td>
				<td>'. $row['color'] .'</td>
			</tr>
			<tr>
				<td>Avalúo: </td>
				<th>$'. $row['avaluo'] .' USD</th>
			</tr>
			<tr>
				<td>Valor Matrícula: </td>
				<th>$'. $this->_calculo_matricula($row['avaluo']) .' USD</th>
			</tr>			
			<tr>
				<th colspan="2"><img src="images/' . $row['foto'] . '" width="300px"/></th>
			</tr>	
			<tr>
				<th colspan="2"><a href="vehiculo.php">Regresar</a></th>
			</tr>																						
		</table>';
		
		return $html;
	}	
	
}
//*****************************************************FUNION ELIMINAR****************************************************************************************

	public function delete_vehiculo($id){
/*		
		$mensaje = "PROXIMAMENTE SE ELIMINARA el vehiculo con id= ".$id . "<br>";
        echo $this->_message_error($mensaje);
*/		
		
	   
		$sql = "DELETE FROM vehiculo WHERE id=$id;";
		if($this->con->query($sql)){
			echo $this->_message_ok("eliminó");
		}else{
			echo $this->_message_error("eliminar<br>");
		}
    		
	}


//**************************************************FUNCION ACTUALIZAR**************************************************************************
	public function update_vehiculo(){
		/*
		echo "<br>PETICION POST <br>";
		echo "<pre>";
			print_r($_POST);
		echo "</pre>";*/
			
			$id = $_POST['id'];
		
		// ATRIBUTOS DE LA CLASE VEHICULO   
				//$this->id = $id;
                $this->placa = $_POST['placa'];
                $this->marca = $_POST['marca'];
                $this->motor = $_POST['motor'];
                $this->chasis = $_POST['chasis'];
                $this->combustible = $_POST['combustible'];
                $this->anio = $_POST['anio'];
                $this->color = $_POST['color'];
                $this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
			$path = "images/" . $this->foto;
   
			if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
				$mensaje="Cargar la imagen";
				echo $this->_message_error($mensaje);
			}
   
                $this->avaluo =  $_POST['avaluo'];
				
				
				
				
		$sql = "UPDATE vehiculo SET placa ='$this->placa',
									marca =$this->marca,
									motor ='$this->motor', 
									chasis ='$this->chasis',
									combustible ='$this->combustible',
									anio ='$this->anio', 
									color =$this->color,
									foto ='$this->foto',
									avaluo =$this->avaluo
				WHERE id=$id;";
		//echo $sql;	
		$bandera = $this->con->query($sql);
		if($bandera==NULL){
			echo "<br>la VARIABLE bandera NULL<br>";
		}else{
			/*echo "<br>VARIABLE bandera <br>";
				echo $bandera;
				echo "<br><br>";*/
		}
		
		if($this->con->query($sql)){
			echo $this->_message_ok("actualizo");
		}else{
			echo $this->_message_error("actualizar<br>");
		}
		
	}

//**********************************************FUNCION SAVE VEHICULO*********************************************** */

public function save_vehiculo(){
			$this->placa = $_POST['placa'];
			$this->marca = $_POST['marca'];
			$this->motor = $_POST['motor'];
			$this->chasis = $_POST['chasis'];
			$this->combustible = $_POST['combustible'];
			$this->anio = $_POST['anio'];
			$this->color = $_POST['color'];
			
		
			$this->foto=$this->_get_name_file($_FILES['foto']['name'],12);
			$path = "images/" . $this->foto;
   
			if(!move_uploaded_file($_FILES['foto']['tmp_name'], $path)){
				$mensaje="Cargar la imagen";
				echo $this->_message_error($mensaje);
			}
   
			$this->avaluo = $_POST['avaluo'];


			$sql = "INSERT INTO vehiculo VALUES (NULL,
												'$this->placa',
												$this->marca,
												'$this->motor',
												'$this->chasis',
												'$this->combustible',
												'$this->anio',
												$this->color,
												'$this->foto',
												'$this->avaluo');";


			//echo $sql;

			
			if($this->con->query($sql)){
				echo $this->_message_ok("guardo");
			}else{
				echo $this->_message_error("Guardar<br>");
			}

		
	
	
	
	
}
//*******************************FUNCION GET NAME FILE**********************************************************


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
	return $cadena.".".$ext;
}

	
//***************************************************************************************	
	
	private function _calculo_matricula($avaluo){
		return number_format(($avaluo * 0.10),2);
	}
	
//***************************************************************************************************************************
	
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="vehiculo.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_BD_Vacia($tipo){
	   $html = '
		<table border="0" align="center">
			<tr>
				<th> NO existen registros en la ' . $tipo . 'Favor contactar a .................... </th>
			</tr>
	
		</table>';
		return $html;
	
	
	}
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="vehiculo.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
//************************************************************************************************************************************************

 
}
?>


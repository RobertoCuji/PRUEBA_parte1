<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Matriculas Vehículos - PARTE II</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="styless/estilos.css" type="text/css"/>

	
</head>
<body>
	<?php
	    include_once("constantes.php");
		require_once("class/class.vehiculo.php");
		
		$cn = conectar();
		$v = new vehiculo($cn);
		//vehiculo::MetodoEstatico();
		
		
//2.1 URL para la petición GET
//$URL = "http://localhost:8088/Vehiculo_CRUD/Vehiculo_PARTE_II/vehiculo.php?d=act/0";	
//$URL = "http://localhost:8088/Vehiculo_CRUD/Vehiculo_PARTE_II/vehiculo.php?d=act/5";	

//$URL = "http://localhost:8088/Vehiculo_CRUD/Vehiculo_PARTE_II/vehiculo.php?d=det/0";	
//$URL = "http://localhost:8088/Vehiculo_CRUD/Vehiculo_PARTE_II/vehiculo.php?d=det/5";		
		
    // Codigo necesario para realizar pruebas.
		if(isset($_GET['d'])){
		  
			//echo "<br>PETICION GET <br>";
			/*echo "<pre>";
				print_r($_GET);
			echo "</pre>";
		  */
			// 2.1 PETICION GET
			// $dato = $_GET['d'];
			
			// 2.2 DETALLE id
			$dato = base64_decode($_GET['d']);
			$tmp = explode("/", $dato);
			
			
		//	echo "<br>VARIABLE TEMP <br>";
			/*echo "<pre>";
				print_r($tmp);
			echo "</pre>";
					
			*/
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "det"){
				echo $v->get_detail_vehiculo($id);
			}elseif($op == "act"){
				echo $v->get_form($id);
			}elseif($op == "new"){
				echo $v->get_form();
			}elseif($op == "del"){
				echo $v->delete_vehiculo($id); // BORRAR TODOS LOS REGISTROS DE LA BASE DE DATOS
			}
			
        
		
		}
		/*
		else
		{
		    
			if(isset($_POST['Guardar'])){
				echo "<br>PETICION POST ...... <br>";
				echo "<pre>";
					print_r($_POST);
				echo "</pre>";
			 }
			
			
			//PARTE III
			if(isset($_POST['Guardar']) && ($_POST['placa']!= NULL)){
				echo "<br>GRABAR VEHICULO - PARTE III<br><br><br>";
				echo '<th colspan="2"><a href="vehiculo.php">Regresar</a></th>';
				//$v->save_vehiculo();
			}else{
				echo $v->get_list();
			}
			
		}*/




		else{
			   
		/*	echo "<br>PETICION POST <br>";
			echo "<pre>";
				print_r($_POST);
			echo "</pre>";
		  */
		if(isset($_POST['Guardar']) && $_POST['op']=="new"){
			$v->save_vehiculo();
		}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
			$v->update_vehiculo();
		}else{
			echo $v->get_list();
		}	
	}
				

		
//*******************************************************
		function conectar(){
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
			}
			/*else
			{
				echo "La conexión tuvo éxito .......<br><br>";
			}*/
			
			$c->set_charset("utf8");
			return $c;
		}
//**********************************************************
		
		
	?>	
</body>
</html>

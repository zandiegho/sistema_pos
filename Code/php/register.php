<?php 

$nombreMarca = $_POST['nombreMarca'];
$idMarca = $_POST['idMarca'];
$nombreContacto = $_POST['nombreContacto'];
$apellidoContacto =$_POST['apellidoContacto'];
$telefono = $_POST['telefonoContacto'];
$correo = $_POST['mailContacto'];
$descripcion = $_POST['descrpcion'];

if (isset($_FILES['imagen'])) {
    $archivo = $_FILES['imagen'];
    $nombreImagen = $archivo['name'];
    $tipo = $archivo['type'];
    $tamano = $archivo['size'];
    $temporal = $archivo['tmp_name'];
    
    if (move_uploaded_file($temporal, "../uploads/" . $nombreImagen)) {
        echo "<br/>La imagen se cargó correctamente.";
        $rutaImagen = "localhost/code/uploads/$nombreImagen$tipo";
    } else {
        echo "Se produjo un error al cargar la imagen.";
    }
}

if(isset($nombreMarca) && isset($idMarca) && isset($nombreContacto) && isset($telefono) && isset($correo)){
    conex($nombreMarca, $idMarca, $nombreContacto, $apellidoContacto, $telefono, $correo, $descripcion, $rutaImagen);
  }

function conex($nameS, $idS, $nameC, $apellidoC, $tel, $mail, $descripcion, $ruta){
    //create conection to bd Mysql
    $servername = "localhost:3306"; //--> Ip servidor base de datos
    $username = "root"; //--> nombre usuario base de datos
    $password = ""; //--> contraseña base de datos 
    $dbname = "SistemaPos"; //--> nombre de la base de datos

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }else{
        echo "<br/>Conexión Exitosa";
    }

    //crear consulta para ingresar datos a tabla User y a tabla negocio
    $query = "INSERT INTO user (username, phone, mail) values ('$nameC' , '$tel' , '$mail')" ;
    $result = mysqli_query($conn, $query);

        if (mysqli_num_rows( $result ) > 0) {
            
            echo "Usuario Registrado Correctamente<br/>";
    
            $querySelecUser = "SELECT * FROM USER WHERE name = '$nameC'";
            
            $resultQuerySelecUser = mysqli_query($conn, $querySelecUser);
    
            if (mysqli_num_rows($resultQuerySelecUser) > 0){ 
                echo "Usuario consultado Correctamente<br/>";
    
                while ($row = mysqli_fetch_assoc($resultQuerySelecUser)) {
                    $id_user = $row['id_User'];        
                }
                echo "<br/> $id_user";
            }    
        }
        else { 
          # echo "No hay resultados para el documento especificado."; 
          echo "<script language='javascript'>alert('Error de autentificacion en Usuario');</script>";
        }  
    

    $query2 = 
    "INSERT INTO negocio (nit, name, id_user)
    values ($idS, '$nameS', $id_user);";
    
    $result2 = mysqli_query($conn, $query2);

    if ($result2 instanceof mysqli_result){

        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_assoc($result2)) {
                $id_bussines = $row['id_Bussines'];
            }
            echo "Negocio Registrado Correctamente<br/>";
        }
        else { 
          # echo "No hay resultados para el documento especificado."; 
          echo "<script language='javascript'>alert('Error de autentificacion en Negocio');</script>";
        }
    }
    


    $query3 = 
    "INSERT INTO datosnegocio (Descripcion, imagen, id_Bussines)
    values ($descripcion, '$ruta', '$id_bussines',)";

    
    $result3 = mysqli_query($conn, $query3);
    
    if (mysqli_num_rows($result3) > 0) {
        
        echo "Datos de Negocio Registrado Correctamente";
    }
    else { 
      # echo "No hay resultados para el documento especificado."; 
      echo "<script language='javascript'>alert('Error de autentificacion en Datos de Negocio');</script>";
    }

}
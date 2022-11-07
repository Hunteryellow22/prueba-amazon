<?php
header('Content-Type: application/json');
require_once "conectar.php";



switch ($_GET['accion']) {
    case 'listar':
    $productos=[];
    $sql = "SELECT * from productos";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_execute($stmt);
    $result=mysqli_stmt_get_result($stmt);
    while ($fila = mysqli_fetch_array($result, MYSQLI_NUM))
               {
                array_push($productos, $fila);
    }
    mysqli_stmt_close($stmt);
        echo json_encode($productos);
        break;
        
    case 'agregar':
    $productos=[];
    $sql = "INSERT into productos(nombre,descripcion,cantidad,costo) values (?,?,?,?)";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssii", $_POST['nombre'],$_POST['descripcion'],$_POST['cantidad'],$_POST['precio']);
    mysqli_stmt_execute($stmt);
        break;

    case 'recuperar':
        $productos=[];
    $sql = "SELECT * from productos as pro where id_producto = ?";
    $stmt = mysqli_prepare($link, $sql);
     mysqli_stmt_bind_param($stmt, "s", $_REQUEST['nombre']);
    mysqli_stmt_execute($stmt);
    $result=mysqli_stmt_get_result($stmt);

    while ($fila = mysqli_fetch_array($result, MYSQLI_NUM))
               {
                array_push($productos, $fila);
    }
    mysqli_stmt_close($stmt);
        echo json_encode($productos);
        break;
    case 'borrar':
    
    $productos=[];
    $sql = "DELETE from productos where id_producto=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_REQUEST['id_codigo']);
    mysqli_stmt_execute($stmt);
       
        break;

    case 'modificar':
        
        $productos=[];
        $sql = "UPDATE productos set nombre=?,descripcion=?, cantidad=?, costo=? where id_producto=?";
        $stmt = mysqli_prepare($link, $sql);
       

            mysqli_stmt_bind_param($stmt, "ssiii", $_POST['nombre'],$_POST['descripcion'],$_POST['cantidad'],$_POST['precio'],$_POST['id_codigo']);
            
            mysqli_stmt_execute($stmt);
          
       
        
        break;
    
}

?>
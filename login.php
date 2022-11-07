
<?php
// inicializar session
session_start();
 
// si hay sesion redireccionar a pagina principal
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: index.php");
  exit;
}
 
// configuracion de la base de datos
require_once "conectar.php";
 
// definicion de variables e inicializacion 
$username = $password = "";
$username_err = $password_err = "";
 
// procesamieto de datos del formulario
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // revisar que el usuario no venga vacio
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor ingrese su usuario.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // revisa que la contraseña no venga vacia
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor ingrese su contraseña.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // valida que no haya errores y procede a la consulta
    if(empty($username_err) && empty($password_err)){
        // preparar consulta
        $sql = "SELECT id_usuario, correo, password,admin FROM usuarios WHERE correo = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // añade las variables a la consulta prepara anteriormente
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // setea el usuario
            $param_username = $username;
            
            // ejecucion de la sentencia preparada
            if(mysqli_stmt_execute($stmt)){
                // almacena resultados
                $result=mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_array($result, MYSQLI_NUM); 
            
              
                // si el usuario existe verifica la contraseña
                if(!empty($row)){
                    // crea password hash
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // enlaza las variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                   
                            // si la contraseña es correcta crea una session con los datos necesario
                    
                      
                        if(password_verify($password, $hashed_password)){
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $row[0];
                            $_SESSION["username"] = $row[1];
                            $_SESSION["adminsitrador"] = $row[3];
                            // Carga de vista correspondiente si es administrador o no
                            if($_SESSION["adminsitrador"]){

                                header("location: productos_crud.php");
                            }else{
                                header("location: index.php");
                            }
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "La contraseña que has ingresado no es válida.";
                        }
                    
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No existe cuenta registrada con ese nombre de usuario.";
                }
            } else{
                echo "Algo salió mal, por favor vuelve a intentarlo.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Naranti hardware</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <!-- owl stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>
<!-- body -->

<body class="main-layout">
    <!-- loader  -->
    <div class="loader_bg">
        <div class="loader"><img src="images/loading.gif" alt="#" /></div>
    </div>
    <!-- end loader -->
    <!-- header -->
    <header>
        <!-- header inner -->
        <div class="header">

            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo">
                                    <a href="index.png"><img src="images/Logos/naranti.jpg" style="height: 10vh;" alt="#"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <div class="menu-area">
                            <div class="limit-box">
                                <nav class="main-menu">
                                   <ul class="menu-area-main">
                                        <li class="active"> <a href="index.php">Inicio</a> </li>
                                        <li> <a href="about.php">Nosotros</a> </li>
                                        <li><a href="brand.html">Cotizador</a></li>
                                        <li><a href="productos.php">Productos</a></li>
                                        <!-- Validacion de usuario logeado o no -->
                                        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true):?>
                                        <li><a href="logout.php">Logout</a></li>
                                        <?php else:?>
                                        <li><a href="login.php">Login/REGISTRO</a></li>
                                        <?php endif;?>
                                       
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end header inner -->
    </header>
    <!-- end header -->
    <div class="brand_color">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="titlepage">
                        <h2>Login/Registro</h2>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- contact -->
    <div class="contact">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <form class="main_form"  method="post">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <input class="form-control" placeholder="Correo" type="text" name="username">
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                <input class="form-control" placeholder="Contraseña" type="password" name="password">
                            </div>
                            <div class="col-md-12">
                              <a href="registro.php">No tiene cuenta,presione este enlace</a>
                          </div>
                            <div class=" col-md-12">
                                 <button class="send">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end contact -->

    <!-- footer -->
    <footer>
        <div id="contact" class="footer">
            <div class="container">
                <div class="row pdn-top-30">
                    <div class="col-md-12 ">
                        <div class="footer-box">
                            <div class="headinga">
                                <h3>Direccion</h3>
                                <span>P.º de Los Eucaliptos 172-interior 2, Balcones de Santa María, 58090 Morelia, Mich.</span>
                                <p>443 338 2109
                                    <br>contacto@naranti.com.mx</p>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <div class="container">
                    <p>© 2022 All Rights Reserved</p>
                </div>
            </div>
        </div>
    </footer>
    <!-- end footer -->
    <!-- Javascript files-->
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.0.0.min.js"></script>
    <script src="js/plugin.js"></script>
    <!-- sidebar -->
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/custom.js"></script>
    <!-- javascript -->
    <script src="js/owl.carousel.js"></script>
    <script src="https:cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".fancybox").fancybox({
                openEffect: "none",
                closeEffect: "none"
            });

            $(".zoom").hover(function() {

                $(this).addClass('transition');
            }, function() {

                $(this).removeClass('transition');
            });
        });
    </script>
</body>

</html>
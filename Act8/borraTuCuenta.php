<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMULARIO DE VERIFICACIÓN</title>
</head>
<body>
  <?php
      function regresa($donde, $boton)//para hacer botones
      {
          echo "<form action=$donde method='POST'>";
          echo "<button type='submit'>$boton</button>";
          echo "</form>";
      }

      include("./Config.php");
      $conexion = conecta();
      if (isset($_POST["Usuario"]))
      {
        $usuario=$_POST["Usuario"];
        echo '<form action="./borraTuCuenta.php" method="POST">
            ¿Seguro que quiere borrar su cuenta?
            Si<input type="radio" name="decision" value="si">
            No<input type="radio" name="decision" value="no">
            <input type="hidden" name="deleuser" value='.$usuario.'>
            <button type="submit">Enviar</button>
        </form>';
      }
      if(isset($_POST["decision"]))
      {
          $decision=$_POST["decision"];
          $usuario=$_POST["deleuser"];
          if($decision== "si")
          {
              $indicacion="DELETE FROM alumno WHERE Ncuenta=$usuario";
              $res= mysqli_query($conexion, $indicacion);
              if($res)
              {
                echo "<h1>Se borró la cuenta con éxito</h1>
                  <form action='./form.html' method='POST'>
                    <button type='submit' name='Cerrar' value='c'>Inicio</button>
                  </form>";
              }
              else
              {
                  echo"No se pudo eliminar la cuenta";
                  regresa("./conexion.php", "Regresar");
              }
          }
          elseif($decision== "no")//Si la decisión es no te regresa
          {
            
            echo "<form action='./conexion.php' method='POST'>
                <input type='hidden' name='num_cuenta' value='".$usuario."'>
                    <button type='submit' name='volver' value='v'>Regresar</button>
                  </form>";
          }
      }
  ?>
</body>
</html>

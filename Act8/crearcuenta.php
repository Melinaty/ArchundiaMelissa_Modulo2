<?php
  include("./Config.php");
  function get_ubi($x,$conexion){
    $indicacion="SELECT Ubicacion FROM ubicacion WHERE id_ubicacion=$x";
    $res = mysqli_query($conexion, $indicacion);
    $nom_ubi = mysqli_fetch_array($res);
    return $nom_ubi[0];
  }
  function get_moda($i,$conexion){
    $indicacion="SELECT Modalidad FROM modalidad WHERE id_modalidad=$i";
    $res = mysqli_query($conexion, $indicacion);
    $nom_mod = mysqli_fetch_array($res);
    return $nom_mod[0];

    }
    function val_carr($i,$conexion){
        $indicacion="SELECT clave_carrera FROM pase_regla WHERE id_pase=$i";
        $res = mysqli_query($conexion, $indicacion);
        $val_carr = mysqli_fetch_array($res);
          return $val_carr[0];
    }
    $conexion = conecta();
    if(isset($_POST["nombre"]))//si recibió el formulario del usuario
    {
        $ncuenta=$_POST["num_cuenta"];
        $nombre=$_POST["nombre"];
        $apePat=$_POST["apePat"];
        $apeMat=$_POST["apeMat"];
        $area=$_POST["area"];
        $carrera=$_POST["carrera"];

        $indicacion="INSERT INTO alumno (Ncuenta, Nombre, ApellidoP, ApellidoM, Area, id_pase) VALUES
        ($ncuenta, '$nombre', '$apePat', '$apeMat',$area, $carrera)";
        if(mysqli_query($conexion, $indicacion)){
            $busca="SELECT id_ubicacion, id_modalidad, id_pase FROM pase_regla WHERE clave_carrera=".val_carr($carrera,$conexion)."";
            $res = mysqli_query($conexion, $busca);
            $cont=mysqli_num_rows($res);
            if($cont>0)
            {
            echo "<form action='./crearcuenta.php' method='POST'>
            <fieldset style='width: 700px;'>
            <legend>Selecciona</legend>
            <label>¿Qué ubicación quieres?<select name='carrera' required>";
                while($row = mysqli_fetch_array($res))
                {
                    echo "<option value='$row[2]'>".get_ubi($row[0],$conexion)." (".get_moda($row[1],$conexion).")</option>";
                }
                echo "</select>
            </label><br>
            <input type='submit' value='Continuar'>
            <input type='hidden' name='num_cuenta' value='".$ncuenta."'>
            </fieldset>
            </form>";
            }
        }
    }
    else
    {
        $ncuenta=$_POST["num_cuenta"];

        if(isset($_POST["cuarto"]))
        {
            $cuarto=($_POST["cuarto"]);
            $prom=0;
            for($i=0; $i<count($cuarto); $i++)
            {
                $prom+=$cuarto[$i];
            }
            $prom=$prom/count($cuarto);
            $busca="UPDATE alumno SET Promedio_cuarto=$prom WHERE Ncuenta=$ncuenta";
            $res = mysqli_query($conexion, $busca);
            if($res)
            {
                echo "<fieldset>
                <legend>Calificaciones de quinto</legend>
                <form action='./crearcuenta.php' method='post'>";
                $busca="SELECT nombre FROM asignaturas WHERE anio=5";
                $res = mysqli_query($conexion, $busca);
                while($row = mysqli_fetch_array($res))
                {
                    echo $row[0].": <input type='number' name='quinto[]' min='6' max='10' required><br>";
                    echo "<br>";
                }
                echo "<input type='hidden' name='num_cuenta' value='".$ncuenta."'>
                <input type='submit' value='Enviar'>
                </form>
                </fieldset>";
            }
            else{
                echo "No se pudo subir tu promedio";
            }

        }
        elseif(isset($_POST["quinto"])){
            $quinto=($_POST["quinto"]);
            $prom=0;
            for($i=0; $i<count($quinto); $i++)
            {
                $prom+=$quinto[$i];
            }
            $prom=$prom/count($quinto);
            $busca="UPDATE alumno SET Promedio_quinto=$prom WHERE Ncuenta=$ncuenta";
            $res = mysqli_query($conexion, $busca);
            if($res)
            {
                echo "<fieldset>
                <legend>Calificaciones de sexto</legend>
                <form action='./sexto.php' method='post'>";
                $busca="SELECT Area FROM alumno WHERE Ncuenta=$ncuenta";
                $res = mysqli_query($conexion, $busca);
                $area = mysqli_fetch_array($res);
                $busca1="SELECT nombre FROM asignaturas WHERE anio=6 AND Area IN($area[0],0) AND Optativa='N'";
                $res1 = mysqli_query($conexion, $busca1);
                if($busca1)
                {
                    while($row = mysqli_fetch_array($res1))
                    {
                        echo $row[0].": <input type='number' name='sexto[]' min='6' max='10' required><br>";
                        echo "<br>";
                    }
                }
                $buscaopt="SELECT nombre FROM asignaturas WHERE anio=6 AND Area=$area[0] AND Optativa='S'";
                $resopt = mysqli_query($conexion, $buscaopt);
                echo 'Optativa: <select name="select" required>
                        <option value="">Selecciona una opcion</option>';
                while($rowopt = mysqli_fetch_array($resopt))
                {
                  echo '<option value="'.$rowopt[0].'">'.$rowopt[0].'</option>';
                }
                echo "</select><input type='number' name='sexto[]' min='6' max='10' required><br>";
                echo "<input type='hidden' name='num_cuenta' value='".$ncuenta."'>
                <input type='submit' value='Enviar'>
                </form>
                </fieldset>";
            }
            else{
                echo "No se pudo subir tu promedio";
            }
        }
        else
        {
          $carrera=$_POST["carrera"];
          $busca="UPDATE alumno SET id_pase=$carrera WHERE Ncuenta=$ncuenta";
          $res = mysqli_query($conexion, $busca);
          if($res)
          {
            echo "<fieldset>
            <legend>Calificaciones de cuarto</legend>
            <form action='./crearcuenta.php' method='post'>";
            $busca="SELECT nombre FROM asignaturas WHERE anio=4";
            $res = mysqli_query($conexion, $busca);
            while($row = mysqli_fetch_array($res))
            {
              echo $row[0].": <input type='number' name='cuarto[]' min='6' max='10' required><br>";
              echo "<br>";
            }
            echo "<input type='hidden' name='num_cuenta' value='".$ncuenta."'>
            <input type='submit' value='Enviar'>
            </form>
            </fieldset>";
          }
          else{
            echo "No se pudo guardar tu carrera";
          }
        }

    }
?>

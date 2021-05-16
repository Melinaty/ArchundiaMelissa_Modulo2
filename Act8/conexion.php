<?php
    include("./Config.php");

    function regresa($donde, $boton,$cuenta)//crea los botones
    {
        echo "<form action=$donde method='POST'>";
        echo "<input type='hidden' name='Usuario' value='".$cuenta."'>";
        echo "<button type='submit'>$boton</button>";
        echo "</form>";
    }
    function prom_carrera($x,$conexion){
        $indicacion="SELECT promedio FROM pase_regla WHERE id_pase=$x";
        $res = mysqli_query($conexion, $indicacion);
        $prom_req = mysqli_fetch_array($res);
          return $prom_req[0];
    }
    function get_ubi($x,$conexion){
        $indicacion="SELECT id_pase, clave_carrera,carrera.nombre,modalidad.Modalidad,ubicacion.Ubicacion FROM pase_regla NATURAL JOIN carrera NATURAL JOIN modalidad NATURAL JOIN ubicacion WHERE id_pase=$x";
        $res = mysqli_query($conexion, $indicacion);
        $nom_carr = mysqli_fetch_array($res);
        $carrera= $nom_carr[2]." (".$nom_carr[3].") en ".$nom_carr[4];
        return $carrera;
    }
    function id_pase($x,$conexion){//regrea el id_pase
        $indicacion="SELECT id_pase FROM pase_regla WHERE clave_carrera=$x";
        $res = mysqli_query($conexion, $indicacion);
        $id_pase = mysqli_fetch_array($res);
          return $id_pase[0];
    }
    $conexion= conecta();
    $ncuenta=$_POST["num_cuenta"];
    $busca="SELECT * FROM alumno WHERE Ncuenta=$ncuenta";
    $res= mysqli_query($conexion, $busca);
    $cont= mysqli_num_rows($res);

    while($row = mysqli_fetch_array($res))
    {
        $datos=$row;
    }
    if($cont>0)//si hay registro
    {
      $instruccion1 = "SELECT id_pase FROM  alumno WHERE Ncuenta=$ncuenta";
        $res1= mysqli_query($conexion, $instruccion1);
        $pase = mysqli_fetch_array($res1);
        $pase = prom_carrera($pase[0],$conexion);
        $instruccion2 = "SELECT Promedio_cuarto, Promedio_quinto, Promedio_sexto FROM alumno WHERE Ncuenta=$ncuenta";
        $resp2= mysqli_query($conexion, $instruccion2);
        $promedio_g = mysqli_fetch_array($resp2);
        $promedio_2 = ($promedio_g[0]+$promedio_g[1]+$promedio_g[2])/3;
        if($promedio_2 > $pase+0.5)
            $prob="Alta";
        else if($pase <= $promedio_2)
                $prob="Media";
        else if($pase-0.5 >= $promedio_2 &&  $pase-0.6 < $promedio_2) 
            $prob="Baja";
        else if($pase == 0)
            $prob="La carrera es de acceso indirecto";
        else
            $prob="Casi nula";
        echo "<table border='1'>
        <thead>
            <tr><strong><th colspan='2'>DATOS</strong></th><tr>
        </thead>
        <tbody>
            <tr>
                <td>Número de cuenta:</td>
                <td>$datos[0]</td>
            </tr>
            <tr>
                <td>Nombre:</td>
                <td>$datos[4]
            </tr>
            <tr>
                <td>Apellidos:</td>
                <td>".$datos[5]." ".$datos[6]."</td>
            </tr>
            <tr>
                <td>Promedio de cuarto: </td>
                <td>$datos[1]</td>
            </tr>
            <tr>
                <td>Promedio de quinto:</td>
                <td>$datos[2]</td>
            </tr>
            <tr>
                <td>Promedio de sexto:</td>
                <td>$datos[3]</td>
            </tr>
            <tr>
                <td>Promedio general:</td>
                <td>$promedio_2</td>
            </tr>
            <tr>
                <td>Área</td>
                <td>$datos[7]</td>
            </tr>
            <tr>
                <td>Promedio requerido minimo de ingreso:</td>
                <td>$pase</td>
            </tr>
            <tr>
                <td>Pase</td>
                <td>".get_ubi($datos[8],$conexion)."</td>
            </tr>
            <tr>
                <td>Probabilidad:</td>
                <td>$prob</td>
            </tr>
        </tbody>
        </table>";

        regresa("./borraTuCuenta.php", "Borrar cuenta",$datos[0]);
        echo "<br><br>";
        regresa("./form.html", "Regresar",$datos[0]);
        echo "<br>".$promedio_2;
        echo "<br>".$pase;
        echo "<br>".$prob;
        
    }
    else// si no hay registro
    {
        echo "<form action='./crearcuenta.php' method='POST'>
        <fieldset style='width: 700px;'>
            <legend>Crea tu cuenta</legend>
            <label>Nombre:
                <input type='text' name='nombre' required>
            </label>
            <br><br>
            <label>Apellido Paterno:
                <input type='text' name='apePat' required>
            </label>
            <br><br>
            <label>Apellido Materno:
                <input type='text' name='apeMat' required>
            </label>
            <br><br>
            <label>Área:
                <input type='number' name='area' min='1' max='4' required>
            </label>
            <br><br>
            <label>Carrera:
                <select name='carrera' required>";
                $carrera="SELECT * FROM carrera";
                $respuesta= mysqli_query($conexion, $carrera);
                while($row = mysqli_fetch_array($respuesta))
                {
                    echo "<option value=".id_pase($row[0],$conexion).">".$row[1]."</option>";
                }
                echo "</select>
            </label>
              <input type='hidden' name='num_cuenta' value='".$ncuenta."'>
            <br><br>
            <button type='submit' name='Iniciar'>Inicar sesión</button>
        </fieldset>
    </form>";
    }
?>

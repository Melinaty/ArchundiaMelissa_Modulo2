<?php
    include("./Config.php");
    $conexion= conecta();
    function prom_carrera($x,$conexion){
        $indicacion="SELECT promedio FROM pase_regla WHERE id_pase=$x";
        $res = mysqli_query($conexion, $indicacion);
        $prom_req = mysqli_fetch_array($res);
          return $prom_req[0];
    }

    if(isset($_POST["sexto"]))
    {
        $sexto=($_POST["sexto"]);
        $ncuenta=$_POST["num_cuenta"];
        $prom=0;
        for($i=0; $i<count($sexto); $i++)
        {
            $prom+=$sexto[$i];
        }
        $prom=$prom/count($sexto);
        $busca="UPDATE alumno SET Promedio_sexto=$prom WHERE Ncuenta=$ncuenta";
            $res = mysqli_query($conexion, $busca);
            if($res)
            {
                echo "Tus dato se subieron correctamente";
                echo "<form action='./conexion.php' method='POST'>
                <input type='hidden' name='num_cuenta' value='".$ncuenta."'>
                <button type='submit'>Presione para continuar</button>";
            }
            else{
                echo "No se pudo subir tu promedio";
            }

    }
?>

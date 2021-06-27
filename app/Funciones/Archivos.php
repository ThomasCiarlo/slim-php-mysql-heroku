<?php

class Archivos
{

    public static function LeerArchivos($rutaArchivo)
    {
      $datos = "";
      $array = array();
      $file = fopen($rutaArchivo,'r');

      while(!feof($file))
      {  
            $array = json_decode(fgets($file));
      }
      
      return $array;
    }

    public static function EscribirArchivos($rutaArchivo,$str)
    {
          $todoOk = false;
          $file = fopen($rutaArchivo,'a');
          try{              
          fwrite($file, $str . "\n");
          $todoOk = true;
          }
          catch(Exception $e)
          {
              fclose($file);
          }
  
          fclose($file);
          return $todoOk;   

    }

    public static function ToJson($vec,$ruta)
    {
        $todoOk = false;
        try{
            $json_string = json_encode($vec); 
            file_put_contents($ruta,$json_string."\n");
            $todoOk = true;
        }
        catch(Exception $e){

        }
        return $todoOk;

    }


    public static function LeerJson($ruta)
    {

      try
      {
        $datos_clientes = file_get_contents($ruta);
        $json_clientes = json_decode($datos_clientes);
        $arrayuser = array();

        if($json_clientes != null){
          foreach($json_clientes as $user)
          {
              array_push($arrayuser,$user);
          }
        }
      }
      catch(Exception $e)
      {
        $arrayuser = array();
      }
      
        return $arrayuser;

    }

    public static function RecibirYLeerArchivo($rutaArchivo,$str)
    {
          $todoOk = false;
          $file = fopen($rutaArchivo,'a');
          try{              
          fwrite($file, $str . "\n");
          $todoOk = true;
          }
          catch(Exception $e)
          {
              fclose($file);
          }
  
          fclose($file);
          return $todoOk;   

    }

}

?>
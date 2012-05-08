<?php
/**/
$hostname_Conexion = "localhost";
$database_Conexion = "adpro";
$username_Conexion = "adpro";
$password_Conexion = "password";
$Conexion = mysql_pconnect($hostname_Conexion, $username_Conexion, $password_Conexion) or trigger_error(mysql_error(),E_USER_ERROR);
/**/


mysql_query("SET NAMES 'utf8'"); //Avoid extrange symbols.
mysql_select_db($database_Conexion, $Conexion);

?>

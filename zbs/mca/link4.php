<?php
class auth
{

    public $mca_hostname = 'sql36.cpt3.host-h.net';
    public $mca_username = 'zbs_user';
    public $mca_password = 'z8eyU2rWKtYrUFG4EzM8';
    //public $mca_hostname = 'localhost';
    //public $mca_username = 'root';
    //public $mca_password = '';
}
function connection($host,$mysql_dbname)
{

    try
    {
        $auth=new auth();
        /*** mysql hostname ***/
        $mysql_hostname = 'default';

        /*** mysql username ***/
        $mysql_username = 'default';

        /*** mysql password ***/
        $mysql_password = 'default';

        /*** database name ***/

        if($host=="zbs")
        {
            $mysql_hostname = $auth->mca_hostname;
            //$mysql_hostname = 'localhost';

            $mysql_username = $auth->mca_username;
            //$mysql_username = 'root';

            $mysql_password = $auth->mca_password;
            //$mysql_password = '';

            /*** database name ***/
            $mysql_dbname="zbs_db";
        }

        else
        {
            echo "There is an error  ".$host;
        }



        $dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
        /*** $message = a message saying we have connected ***/

        /*** set the error mode to excptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbh;
    }
    Catch(Exception $e)
    {
        header("Location: ../logout.php");
        return ("Connection Error ");
    }
}

?>
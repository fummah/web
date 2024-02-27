<?php
$table = 'web_clients_view';
$primaryKey = 'client_id';

$columns = array(
    array( 'db' => 'name', 'dt' => 0 ),
    array( 'db' => 'surname',  'dt' => 1 ),
    array( 'db' => 'broker_name',   'dt' => 2 ),
    array( 'db' => 'email',     'dt' => 3 ),
    array( 'db' => 'contact_number',     'dt' => 4 ),
    array( 'db' => 'subscription_rate',     'dt' => 5 ),
    array( 'db' => 'date_entered',     'dt' => 6 )
);
$sql_details = array(
    'user' => 'greenwhc_8',
    'pass' => 'CpN4WKc0UBmm0PrgN7Zx',
    'db'   => 'web_clients',
    'host' => 'sql2.cpt1.host-h.net'
);
require( '../classes/ssp.class.php' );
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);


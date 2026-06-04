<?php
if (substr($_SERVER["REQUEST_URI"], -10) == "config.php") {
    header("Location:./");
};
$data['mikhmon'] = array('1' => 'mikhmon<|<admin', 'mikhmon>|>aWRja2JlaWQ=');

$data['MIKROTIK'] = array('1' => 'MIKROTIK!10.5.50.1', 'MIKROTIK@|@admin', 'MIKROTIK#|#eGJlaWR4mZ6X', 'MIKROTIK%FamiiComp', 'MIKROTIK^-', 'MIKROTIK&Rp', 'MIKROTIK*10', 'MIKROTIK(1', 'MIKROTIK)', 'MIKROTIK=10', 'MIKROTIK@!@disable');
$data['VIRTUALBOX'] = array ('1'=>'VIRTUALBOX!192.168.56.113','VIRTUALBOX@|@admin','VIRTUALBOX#|#aWRja2JlaWQ=','VIRTUALBOX%VB-HOTSPOT','VIRTUALBOX^10.5.50.1','VIRTUALBOX&Rp','VIRTUALBOX*10','VIRTUALBOX(1','VIRTUALBOX)','VIRTUALBOX=10','VIRTUALBOX@!@disable');
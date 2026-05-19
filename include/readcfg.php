<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once __DIR__.'/../init.php';
if (substr($_SERVER["REQUEST_URI"], -11) == "readcfg.php") {
    header("Location:./");
}

$iphost = session_data("iphost");
$userhost = session_data("userhost");
$passwdhost = session_data("passwdhost");
$hotspotname = session_data("hotspotname");
$dnsname = session_data("dnsname");
$currency = session_data("currency");
$areload = session_data("areload");
$iface = session_data("iface");
$infolp = session_data("infolp");
$idleto = session_data("idleto");
$sesname = session_data("sesname");
$useradm = session_data("useradm");
$passadm = session_data("passadm");
$livereport = session_data("livereport");

$cekindo['indo'] = array(
    'RP', 'Rp', 'rp', 'IDR', 'idr', 'RP.', 'Rp.', 'rp.', 'IDR.', 'idr.',
);



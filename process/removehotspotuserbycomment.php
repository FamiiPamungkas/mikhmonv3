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

require_once __DIR__ . '/../init.php';

$getuser = RouterosAPI::getInstance()->comm("/ip/hotspot/user/print", array(
  "?comment" => "$removehotspotuserbycomment",
  "?uptime" => "00:00:00"
));
$TotalReg = count($getuser);

for ($i = 0; $i < $TotalReg; $i++) {
  $userdetails = $getuser[$i];
  $uid = $userdetails['.id'];

  RouterosAPI::getInstance()->comm("/ip/hotspot/user/remove", array(
    ".id" => "$uid",
  ));
}

echo "<script>window.location='./?hotspot=users&profile=all&session=" . $session . "'</script>";
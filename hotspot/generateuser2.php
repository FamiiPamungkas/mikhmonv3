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
require_once __DIR__ . '/utils.php';

ini_set('max_execution_time', 300);

if (!isset($_SESSION["mikhmon"])) {
	header("Location:../admin.php?id=login");
} else {
    // time zone
    date_default_timezone_set($_SESSION['timezone']);

    $profiles = hotspot_config("profiles");

	if (isset($_POST['qty'])) {
		$qty = ($_POST['qty']);
		$user = ($_POST['user']);
		$profile = ($_POST['uprofile']);
		$prefix = ($_POST['prefix']);
		$adcomment = ($_POST['adcomment']);

		if ($adcomment == "") {
			$adcomment = "";
		}

        $config = $profiles[$profile];
		$getprofile = RouterosAPI::getInstance()->comm("/ip/hotspot/user/profile/print", array("?name" => $profile));
        $hotspot_profile = $profile;
        if (!$getprofile){
            $hotspot_profile = "default";
        }

		$comment = $user . "-" . rand(100, 999) . "-" . date("ymd") . "-" . $adcomment;

		if ($config) {
            $u = [];
            $p = [];
            for ($i = 1; $i <= $qty; $i++) {
                $u[$i] = $prefix.random_aplhanumeric($config["user_length"]);
                $p[$i] = random_aplhanumeric($config["pass_length"]);
			}

			for ($i = 1; $i <= $qty; $i++) {
                RouterosAPI::getInstance()->comm("/ip/hotspot/user/add", array(
					"server" => "all",
					"name" => "$u[$i]",
					"password" => "$p[$i]",
					"profile" => $hotspot_profile,
					"limit-uptime" => $config['time_limit'],
					"limit-bytes-total" => "0",
					"comment" => "$comment",
				));
			}
		}

        echo "<script>window.location='./?hotspot=users&comment=$comment&session=" . $session . "'</script>";
	}

}
?>
<div class="row">

<div class="col-8">
<div class="card box-bordered">
	<div class="card-header">
	<h3><i class="fa fa-user-plus"></i> <?= $_generate_user ?> 2 <small id="loader" style="display: none;" ><i><i class='fa fa-circle-o-notch fa-spin'></i> <?= $_processing ?> </i></small></h3>
	</div>
	<div class="card-body">
<form autocomplete="off" method="post" action="">
    <input type="hidden" name="user" value="up">
    <input type="hidden" name="server" value="all"/>
	<div>
        <?php if ($_SESSION['ubp'] != "") {
            echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=" . $_SESSION['ubp'] . "&session=" . $session . "'> <i class='fa fa-close'></i> ".$_close." X</a>";
        } elseif ($_SESSION['vcr'] = "active") {
            echo "    <a class='btn bg-warning' href='./?hotspot=users-by-profile&session=" . $session . "'> <i class='fa fa-close'></i> ".$_close." Y</a>";
        } else {
            echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=all&session=" . $session . "'> <i class='fa fa-close'></i> ".$_close." Z</a>";
        } ?>
        <button type="submit" name="save" onclick="loader()" class="btn bg-primary" title="Generate User"> <i class="fa fa-save"></i> <?= $_generate ?></button>
    </div>
<table class="table">
  <tr>
    <td class="align-middle"><?= $_qty ?></td><td><div><input class="form-control " type="number" name="qty" min="1" max="500" value="1" required="1"></div></td>
  </tr>
    <tr>
        <td class="align-middle"><label for="uprofile">Profile</label></td>
        <td>
            <select class="form-control" id="uprofile" name="uprofile" required="required" onchange="setPrefix(this)">
                <option value="">- Select Below -</option>
                <?php foreach ($profiles as $k=>$v) { ?>
                    <option value="<?= $k ?>" data-prefix="<?= $v['prefix'] ?>"><?= $v['label'] ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
  <tr>
    <td class="align-middle"><?= $_prefix ?></td><td><input id="prefix" class="form-control " type="text" size="6" maxlength="6" autocomplete="off" name="prefix" value=""></td>
  </tr>
	<tr>
    <td class="align-middle"><?= $_comment ?></td><td><input class="form-control " type="text" title="No special characters" id="comment" autocomplete="off" name="adcomment" value=""></td>
  </tr>
</table>
</form>
</div>
</div>
</div>
    <script>
        function setPrefix(select) {
            const option = select.options[select.selectedIndex];
            const prefix = option.getAttribute("data-prefix") || "";

            const inputPrefix = document.getElementById("prefix");
            inputPrefix.value = prefix;
        }
    </script>
</div>

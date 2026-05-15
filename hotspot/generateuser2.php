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
session_start();
// hide all error
error_reporting(0);

ini_set('max_execution_time', 300);
require_once __DIR__ . '/../user-manager/utils.php';

if (!isset($_SESSION["mikhmon"])) {
	header("Location:../admin.php?id=login");
} else {
// time zone
date_default_timezone_set($_SESSION['timezone']);

	$genprof = $_GET['genprof'];
	if ($genprof != "") {
		$getprofile = $API->comm("/ip/hotspot/user/profile/print", array(
			"?name" => "$genprof",
		));
		$ponlogin = $getprofile[0]['on-login'];
		$getprice = explode(",", $ponlogin)[2];
		if ($getprice == "0") {
			$getprice = "";
		} else {
			$getprice = $getprice;
		}

		$getvalid = explode(",", $ponlogin)[3];

		$getlocku = explode(",", $ponlogin)[6];
		if ($getlocku == "") {
			$getprice = "Disable";
		} else {
			$getlocku = $getlocku;
		}

		if ($currency == in_array($currency, $cekindo['indo'])) {
			$getprice = $currency . " " . number_format((float)$getprice, 0, ",", ".");
		} else {
			$getprice = $currency . " " . number_format((float)$getprice);
		}
		$ValidPrice = "<b>Validity : " . $getvalid . " | Price : " . $getprice . " | Lock User : " . $getlocku . "</b>";
	} else {
	}

	$srvlist = $API->comm("/ip/hotspot/print");
    $profiles = userman("profiles");

	if (isset($_POST['qty'])) {
		$qty = ($_POST['qty']);
		$server = ($_POST['server']);
		$user = ($_POST['user']);
		$userl = ($_POST['userl']);
		$prefix = ($_POST['prefix']);
		$char = ($_POST['char']);
		$profile = ($_POST['uprofile']);
		$timelimit = ($_POST['timelimit']);
		$datalimit = ($_POST['datalimit']);
		$adcomment = ($_POST['adcomment']);
		$mbgb = ($_POST['mbgb']);
		if ($timelimit == "") {
			$timelimit = "0";
		} else {
			$timelimit = $timelimit;
		}
		if ($datalimit == "") {
			$datalimit = "0";
		} else {
			$datalimit = $datalimit * $mbgb;
		}
		if ($adcomment == "") {
			$adcomment = "";
		} else {
			$adcomment = $adcomment;
		}

        $config = $profiles[$profile];
		$getprofile = $API->comm("/ip/hotspot/user/profile/print", array("?name" => $config['hotspot_profile']));
		$ponlogin = $getprofile[0]['on-login'];
		$getvalid = explode(",", $ponlogin)[3];
		$getprice = explode(",", $ponlogin)[2];
		$getsprice = explode(",", $ponlogin)[4];
		$getlock = explode(",", $ponlogin)[6];
		$_SESSION['ubp'] = $profile;
		$commt = $user . "-" . rand(100, 999) . "-" . date("ymd") . "-" . $adcomment;
		$gentemp = $commt . "|~" . $profile . "~" . $getvalid . "~" . $getprice . "!".$getsprice."~" . $timelimit . "~" . $datalimit . "~" . $getlock;
		$gen = '<?php $genu="'.encrypt($gentemp).'";?>';
		$temp = './voucher/temp.php';
		$handle = fopen($temp, 'w') or die('Cannot open file:  ' . $temp);
		$data = $gen;
		fwrite($handle, $data);

		$a = array("1" => "", "", 1, 2, 2, 3, 3, 4);


        error_log("PROFILE ".print_r($config,true));
        error_log("GET PROFILE  ".print_r($getprofile,true));
        error_log("ON LOGIN STR ".print_r($ponlogin,true));
        error_log("ON LOGIN  ".print_r(explode(",",$ponlogin),true));
        error_log("VALID  ".print_r($getvalid,true));
        error_log("PRICE  ".print_r($getprice,true));
        error_log("SALES PRICE  ".print_r($getsprice,true));
        error_log("LOCK  ".print_r($getlock,true));
		if ($config) {
            $u = [];
            $p = [];
            for ($i = 1; $i <= $qty; $i++) {
                $u[$i] = $prefix.random_aplhanumeric($config["user_length"]);
                $p[$i] = random_aplhanumeric($config["pass_length"]);
			}

			for ($i = 1; $i <= $qty; $i++) {
//				$API->comm("/ip/hotspot/user/add", array(
//					"server" => "all",
//					"name" => "$u[$i]",
//					"password" => "$p[$i]",
//					"profile" => $config['hotspot-profile'],
//					"limit-uptime" => "$timelimit",
//					"limit-bytes-total" => "$datalimit",
//					"comment" => "$commt",
//				));
			}
		}

//		if ($qty < 2) {
//			echo "<script>window.location='./?hotspot-user=" . $u[1] . "&session=" . $session . "'</script>";
//		} else {
//			echo "<script>window.location='./?hotspot-user=generate&session=" . $session . "'</script>";
//		}
	}

	$getprofile = $API->comm("/ip/hotspot/user/profile/print");
	include_once('./voucher/temp.php');
	$genuser = explode("-", decrypt($genu));
	$genuser1 = explode("~", decrypt($genu));
	$umode = $genuser[0];
	$ucode = $genuser[1];
	$udate = $genuser[2];
	$uprofile = $genuser1[1];
	$uvalid = $genuser1[2];
	$ucommt = $genuser[3];
	if ($uvalid == "") {
		$uvalid = "-";
	} else {
		$uvalid = $uvalid;
	}
	$uprice = explode("!",$genuser1[3])[0];
	if ($uprice == "0") {
		$uprice = "-";
	} else {
		$uprice = $uprice;
	}
	$suprice = explode("!",$genuser1[3])[1];
	if ($suprice == "0") {
		$suprice = "-";
	} else {
		$suprice = $suprice;
	}
	$utlimit = $genuser1[4];
	if ($utlimit == "0") {
		$utlimit = "-";
	} else {
		$utlimit = $utlimit;
	}
	$udlimit = $genuser1[5];
	if ($udlimit == "0") {
		$udlimit = "-";
	} else {
		$udlimit = formatBytes($udlimit, 2);
	}
	$ulock = $genuser1[6];
	//$urlprint = "$umode-$ucode-$udate-$ucommt";
	$urlprint = explode("|", decrypt($genu))[0];
	if ($currency == in_array($currency, $cekindo['indo'])) {
		$uprice = $currency . " " . number_format((float)$uprice, 0, ",", ".");
		$suprice = $currency . " " . number_format((float)$suprice, 0, ",", ".");
	} else {
		$uprice = $currency . " " . number_format((float)$uprice);
		$suprice = $currency . " " . number_format((float)$suprice);

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
	<div>
        <?php if ($_SESSION['ubp'] != "") {
            echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=" . $_SESSION['ubp'] . "&session=" . $session . "'> <i class='fa fa-close'></i> ".$_close." X</a>";
        } elseif ($_SESSION['vcr'] = "active") {
            echo "    <a class='btn bg-warning' href='./?hotspot=users-by-profile&session=" . $session . "'> <i class='fa fa-close'></i> ".$_close." Y</a>";
        } else {
            echo "    <a class='btn bg-warning' href='./?hotspot=users&profile=all&session=" . $session . "'> <i class='fa fa-close'></i> ".$_close." Z</a>";
        } ?>
        <button type="submit" name="save" onclick="loader()" class="btn bg-primary" title="Generate User"> <i class="fa fa-save"></i> <?= $_generate ?></button>
        <a class="btn bg-secondary" title="Print Default" href="./voucher/print.php?id=<?= $urlprint; ?>&qr=no&session=<?= $session; ?>" target="_blank"> <i class="fa fa-print"></i> <?= $_print ?></a>
        <a class="btn bg-danger" title="Print QR" href="./voucher/print.php?id=<?= $urlprint; ?>&qr=yes&session=<?= $session; ?>" target="_blank"> <i class="fa fa-qrcode"></i> <?= $_print_qr ?></a>
        <a class="btn bg-info" title="Print Small" href="./voucher/print.php?id=<?= $urlprint; ?>&small=yes&session=<?= $session; ?>" target="_blank"> <i class="fa fa-print"></i> <?= $_print_small ?></a>
    </div>
<table class="table">
  <tr>
    <td class="align-middle"><?= $_qty ?></td><td><div><input class="form-control " type="number" name="qty" min="1" max="500" value="1" required="1"></div></td>
  </tr>
    <tr>
        <td class="align-middle"><label for="server">Server</label></td>
        <td>
            <input id="server" type="text" class="form-control" name="server" value="all" readonly/>
        </td>
    </tr>
    <tr>
        <td class="align-middle"><label for="uprofile">Profile</label></td>
        <td>
            <select class="form-control" id="uprofile" name="uprofile" required="required" onchange="setPrefix(this)">
                <option value="">- Select Below -</option>
                <?php foreach ($profiles as $k=>$v) { ?>
                    <option value="<?= $k ?>" data-prefix="<?= $v['prefix'] ?>"><?= $k ?></option>
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
   <tr >
    <td  colspan="4" class="align-middle w-12"  id="GetValidPrice">
    	<?php if ($genprof != "") {
					echo $ValidPrice;
				} ?>
    </td>
  </tr>
</table>
</form>
</div>
</div>
</div>

<div class="col-4">
	<div class="card">
		<div class="card-header">
			<h3><i class="fa fa-ticket"></i> <?= $_last_generate ?></h3>
		</div>
		<div class="card-body">
<table class="table table-bordered">
  <tr>
  	<td><?= $_generate_code ?></td><td><?= $ucode ?></td>
  </tr>
  <tr>
  	<td><?= $_date ?></td><td><?= $udate ?></td>
  </tr>
  <tr>
  	<td><?= $_profile ?></td><td><?= $uprofile ?></td>
  </tr>
  <tr>
  	<td><?= $_validity ?></td><td><?= $uvalid ?></td>
  <tr>
  	<td><?= $_time_limit ?></td><td><?= $utlimit ?></td>
  </tr>
  <tr>
  	<td><?= $_data_limit ?></td><td><?= $udlimit ?></td>
  </tr>
  <tr>
  	<td><?= $_price ?></td><td><?= $uprice ?></td>
  </tr>
  <tr>
  	<td><?= $_selling_price ?></td><td><?= $suprice ?></td>
  </tr>
  <tr>
  	<td><?= $_lock_user ?></td><td><?= $ulock ?></td>
  </tr>
  <tr>
    <td colspan="2">
		<p style="padding:0px 5px;">
      <?= $_format_time_limit ?>
    </p>
    <p style="padding:0px 5px;">
      <?= $_details_add_user ?>
    </p>
    </td>
  </tr>
</table>
</div>
</div>
</div>
    <script>
        // get valid $ price
        function GetVP() {
            var prof = document.getElementById('uprof').value;
            $("#GetValidPrice").load("./process/getvalidprice.php?name=" + prof + "&session=<?= $session; ?> #getdata");
        }

        function setPrefix(select) {
            const option = select.options[select.selectedIndex];
            const prefix = option.getAttribute("data-prefix") || "";

            const inputPrefix = document.getElementById("prefix");
            inputPrefix.value = prefix;
        }

    </script>
</div>

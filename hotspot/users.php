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

use classes\Html;
use classes\VoucherTemplate;

require_once __DIR__.'/../init.php';

ini_set('max_execution_time', 300);

if (!isset($_SESSION["mikhmon"])) {
  header("Location:../admin.php?id=login");
} else {

    require_once 'helper-methods.php';
    require_once 'utils.php';

    $exp = get_parameter("exp");
    $fixUptime = get_parameter("fixUptime");

    $cfg_profiles = hotspot_config("profiles");

    $params = [];
    if (!$prof) $prof = "all";
    if ($prof != "all") $params["?profile"] = "$prof";
    if ($comm != "") $params["?comment"] = "$comm";

    $filtered_users = [];
    $getuser = RouterosAPI::getInstance()->comm("/ip/hotspot/user/print", $params);
    foreach ($getuser as $u) {
        $exists = false;
        foreach ($cfg_profiles as $k=>$v) {
            if (isset($u['profile']) && $u['profile']===$k){
                $exists = true;
            }
        }
        if ($exists) $filtered_users[] = $u;
    }

    if ($fixUptime && $prof != "all"){
        fix_uptime($filtered_users, $prof);
    }

    $getuser = $filtered_users;
    $TotalReg = count($getuser);

    $getprofile = RouterosAPI::getInstance()->comm("/ip/hotspot/user/profile/print");

    $comment_group = get_comment_group($prof);

    $voucher_templates = VoucherTemplate::fetchAllNames();

}
?>

<div class="row">
<div class="col-12">
<div class="card">
<div class="card-header">
    <h3><i class="fa fa-users"></i> <?= $_users ?>
      <span style="font-size: 14px">
         &nbsp; | &nbsp; <a href="./?hotspot-user=add&session=<?= $session; ?>" title="Add User"><i class="fa fa-user-plus"></i> <?= $_add ?></a>
        &nbsp; | &nbsp; <a href="./?hotspot-user=generate&session=<?= $session; ?>" title="Generate User"><i class="fa fa-users"></i> <?= $_generate ?></a>
         &nbsp; | &nbsp; <a href="<?= str_replace("=users", "=export-users", $url); ?>&export=script" title="Download User List as Mikrotik Script"><i class="fa fa-download"></i> Script</a>&nbsp; | &nbsp; <a href="<?= str_replace("=users", "=export-users", $url); ?>&export=csv" title="Download User List as CSV"><i class="fa fa-download"></i> CSV</a>
        </span>  &nbsp;
        <small id="loader" style="display: none;" ><i><i class='fa fa-circle-o-notch fa-spin'></i> <?= $_processing ?> </i></small>
    </h3>
    
</div>
<div class="card-body">
    <div class="row">
        <div class="col-8 pd-t-5 pd-b-5">
            <div class="input-group">
                <div class="input-group-3 col-box-3">
                    <input id="filterTable" type="text" style="height: 30px" class="group-item group-item-l" placeholder="<?= $_search ?>">
                </div>
                <div class="input-group-3 col-box-3">
                    <select class="group-item group-item-m" name="profile" onchange="refreshPage(true)" title="Filter by Profile">
                        <?php echo Html::option("all", "- all profile -", $prof); ?>
                        <?php
                        foreach ($getprofile as $p) {
                            echo Html::option($p['name'], $p['name'], $prof);
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group-3 col-box-3">
                    <select class="group-item group-item-m" id="comment" name="comment" onchange="refreshPage()">
                        <?php echo Html::option("", "- all comment -", $comm); ?>
                        <?php
                        foreach ($comment_group as $tcomment => $value) {
                            if (is_numeric(substr($tcomment, 3, 3))) {
                                $label = $tcomment . " " . $value['profile'] . " [" . $value['count'] . "]";
                                echo Html::option($tcomment, $label, $comm);
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="input-group-3 col-box-3">
                    <select id="template" class="group-item group-item-r" name="template" onchange="printVoucher()">
                        <?= Html::option("", "Generate Voucher", "") ?>
                        <?php foreach ($voucher_templates as $t) { ?>
                            <?= Html::option("$t", "$t") ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="input-group-4 col-box-4 col-4">
            <?php if ($comm != "") { ?>
                <button class="btn bg-red" onclick="deleteUserByComment('<?= $session ?>','<?= $comm ?>')" title="Remove user by comment <?= $comm; ?>"><i class="fa fa-trash"></i> <?= $_by_comment ?></button>
            <?php } else if ($exp == "1") { ?>
                <button class="btn bg-red" onclick="deleteExpiredUsers('<?= $session ?>')" title="Remove user expired"><i class="fa fa-trash"></i> Expired Users</button>
            <?php } ?>
<!--            <button class="btn bg-primary" title='Print' onclick="printVoucher('qr','no');"><i class="fa fa-print"></i> Print default</button>-->
        </div>
    </div>
<div class="overflow mr-t-10 box-bordered" style="max-height: 75vh">
<table id="dataTable" class="table table-bordered table-hover text-nowrap">
  <thead>
  <tr>
    <th style="min-width:50px;" class="align-middle text-center" id="cuser"><?= $TotalReg; ?></th>
    <th style="min-width:50px;" class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Server</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> <?= $_name ?></th>
    <th>Print</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> <?= $_profile ?></th>
	  <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> Mac Address</th>
    <th class="text-right align-middle pointer" title="Click to sort"><i class="fa fa-sort"></i> <?= $_uptime_user ?></th>
    <th class="text-right align-middle pointer" title="Click to sort"><i class="fa fa-sort"></i> Bytes In</th>
    <th class="text-right align-middle pointer" title="Click to sort"><i class="fa fa-sort"></i> Bytes Out</th>
    <th class="pointer" title="Click to sort"><i class="fa fa-sort"></i> <?= $_comment ?></th>
    </tr>
  </thead>
  <tbody id="tbody">
<?php
for ($i = 0; $i < $TotalReg; $i++) {
  $userdetails = $getuser[$i];
  $uid = $userdetails['.id'];
  $userver = $userdetails['server'] ?? "all";
  $uname = $userdetails['name'];
  $upass = $userdetails['password'];
  $uprofile = $userdetails['profile'];
  $umacadd = $userdetails['mac-address'] ?? "";
  $uuptime = formatDTM($userdetails['uptime']);
  $ubytesi = formatBytes($userdetails['bytes-in'], 2);
  $ubyteso = formatBytes($userdetails['bytes-out'], 2);

  $ucomment = $userdetails['comment'];
  $udisabled = $userdetails['disabled'];
  $utimelimit = $userdetails['limit-uptime'];
  if ($utimelimit == '1s') {
    $utimelimit = ' expired';
  } else {
    $utimelimit = ' ' . $utimelimit;
  }
  $udatalimit = $userdetails['limit-bytes-total'] ?? "";
  if ($udatalimit == '') {
    $udatalimit = '';
  } else {
    $udatalimit = ' ' . formatBytes($udatalimit, 2);
  }

  ?>
    <tr>
        <td style='text-align:center;'>
            <i class='fa fa-minus-square text-danger pointer' onclick="if(confirm('Are you sure to delete username (<?= $uname; ?>)?')){loadpage('./?remove-hotspot-user=<?= $uid; ?>&session=<?= $session; ?>')}" title='Remove <?= $uname; ?>'></i>
            <?php
            if ($udisabled == "true") {
                $uriprocess = "'./?enable-hotspot-user=" . $uid . "&session=" . $session."'";
                echo '<span class="text-warning pointer" title="Enable User ' . $uname . '"  onclick="loadpage('.$uriprocess.')"><i class="fa fa-lock "></i></span>';
            } else {
                $uriprocess = "'./?disable-hotspot-user=" . $uid . "&session=" . $session."'";
                echo '<span class="pointer" title="Disable User ' . $uname . '"  onclick="loadpage('.$uriprocess.')"><i class="fa fa-unlock "></i></span>';
            }
            ?>
        </td>
        <td><?= $userver ?></td>
<?php
   if ($uname == $upass) {
    $usermode = "vc";
  } else {
    $usermode = "up";
  }
  $popup = "javascript:window.open('./voucher/print-new.php?user=" . $uname . "&session=" . $session . "','_blank','width=320,height=550').print();";
?>
        <td>
            <a title='Open User <?= $uname ?>' href=./?hotspot-user=<?= $uid ?>&session=<?= $session ?>><i class='fa fa-edit'></i> <?= $uname ?></a>
        </td>
        <td style="text-align: center">
            <a title="Print <?= $uname ?>" href="<?= $popup ?>"><i class="fa fa-print"></i></a>
        </td>
<?php
  echo "<td>" . $uprofile . "</td>";
  echo "<td style=' text-align:left'>" . $umacadd . "</td>";
  echo "<td style=' text-align:right'>" . $uuptime . "</td>";
  echo "<td style=' text-align:right'>" . $ubytesi . "</td>";
  echo "<td style=' text-align:right'>" . $ubyteso . "</td>";
  echo "<td>";
  if ($uname == "default-trial") {
  } else if (substr($ucomment,0,3) == "vc-" || substr($ucomment,0,3) == "up-") {
    echo "<a href=./?hotspot=users&comment=" . $ucomment . "&session=" . $session . " title='Filter by " . $ucomment . "'><i class='fa fa-search'></i> ". $ucomment." ". $udatalimit ." ".$utimelimit . "</a>";
  } else if ($utimelimit == ' expired') {
    echo "<a href=./?hotspot=users&profile=all&exp=1&session=" . $session . " title='Filter by expired'><i class='fa fa-search'></i> " . $ucomment." ". $udatalimit ." ".$utimelimit . "</a>";
  }else{
    echo $ucomment.' ';
  }
  echo  "</td>";


}
?>
  </tr>
  </tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<script>

    const session = '<?= $session;?>'

    function refreshPage(resetComment) {
        const comment = $("[name=comment]").val();
        const profile = $("[name=profile]").val();

        const params = new URLSearchParams();
        if (profile) params.set('profile', profile);
        if (comment && !resetComment) params.set('comment', comment);

        window.location = `./?hotspot=users&session=${session}&${params.toString()}`;
    }

    function printVoucher() {
        const templateEl = document.getElementById('template');
        const template = templateEl.value;
        const comm = document.getElementById('comment').value;

        const url = "./voucher/print-new.php?id=" + comm + "&template=" + template + "&session=<?= $session; ?>";
        if (comm === "") {
            alert('Silakan pilih salah satu Comment terlebih dulu!');
        } else {
            const win = window.open(url, '_blank');
            win.focus();
        }
        templateEl.value = "";
    }

    function deleteUserByComment(session, comment) {
        if (confirm(`Are you sure to delete username by comment (${comment})?`)) {
            loadpage(`./?remove-hotspot-user-by-comment=${comment}&session=${session}`);
            loader();
        }
    }

    function deleteExpiredUsers(session) {
        if (confirm('Are you sure to delete users?')) {
            loadpage(`./?remove-hotspot-user-expired=1&session=${session}`);
            loader();
        }
    }
</script>

	


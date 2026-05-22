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

require_once __DIR__ . '/../init.php';

if (!isset($_SESSION["mikhmon"])) {
	header("Location:../admin.php?id=login");
} else {
    // load session MikroTik
	$session = $_GET['session'];

    // load config
    require_once __DIR__ . '/../voucher/utils.php';
    include(__DIR__.'/../include/config.php');
    include(__DIR__.'/../include/readcfg.php');

    $template_param = get_parameter("template");
    $read_from_file = get_parameter("read-from-file");

    $templates = VoucherTemplate::fetchAll();
    $template_names = [];
    $template = null;
    /** @var $t VoucherTemplate */
    foreach ($templates as $t) {
        $template_names[] = $t->name;
        if ($t->name === $template_param) $template = $t;
    }

    $template_name = "";
    $template_header = "";
    $template_row = "";
    $template_footer = "";
    if ($template){
        $template_header = $template->header;
        $template_row = $template->row;
        $template_footer = $template->footer;
        $template_name = $template->name;
    }

    if (isset($_POST['save'])) {
        $voucher = new VoucherTemplate($_POST);
        $r = $voucher->saveOrUpdate();
        echo "<script>window.location='./?hotspot=template-editor&template=".$voucher->name."&session=".$session."'</script>";

    }

    if (isset($_POST['save-to-file'])) {
        $voucher = new VoucherTemplate($_POST);
        $data = (array)$voucher;
        $data['header'] = base64_encode($voucher->header);
        $data['row'] = base64_encode($voucher->row);
        $data['footer'] = base64_encode($voucher->footer);

        $content = "<?php\n\nreturn " . var_export($data, true) . ";\n";

        $path = __DIR__ . "/../voucher/template-$voucher->id.php";
        file_put_contents($path, $content);

        echo "<script>window.location='./?hotspot=template-editor&template=" . $voucher->name . "&session=" . $session . "'</script>";
    }

}
?>
<!-- Create a simple CodeMirror instance -->
<link rel="stylesheet" href="./css/editor.min.css">
<script src="./js/editor.min.js"></script>

<style>
    .editor-wrapper.row > .CodeMirror{
        height: 150px;

    }
    .CodeMirror {
        border: 1px solid #2f353a;
        height: 300px;
    }
    textarea{
        font-size:12px;
        border: 1px solid #2f353a;
    }

    .template-editor{
        width:100%;
    }

</style>
<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa fa-edit"></i> <?= $_template_editor ?></h3>
            </div>
            <div class="card-body">
                <form autocomplete="off" method="post" action="">
                    <input type="hidden" name="id" value="<?= $template != null ? $template->id : 0 ?>">
                    <table class="table">
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-8 pd-t-5 pd-b-5 col-box-12">
                                        <div class="input-group">
                                            <div class="input-group-3">
                                                <div class="group-item group-item-l pd-2p5 text-center" style="height: 30px">Template</div>
                                            </div>
                                            <div class="input-group-3">
                                                <select class="group-item group-item-r" name="template" onchange="changeTemplate('<?= $session ?>')">
                                                    <option value="">- new -</option>
                                                    <?php foreach ($template_names as $t) { ?>
                                                        <?= Html::option($t, $t, $template_param) ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4 col-box-12">
                                        <button type="submit" title="Save template" class="btn bg-primary" name="save"><i class="fa fa-save"></i> <?= $_save ?></button>
                                        <button type="submit" title="Save template" class="btn bg-primary" name="save-to-file"><i class="fa fa-save"></i> Save As Master</button>
                                        <a class="btn bg-green" onclick="openPreview('<?= $session ?>')" title="View voucher"><i class="fa fa-image"></i> Preview</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <label>
                        Name :
                        <input type="text" class="form-control" name="name" value="<?= $template_name ?>" <?= $template_name == "default" ? "readonly":"" ?>>
                    </label>
                    <div class="editor-wrapper">
                        <label for="row-editor">Header :</label>
                        <textarea id="header-editor" name="header" class="bg-dark template-editor" height="300"><?= $template_header ?></textarea>
                    </div>
                    <div class="editor-wrapper row">
                        <label for="row-editor">Row :</label>
                        <textarea id="row-editor" name="row" class="bg-dark template-editor" height="200px"><?= $template_row ?></textarea>
                    </div>
                    <div class="editor-wrapper">
                        <label for="footer-editor">Footer :</label>
                        <textarea id="footer-editor" name="footer" class="bg-dark template-editor" height="300px"><?= $template_footer ?></textarea>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-3">
<!--        <div class="card">-->
<!--            <div class="card-header">-->
<!--                <h3>Variable</h3>-->
<!--            </div>-->
<!--            <div class="card-body">-->
<!--				<textarea id="var" class="bg-dark" readonly rows=39 style="width:100%" disabled>-->
<!--	        		--><?php //= file_get_contents('./voucher/variable.php'); ?>
<!--	    		</textarea>-->
<!--            </div>-->
<!--        </div>-->
    </div>
</div>

<script>

    const editorConfig = {
        lineNumbers: true,
        matchBrackets: true,
        mode: "application/x-httpd-php",
        indentUnit: 4,
        indentWithTabs: true,
        lineWrapping: true,
        viewportMargin: Infinity,
        matchTags: {
            bothTags: true
        },
        extraKeys: {
            "Ctrl-J": "toMatchingTag"
        }
    }

    const headerEditor = CodeMirror.fromTextArea(document.getElementById("header-editor"), editorConfig);
    const rowEditor = CodeMirror.fromTextArea(document.getElementById("row-editor"), editorConfig);
    const footerEditor = CodeMirror.fromTextArea(document.getElementById("footer-editor"), editorConfig);

    headerEditor.setOption("theme", "material");
    rowEditor.setOption("theme", "material");
    footerEditor.setOption("theme", "material");

    function openPreview(session) {
        const templateEl = document.querySelector("[name='template']");
        const template = templateEl.value;
        window.open(`./voucher/voucher-preview.php?usermode=up&template=${template}&session=${session}`,'_blank','width=310,height=310')
    }

    function changeTemplate(session) {
        const templateEl = document.querySelector("[name='template']");
        const template = templateEl.value;

        window.location = `./?hotspot=template-editor&template=${template}&session=${session}`
    }

</script>



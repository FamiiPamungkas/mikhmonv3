<?php

$h = base64_encode(<<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="pragma" content="no-cache">
    <title>Voucher-<?= $hotspotname . "-" . $getuprofile . "-" . $id; ?>"</title>
    <style>
        @page {
            size: auto;
            margin: 15mm 3mm 3mm 7mm;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                background: #fff;
            }

            table {
                page-break-after: auto
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto
            }

            td {
                page-break-inside: avoid;
                page-break-after: auto
            }

            thead {
                display: table-header-group
            }

            tfoot {
                display: table-footer-group
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            /*background: #ebebeb;*/
            padding: 16px;
            font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
        }

        #result {
            display: block;
        }

        .bg-5j {
            background-color: #00C49A !important;
        }

        .bg-1h {
            background-color: #ff9451 !important;
        }

        .bg-1b {
            background-color: #fdbeff !important;
        }

        .bd-5j {
            border-color: #8d99ae !important;
        }

        .bd-1h {
            border-color: #ff9a51 !important;
        }

        .bd-1b {
            border-color: #9e2ca1 !important;
        }

        .c-5j {
            color: #00C49A !important;
        }

        .c-1h {
            color: #ff9a51 !important;
        }

        .c-1b {
            color: #9e2ca1 !important;
        }

        .card {
            float: left;
            width: 220px;
            border: solid 1px black;
            border-radius: 5px;
            position: relative;
            justify-content: space-between;
            font-size: .7rem;
            margin: 3px;
            display: inline-block;
        }

        .card .left-area {
            padding: 6px 6px 3px;
            width: 75px;
            /*position: absolute;*/
        }

        .card .right-area {
            padding: 0 0 3px 0;
            width: 70%;
            text-align: right;
        }

        .card .left-area .price {
            font-size: 1.2rem;
            line-height: 1rem;
            font-weight: bold;
        }

        .card .left-area .currency {
            font-weight: bold;
        }

        .bg-w {
            position: absolute;
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.80);
            clip-path: polygon(40% 0, 100% 0, 100% 100%, 30% 100%);
            left: 0;
            top: 0;
            z-index: 0;
        }

        .bg {
            z-index: 0;
            background-color: rgb(211, 0, 0);
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            clip-path: polygon(30% 0, 100% 0, 100% 100%, 30% 100%, 35% 50%);
        }

        .data {
            position: relative;
            z-index: 100;
        }

        .bold {
            font-weight: bold;
        }

        .up {
            width: 100px;
        }

        .left {
            text-align: left;
        }

        table td.tight {
            border-spacing: 0;
            line-height: .7rem;
        }

        .brand {
            position: absolute;
            top: 2px;
            right: 5px;
        }

        .d-none {
            display: none;
        }
    </style>
</head>
<body>
<div id="data" class="d-none">
HTML
);

$r = base64_encode(
    <<<'HTML'
<div data-item>
    <p data-profile>{profile}</p>
    <p data-price>{price}</p>
    <p data-user>{username}</p>
    <p data-pass>{password}</p>
</div>
HTML
);

$f = base64_encode(
    <<<'HTML'
</div>
<div id="result"></div>
<table id="voucher" class="card d-none">
    <tr>
        <td class="left-area">
            <div class="currency bold" data-duration></div>
            <div class="price" data-price>------</div>
            <div class="currency">RUPIAH</div>
        </td>
        <td class="right-area">
            <div class="bg"></div>
            <div class="bg-w"></div>
            <span class="brand bold" data-brand>FamiiComp</span>
            <table class="data">
                <tr>
                    <td colspan="3" class="bold">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;</td>
                    <td class="up left tight">Username</td>
                    <td class="up left tight">Password</td>
                </tr>
                <tr>
                    <td></td>
                    <td class="up left tight">
                        <span class="bold" data-username>-----</span>
                    </td>
                    <td class="up left tight">
                        <span class="bold" data-password>-----</span>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<script>

    const dataEl = document.getElementById("data");
    const itemEl = document.querySelector("[data-item]");
    const voucherEl = document.getElementById("voucher");
    const resultEl = document.getElementById("result");
    const count = 90;

    // generateDummyData();
    createVoucher();

    function randomString(length) {
        if (!length) length = 4;

        let string = "";
        while (string.length < length) {
            string += randomChar();
        }
        return string;
    }

    function randomChar() {
        const alphanum = "abcdefghijklmnopqrstu1234567890";
        const array = alphanum.split("");
        const s = Math.random() * array.length;
        const index = Math.round(s);
        return array[index] ?? "";
    }

    function generateDummyData() {
        for (let i = 1; i < count; i++) {
            const clone = itemEl.cloneNode(true);

            const profileEl = clone.querySelector("[data-profile]");
            const priceEl = clone.querySelector("[data-price]");
            const userEl = clone.querySelector("[data-user]");
            const passEl = clone.querySelector("[data-pass]");

            let price = 2000;
            let duration = "";
            let profile = "";
            const rand = Math.random() * 10;
            if (rand < 4) {
                price = 2000
                duration = "5 JAM";
                profile = "5.J.R"
            } else if (rand > 5) {
                price = 30000
                duration = "1 BULAN";
                profile = "1.B.R"
            } else {
                price = 4000;
                duration = "1 HARI";
                profile = "1.H.R"
            }

            priceEl.textContent = price;
            profileEl.textContent = profile;
            userEl.textContent = randomString(6);
            passEl.textContent = randomString(4);

            dataEl.append(clone);

        }
        createVoucher();
    }

    function parseDuration(profile) {
        try {
            const array = profile.split(".");
            switch (array[1]) {
                case "H":
                    return array[0] + " HARI";
                case "J":
                    return array[0] + " JAM";
                case "M":
                    return array[0] + " MINGGU";
                case "B":
                    return array[0] + " BULAN";
                default:
                    return "???";
            }
        } catch (e) {
            return "?"
        }
    }

    function parseClassname(profile) {
        try {
            const array = profile.split(".");
            switch (array[1]) {
                case "H":
                    return "1h";
                case "J":
                    return "5j";
                case "M":
                    return "1h";
                case "B":
                    return "1b";
                default:
                    return "???";
            }
        } catch (e) {
            return "?"
        }
    }

    function createVoucher() {
        let i = 1;
        for (const item of dataEl.children) {
            const clone = voucherEl.cloneNode(true);
            clone.classList.remove("d-none");

            const priceEl = clone.querySelector("[data-price]");
            const usernameEl = clone.querySelector("[data-username]");
            const passwordEl = clone.querySelector("[data-password]");
            const durationEl = clone.querySelector("[data-duration]");
            const brandEl = clone.querySelector("[data-brand]");
            const backgroundEl = clone.querySelector("[class=bg]");

            const priceStr = item.querySelector("[data-price]");
            const price = parseFloat(priceStr.textContent) || 0;
            priceEl.innerHTML = new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: 0,
            }).format(price);

            const profile = item.querySelector("[data-profile]").textContent;
            passwordEl.innerHTML = item.querySelector("[data-pass]").textContent;
            usernameEl.innerHTML = item.querySelector("[data-user]").textContent;
            durationEl.innerHTML = parseDuration(profile);
            brandEl.innerHTML = brandEl.innerHTML + ` [`+i+`]`;

            const cls = parseClassname(profile);
            clone.classList.add(`bd-`+cls);
            backgroundEl.classList.add(`bg-`+cls);
            brandEl.classList.add(`c-`+cls);

            resultEl.append(clone);
            i++;
        }
    }

</script>
</body>
</html>
HTML
);


return [
    "default" => [
        'header' => $h,
        'row' => $r,
        'footer' => $f
    ]
];

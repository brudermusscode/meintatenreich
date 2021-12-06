<?php

$ptit = "MeinTatenReich wird zur Zeit gewartet!";
$pid = "maintenance";

include_once $_SERVER["DOCUMENT_ROOT"] . "/maintenance/head.php";

?>

<style>
    * {
        font-family: Arial, sans-serif;
    }

    body {
        overflow: auto;
        overflow-x: hidden;
    }

    strong {
        font-weight: bold;
    }

    input {
        border: 0;
        border-bottom: 1px solid rgba(0, 0, 0, .24);
        line-height: 42px;
        height: 42px;
        width: 100%;
        padding: 0;
        outline: none;
    }

    input:focus,
    input:hover {
        border-bottom: 1px solid #2195F2;
    }

    #app {
        width: auto;
    }

    .mmain {
        margin: 0 auto;
        margin-top: 78px;
        margin-bottom: 62px;
        width: 690px;
    }

    .mmain .logo-outer {
        position: relative;
        width: 262px;
        height: 127px;
        margin-bottom: 52px;
    }

    .mmain .logo-outer .actual {
        height: 100%;
        width: 100%;
    }

    .mmain .mm-box {
        margin-bottom: 38px;
        width: 100%;
    }

    .mmain .mm-box.shops {
        float: none;
        width: 100%;
    }

    .mmain .mm-box .mmb-inr .hd {
        line-height: 21px;
        margin-bottom: 18px;
    }

    .mmain .mm-box .mmb-inr .hd p {
        font-size: 1.8em;
        font-family: 'Indie Flower', sans-serif;
        text-align: left;
        padding-left: 24px;
        text-shadow: 0 1px 1px white;
    }

    .mmain .mm-box .mmb-inr .body {
        line-height: 21px;
    }

    .mmain .mm-box .mmb-inr .body p {
        color: #333;
        font-size: 1em;
    }

    @media screen and (max-width:calc(690px + 48px)) {
        .mmain {
            width: calc(100% - 48px);
        }
    }

    .button-outer {
        position: relative;
    }

    .button-outer .button {
        width: calc(50% - 3px);
        background: #BA8F5E;
        cursor: pointer;
    }

    .button-outer .button:hover {
        opacity: .8;
    }

    .button-outer .button:active {
        opacity: .6;
    }

    .button-outer .button p {
        color: white !important;
        text-align: center;
        line-height: 42px;
        font-size: 1.2em !important;
        font-weight: 300 !important;
        padding: 0 12px;
        width: calc(100% - 24px);
    }

    .button-outer .button:nth-of-type(2) {
        margin-left: 6px;
    }

    .dot {
        background: rgba(255, 255, 255, .84);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .42);
        border-radius: 50%;
        height: 6px;
        width: 6px;
    }
</style>

<div class="mmain" style="top:50%;left:50%;transform:translate(-50%, -50%);position:fixed;width:calc(100% - 48px);margin:0;">
    <div class="logo-outer almid-h">
        <div style="background:url(/assets/web/img/global/pdf_logo.png) center no-repeat;background-size:cover;height:140px;width:140px;transform:translateX(-50%);left:50%;position:absolute;"></div>
    </div>

    <div data-react="maintenance">

        <p style="text-align:center;color:white;text-shadow:2px 1px 2px rgba(0,0,0,.48);font-size:2em;line-height:1.2em;">Kurze Wartungsarbeiten!</p>
        <p style="text-align:center;color:white;text-shadow:1px 1px 2px rgba(0,0,0,.48);font-size:1em;line-height:1.2em;margin-top:8px;">2021 &copy; MeinTatenReich</p>

    </div>

</div>

</body>

</html>
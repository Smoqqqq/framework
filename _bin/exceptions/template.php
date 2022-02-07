<div id="error">
    <div id="head">
        <p>An Error occured:</p>
        <div id="title">
            <h1><?= $errstr ?> <small>(errcode: <?= $errno ?>)</small></h1>
        </div>
        <div id="subtitle">
            <h2><b>On file <?= $errfile ?><br> At line: <?= $errline ?></h2>
        </div>
    </div>
</div>

<style>
    *{
        margin: 0px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #error {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100vw;
        min-height: 100vh;
        background: #CD6155;
    }
    
    #error #head {
        background: #922B21;
        border: 2px solid #922B21;
        padding: 20px;
    }

    #error #head h1{
        color: #280a07;
        font-size: 30px;
        margin-bottom: 10px;
    }
    #error #head h2{
        color: #381a17;
        font-size: 22px;
        font-weight: 400;
    }
    #error #head p {
        color: #280a07;
        font-size: 18px;
        font-weight: bold;
    }
</style>
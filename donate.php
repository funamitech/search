<?php
    require "misc/header.php";

    // Feel free to add your donation options here, but please don't remove mine.
    // Why not? I'm gonna remove yours. LMAO jkjk I'll leave them.
?>

    <title>YuruSearch - Donate</title>
    </head>
    <body>
        <div class="misc-container">
	    <h1>Donate to YuruVerse</h1>
	      <a href="https://toss.me/yuifmirror">Toss (South Korean residents only)</a>
	      <a href="https://github.com/sponsors/NoaHimesaka1873">GitHub Sponsors</a>
               <h1>Donate to the original developer of LibreX, a project LibreY tries to improve.</h1>
               <h2>Bitcoin (BTC):</h2>
               <p>bc1qs43kh6tvhch02dtsp7x7hcrwj8fwe4rzy7lp0h</p>
               <img src="static/images/btc.png" alt="btc qr code" width="150" height="150"/>
               <h2>Monero (XMR):</h2>
               <p>41dGQr9EwZBfYBY3fibTtJZYfssfRuzJZDSVDeneoVcgckehK3BiLxAV4FvEVJiVqdiW996zvMxhFB8G8ot9nBFqQ84VkuC</p>
               <img src="static/images/xmr.png" alt="xmr qr code" width="150" height="150"/>
               <h1>Donate to the person that forked LibreX into LibreY</h1>
               <a href="https://ahwx.org/donate.php">Click here</a>
        </div>

        <hr class="small-line" />

    <div class="donate-container">
      <!-- librex dev -->
      <h2>
<?php printftext("donate_original_developer", "Libre<span class=\"Y\">X</span>")?>
      </h2>

      <div class="flexbox-column">
        <div class="qr-box">
          <div class="inner-wrap">
            <h3>Bitcoin [BTC]</h3>
            <p>bc1qs43kh6tvhch02dtsp7x7hcrwj8fwe4rzy7lp0h</p>
          </div>

          <img
            src="/static/images/btc.png"
            height="160"
            width="160"
            alt="btc qr code"
          />
        </div>
        <div class="qr-box">
          <div class="inner-wrap">
            <h3>Monero [XMR]</h3>
            <p>
              41dGQr9EwZBfYBY3fibTtJZYfssfRuzJZDSVDeneoVcgckehK3BiLxAV4FvEVJiVqdiW996zvMxhFB8G8ot9nBFqQ84VkuC
            </p>
          </div>
          <img
            src="/static/images/xmr.png"
            height="160"
            width="160"
            alt="xmr qr code (hnhx)"
          />


        <!-- librey dev -->
      <h2>
<?php printftext("donate_fork", "Libre<span class=\"Y\">X</span>")?>
      </h2>

        <div class="qr-box">
          <div class="inner-wrap">
            <h3>Monero [XMR]</h3>
              <p>
                4ArntPzKpu32s4z2XqYhyaY1eUeUBKtCzJqEqxWtF5mCi5vR6sdhh32Hd2fk9FjeUxYDtaaUexUqoRNxrgfrtuXs4XpgMNJ
              </p>
          </div>
          <img
            src="/static/images/xmr-ahwx.png"
            height="160"
            width="160"
            alt="xmr qr code (ahwx)"
          />
        </div>
          
        <div class="flex-row">
          <a href="https://ko-fi.com/Ahwxorg" target="_blank"
            ><img
              src="/static/images/kofi.png"
              alt="kifi img"
              height="50"
              width="auto"
          /></a>

          <a href="https://www.buymeacoffee.com/ahwx" target="_blank">
            <img
              src="/static/images/buy-me-a-coffee.png"
              height="50"
              width="auto"
              alt="buy-me-a-coffee img"
          /></a>
        </div>
      </div>
    </div>

        

<?php require "misc/footer.php"; ?>

<!DOCTYPE html>
<!-- saved from url=(0063)http://www.kevinlamping.com/deck.narrator.js/sample/sample.html -->
<html class="js flexbox canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths ready"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=1024, user-scalable=no">

  <title>Slide Narration with HTML5 Audio</title>

  <!-- Required stylesheet -->
  <link rel="stylesheet" href="deck.core.css">

  <!-- Extension CSS files go here. Remove or add as needed. -->
  <link rel="stylesheet" href="deck.menu.css">
  <link rel="stylesheet" href="deck.navigation.css">
  <link rel="stylesheet" href="deck.status.css">
  <link rel="stylesheet" href="deck.hash.css">
  <link rel="stylesheet" href="deck.scale.css">
  <link rel="stylesheet" href="deck.automatic.css">

  <link rel="stylesheet" href="deck.narrator.css">

  <!-- Style theme. More available in /themes/style/ or create your own. -->
  <link rel="stylesheet" href="web-2.0.css">

  <!-- Transition theme. More available in /themes/transition/ or create your own. -->
  <link rel="stylesheet" href="horizontal-slide.css">

  <!-- Required Modernizr file -->
  <script src="modernizr.custom.js.descarga"></script>
</head>
<body class="deck-container on-slide-0 on-slide-slide-0" style="">

<!-- Begin slides. Just make elements with a class of slide. -->

<section class="slide deck-current" data-narrator-duration="4.5" data-duration="4500" id="slide-0">
  <div class="deck-slide-scaler"><h1>Slide Narration with HTML5 Audio</h1></div>
</section>

<section class="slide deck-next" data-narrator-duration="4.2" data-duration="4200" id="slide-1">
  <div class="deck-slide-scaler"><h2>Yay Slides!</h2><p>
    <a href="https://twitter.com/unconed/status/340988601741479936">
      <img src="tweet.png" alt="Example tweet with link to slides">
    </a>
  </p></div>

  
</section>

<section class="slide deck-after" data-narrator-duration="4" data-duration="4000" id="slide-2">
  <div class="deck-slide-scaler"><h2>WTF?</h2><p>
    <a href="http://www.slideshare.net/klamping/css-pres">
      <img src="slide.png" alt="Example slide that makes no sense without audio or description">
    </a>
  </p></div>
  
  
</section>

<section class="slide deck-after" data-narrator-duration="8" data-duration="8000" id="slide-3">
  <div class="deck-slide-scaler"><h2>Find Out How to:</h2><p><a href="http://github.com/klamping/deck.narrator.js">Add Narration to your Slide Deck with HTML5 Audio</a></p></div>
  <!-- TODO change to HTML5Hacks.com URL -->
  
</section>

<!-- End slides. -->


<!-- Begin extension snippets. Add or remove as needed. -->

<!-- deck.navigation snippet -->
<a href="http://www.kevinlamping.com/deck.narrator.js/sample/sample.html#" class="deck-prev-link deck-nav-disabled" title="Previous">?</a>
<a href="http://www.kevinlamping.com/deck.narrator.js/sample/sample.html#slide-1" class="deck-next-link" title="Next">?</a>

<!-- deck.status snippet -->
<p class="deck-status">
  <span class="deck-status-current">1</span>
  /
  <span class="deck-status-total">4</span>
</p>

<!-- deck.hash snippet -->
<a href="http://www.kevinlamping.com/deck.narrator.js/sample/" title="Permalink to this slide" class="deck-permalink">#</a>

<!-- deck.narrator snippet -->
<audio controls="" class="deck-narrator-audio" id="narrator-audio">
  <source src="audio.m4a" type="audio/mp4">
  <source src="audio.ogg" type="audio/ogg">
  <track kind="caption" src="transcript.vtt" srclang="en" label="English">
</audio>
<!-- End extension snippets. -->


<!-- Required JS files. -->
<script src="jquery-1.10.1.min.js.descarga"></script>
<script src="deck.core.js.descarga"></script>

<!-- Extension JS files. Add or remove as needed. -->
<script src="deck.core.js.descarga"></script>
<script src="deck.hash.js.descarga"></script>
<script src="deck.menu.js.descarga"></script>
<script src="deck.status.js.descarga"></script>
<script src="deck.navigation.js.descarga"></script>
<script src="deck.scale.js.descarga"></script>
<script src="deck.automatic.js.descarga"></script>
<script src="deck.narrator.js.descarga"></script>

<!-- Initialize the deck. You can put this in an external file if desired. -->
<script>
  $(function() {
    $.extend(true, $.deck.defaults, {
      automatic: {
        startRunning: false,
        cycle: false
      }
    });
    $.deck('.slide');
  });
</script>


</body></html>
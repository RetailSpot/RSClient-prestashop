<!-- Block psretailspotvideo -->
<script type="text/javascript">

  function decodeHtml(html) {
      var txt = document.createElement("textarea");
      txt.innerHTML = html;
      return txt.value;
  }

  var stringInput = decodeHtml('{$rs_videoad_config}');  
  var RetailSpotConfig = JSON.parse(stringInput);
  
  window.onload = function(){
  
    var s = document.createElement("script");
    s.src = "http://localhost:3000/dist/rsplayer.js";
    s.async = true;
    s.onload = function(){
      const RS = window.RetailSpot;

      const RetailSpotConfig = 

      RS.init(RetailSpotConfig);
    }
    
    top.document.body.appendChild(s);
  };
 
</script>
<!-- /Block psretailspotvideo -->
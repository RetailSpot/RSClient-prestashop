<!-- Block psretailspotvideo -->
<script type="text/javascript">
  var RetailSpotConfig = {$rs_videoad_config};
  debugger
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
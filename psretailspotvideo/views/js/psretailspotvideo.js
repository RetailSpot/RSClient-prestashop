  window.onload = function(){
  
    var s = document.createElement("script");
    s.src = "http://localhost:3000/dist/rsplayer.js";
    s.async = true;
    s.onload = function(){
      const RS = window.RetailSpot;

      const RetailSpotConfig = [{
          vastUrl:"https://ads.stickyadstv.com/www/delivery/swfIndex.php?reqType=AdsSetup&protocolVersion=2.0&zoneId=2003",
          width: 320,
          height: 180,
          //CSSSelector: "#target", // ignored by slider ad format
          format: "slider",
          align: "left", // default is bottom right
          //vmargin: 50, // default is 30
          //hmargin: 50, // default is 30
          //anim: "top" // default is 'auto': minimal distance animation
        },
        // {
        //   vastUrl: "https://ads.stickyadstv.com/www/delivery/swfIndex.php?reqType=AdsSetup&protocolVersion=2.0&zoneId=2003",
        //   width: 400,
        //   height: 240,
        //   format: "intext",
        //   //align: "left", // default is bottom right
        //   //vmargin: 50, // default is 30
        //   //hmargin: 50, // default is 30
        //   //anim: "top" // default is 'auto': minimal distance animation
        // },
      ]

      RS.init(RetailSpotConfig);
    }
    
    top.document.body.appendChild(s);
  };
 
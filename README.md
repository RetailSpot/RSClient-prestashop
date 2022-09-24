# RetailSpot Video Advertising - prestashop module

## Description

Provide easy integration for RetailSpot Ads in Prestashop.

Can show two ads at once. Configuration parameters are provided in prestashop module configuration page.
This customized javascrip will be included in the page:

```
        (function(){
          window.RetailSpotConfig = [
            {
              vastUrl: "https://ads.stickyadstv.com/vast/vpaid-adapter/2003",
              format: "slider",
              width: 320,
              height: 180,
              align: "left", // default is bottom right
              vmargin: 50, // default is 30
              hmargin: 50, // default is 30
            },
            {
              vastUrl: "https://ads.stickyadstv.com/vast/vpaid-adapter/2003",
              format: "intext",
              width: 400,
              height: 225,
              CSSSelector: "#target"
            }
          ]

          var s = document.createElement("script");
          s.src = "http://localhost:3000/dist/rsplayer.js";
          document.body.appendChild(s);
        })();
```

## Prerequisites

- install **docker** and **docker compose** for test purpose

## Installing

## Build options

### Build prod version

### Build for dev

#### development environment

Once docker is installed, you can test this module on prestashop 1.6 by running

```
docker-compose up
```

This will run prestashop 1.6 on localhost. Go to localost:8081 to see it.

Admin page can be accessed at

```
localhost:8081/psadmin/index.php
```

Email: demo@prestashop.com
Password: prestashop_demo

see docker-compose.yml to launch and test the latest pretashop version (1.7)  


## Deployment

To deploy a new version,  run the "deploy.sh" script with the new version number:

```
./deploy.sh v1.2
```
vzmon
=====

Solar generation mobile phone monitoring app for volkszaehler.org

![app demo](https://github.com/andig/vzmon/raw/master/img/demo.png)

![colored chart demo](https://github.com/andig/vzmon/raw/master/img/colorchart.png)

## Preparation

1. Install [Volkszähler](volkszaehler.org)
2. Setup your channels- minimum requirement is one channel for solar generation ("Erzeugung" in German) and one for external usage ("Bezug" in German)
3. As there is a bug in the current Volkszähler 2.0 software, make sure to replace /lib/Interpreter/Interpreter.php with the current version from [GitHub](https://github.com/volkszaehler/volkszaehler.org/blob/master/lib/Interpreter/Interpreter.php)

## Setup

### Download and install

1. Download vzmon from GitHub
2. Extract to folder on your webserver (e.g. /var/www/htdocs/vzmon)
3. Make sure installation is accessible via web browser (e.g. http://your-ip/vzmon)

### Weather API

1. Goto [forecast.io](forecast.io), signup and obtain an API key for your weather forecast
2. Open ``js/options.js`` for editing
3. Add API key to ``js/options.js``
4. Add latitide/longitude of your position to ``js/options.js``

### Volkszähler API

1. Add your [volkszaehler.org](volkszaehler.org) installation URL to ``js/options.js``
2. Add the names of your generation and usage channels (not the UUIDs!) to ``js/options.js``
3. If you want to show total consumption, you can add the "totalValue" and "totalValueAtDate" options to indicate at which data your meter had a specific meter reading. This value will be used as offset for your data.

### Use as Smartphone app

1. Open your installation (e.g. http://your-ip/vzmon) in Smartphone web browser
2. Add link to your bookmarks or (on iProducts) home screen.

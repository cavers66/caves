/*
 * SimpleModal Coordinates Converter Form
 * http://www.cavers.de
 *
 * Copyright (c) 2013 Ralf Rötzer
 *
  */

window.addEvent("domready", function ()
{
    $("cave-converter").addEvent("click", function ()
    {
        if (SM)
        {
            SM.show();
        }
        else
        {
            var SM = new SimpleModal({ "width": 550 });
            SM.addButton("Übertragen", "btn primary", function ()
            {
                save();
                this.hide();
            });
            SM.addButton("Abbrechen", "btn");
            SM.show(
            {
                "model": "modal-ajax",
                "title": "Geografischen Koordinaten umrechnen",
                "param": 
                {
                    "url": "system/modules/caves/assets/data/converter.php",
                    "onRequestComplete": function ()
                    {
                        initialise();
                    }
                }
            });    
        }
        
    });
});

// Wird direkt nach  dem laden ausgeführt
function initialise()
{
    var lat = document.forms["tl_cave"].elements["ctrl_latitude"].value;
    var lon = document.forms["tl_cave"].elements["ctrl_longitude"].value;
    lat = lat.replace(/0+$/, "");
    lon = lon.replace(/0+$/, "");
    
    if (lat > 0 && lon > 0)
    {
        document.forms["formD"].elements["latD"].value = lat;
        document.forms["formD"].elements["lonD"].value = lon;        
        convertD();
    }
    checkIsEmpty("formD");
    checkIsEmpty("formDm");
    checkIsEmpty("formDms");
    checkIsEmpty("formGeo");
        
}

// Überprüfen auf leere Felder
function checkIsEmpty(formId)
{
    state = true;
    f = document.forms[formId];
    for (i = 0; i < f.length;i++)
    {
        if (state == true)
        {
            if (f.elements[i].type == "text")
            {
                if (f.elements[i].value == "")
                {
                    state = false;   
                }
            }
        }
        if (f.elements[i].type == "button")
        {
            if (state == false)
            {
                f.elements[i].disabled = true;
            }
            else
            {
                f.elements[i].disabled = false;
            }
        } 
    } 
      
}

// Bereinigt und Validiert eingabe
function cleanInput(strVar,strTyp)
{
    strVar = strVar.replace(/°|"|'/g, "");
    strVar = strVar.replace(/,/g, ".");
    

    switch(strTyp)
    {
        case "Geo":
            match = strVar.search(/[NS]\s[0-6]?[0-9]\s[0-6]?[0-9](\.[0-9]{1,3})?\s[EW]\s[1]?[0-9]{1,2}\s[0-6]?[0-9]?(\.[0-9]{1,3})?/);
            if (match == -1)
            {
                strClean = match;
            }
            else
            {
                strClean = strVar;
            }
            break;
        case "Dm":
            
            match = strVar.search(/([-]?[1]?[0-9]{1,2})\s([0-6]?[0-9])(\.[0-9]{1,3})?/);
            if (match == -1)
            {
                strClean = match;
            }
            else
            {
                strClean = strVar;
            }
            break;
        case "Dms":
            match = strVar.search(/[-]?[1]?[0-9]{1,2}\s[0-6]?[0-9]\s[0-6]?[0-9](\.[0-9])?/);
            if (match == -1)
            {
                strClean = match;
            }
            else
            {
                strClean = strVar;
            }
            break;
        default:
            strClean = strVar;
    }
    return strClean;   
}


// Konvertiert Dezimalgrad
function convertD()
{
    // latitude
    lat = document.forms["formD"].elements["latD"].value;
    lat = cleanInput(lat,"D");
    if (lat == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    x = lat;
    if (x > 0)
    {
        ns = "";
    }
    else
    {
        ns = "-";
        x = x * -1;
    }
    xr = Math.round(x * 100000) / 100000;
    xr = ns + xr;
    splited = xr.split(".");
    xg = splited[0];
    xm = (x - xg)*60;
    xmr = Math.round(xm * 1000) / 1000;
    xgm = ns + xg + " " + Math.abs(xmr);
    splited = String(xm).split(".");
    xmg = splited[0];
    xs = (xm - xmg) * 60;
    xsr = Math.round(xs *10) / 10;
    xgms = ns + xg + " " + Math.abs(xmg) + " " + Math.abs(xsr);

    if (ns == "")
    {
       ns = "N";
    }
    else
    {
       ns = "S";
    }
    
    // longitude 
    lon = document.forms["formD"].elements["lonD"].value;
    lon = cleanInput(lon,"D");
    if (lon == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    y = lon;
    if (y > 0)
    {
        ew = "";
    }
    else
    {
        ew = "-";
        y = y * -1;
    }
    yr = Math.round(y * 100000) / 100000;
    yr = ew + yr;
    splited = yr.split(".");
    yg = splited[0];
    ym = (y - yg)*60;
    ymr = Math.round(ym * 1000) / 1000;
    ygm = ew + yg + " " + Math.abs(ymr);
    splited = String(ym).split(".");
    ymg = splited[0];
    ys = (ym - ymg) * 60;
    ysr = Math.round(ys *10) / 10;
    ygms = ew + yg + " " + Math.abs(ymg) + " " + Math.abs(ysr);
    
    if (ew == "")
    {
       ew = "E";
    }
    else
    {
       ew = "W";
    }
    
    xygm = ns + " " + xg + " " + Math.abs(xmr) + " " + ew + " " + yg + " " + Math.abs(ymr);

    //$myCoord = new gPoint();
    //$myCoord->setLongLat($yr, $xr);
    //$myCoord->convertLLtoTM();
    //$utm = $myCoord->utmZone." E ".(int)$myCoord->utmEasting." N ".(int)$myCoord->utmNorthing;

    if (xm > 60 || ym > 60)
    {
        alert("FEHLER! > 60 min");
    }
    document.forms["formDm"].elements["latDm"].value = xgm;
    document.forms["formDm"].elements["lonDm"].value = ygm;
    checkIsEmpty("formDm");
    document.forms["formDms"].elements["latDms"].value = xgms;
    document.forms["formDms"].elements["lonDms"].value = ygms;
    checkIsEmpty("formDms");
    document.forms["formGeo"].elements["latlonDm"].value = xygm;
    checkIsEmpty("formGeo");
}

// Konvertiert Dezimalminuten
function convertDm()
{
    // Latitude
    latgm = document.forms["formDm"].elements["latDm"].value;
    latgm = cleanInput(latgm,"Dm");
    if (latgm == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    x = latgm;
    splited = x.split(" ");
    xg = splited[0];
    xm = splited[1];
    if (xg > 0)
    {
        ns = "";
    }
    else
    {
        ns = "-";
        xg = xg * -1;
    }
    xmr = Math.round(xm * 100000) / 100000;
    xgm = ns + xg + " " + Math.abs(xmr);
    xmd = xm / 60;
    x = parseInt(xg) + xmd;
    xr = Math.round(x * 100000) / 100000;
    xr = ns + xr;
    splited = xm.split(".");
    xmg = splited[0];
    xs = (xm - xmg) * 60;
    xsr = Math.round(xs * 10) / 10
    xgms = ns + xg + " " + Math.abs(xmg) + " " + Math.abs(xsr);
    if (ns == "")
    {
        ns = "N";
    }
    else
    {
        ns = "S";
    }

    // Longitude
    longm = document.forms["formDm"].elements["lonDm"].value;
    longm = cleanInput(longm,"Dm");
    if (longm == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    y = longm;
    splited = y.split(" ");
    yg = splited[0];
    ym = splited[1];
    if (yg > 0)
    {
        ew = "";
    }
    else
    {
        ew = "-";
        yg = yg * -1;
    }
    ymr = Math.round(ym * 100000) / 100000;
    ygm = ew + yg + " " + Math.abs(ymr);
    ymd = ym / 60;
    y = parseInt(yg) + ymd;
    yr = Math.round(y * 100000) / 100000;
    yr = ew + yr;
    splited = ym.split(".");
    ymg = splited[0];
    ys = (ym - ymg) * 60;
    ysr = Math.round(ys * 10) / 10
    ygms = ew + yg + " " + Math.abs(ymg) + " " + Math.abs(ysr);
    if (ew == "")
    {
        ew = "E";
    }
    else
    {
        ew = "W";
    }
    
    xygm = ns + " " + xg + " " + Math.abs(xmr) + " " + ew + " " + yg + " " + Math.abs(ymr);

   //$myCoord = new gPoint();
   //$myCoord->setLongLat($yr, $xr);
   //$myCoord->convertLLtoTM();
   //$utm = $myCoord->utmZone." E ".(int)$myCoord->utmEasting." N ".(int)$myCoord->utmNorthing;
    if (xm > 60 | ym > 60)
    {
        alert ("FEHLER! > 60 min");
    }
    document.forms["formD"].elements["latD"].value = xr;
    document.forms["formD"].elements["lonD"].value = yr;
    checkIsEmpty("formD");
    document.forms["formDms"].elements["latDms"].value = xgms;
    document.forms["formDms"].elements["lonDms"].value = ygms;
    checkIsEmpty("formDms");
    document.forms["formGeo"].elements["latlonDm"].value = xygm;
    checkIsEmpty("formGeo"); 
}

// Konvertiert Grad, Minuten Sekunden
function convertDms()
{
    // Latitude
    latgms = document.forms["formDms"].elements["latDms"].value;
    latgms = cleanInput(latgms,"Dms");
    if (latgms == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    x = latgms;
    splited = x.split(" ");
    xg = splited[0];
    xm = splited[1];
    xs = splited[2];
    if (xg > 0)
    {
        ns = "";
    }
    else
    {
        ns = "-";
        xg = xg * -1;
    }
    xmd = xm / 60;
    xsd = xs / 6000;
    xsr = Math.round(xs * 10) / 10;
    xgms = ns + xg + " " + Math.abs(xm) + " " + Math.abs(xsr);
    xmd = parseInt(xm) + xs / 60;
    xmdr = Math.round(xmd * 1000) / 1000;
    xgm = ns + xg + " " + Math.abs(xmdr);
    xmd = xmd / 60;
    x = parseInt(xg) + xmd;
    xr = Math.round(x * 100000) / 100000;
    xr = ns + xr;
    if (ns == "")
    {
        ns = "N";
    }
    else
    {
        ns = "S";
    }

    // Longitude
    longms = document.forms["formDms"].elements["lonDms"].value;
    longms = cleanInput(longms,"Dms");
    if (longms == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    y = longms;
    splited = y.split(" ");
    yg = splited[0];
    ym = splited[1];
    ys = splited[2];
    if (yg > 0)
    {
        ew = "";
    }
    else
    {
        ew = "-";
        yg = yg * -1;
    }
    ymd = ym / 60;
    ysd = ys / 6000;
    ysr = Math.round(ys * 10) / 10;
    ygms = ew + yg + " " + Math.abs(ym) + " " + Math.abs(ysr);
    ymd = parseInt(ym) + ys / 60;
    ymdr = Math.round(ymd * 1000) / 1000;
    ygm = ew + yg + " " + Math.abs(ymdr);
    ymd = ymd / 60;
    y = parseInt(yg) + ymd;
    yr = Math.round(y * 100000) / 100000;
    yr = ew + yr;
    if (ew == "")
    {
        ew = "E";
    }
    else
    {
        ew = "W";
    }
    xygm = ns + " " + xg + " " + Math.abs(xmdr) + " " + ew + " " + yg + " " + Math.abs(ymdr);

   //$myCoord = new gPoint();
   //$myCoord->setLongLat($yr, $xr);
   //$myCoord->convertLLtoTM();
   //$utm = $myCoord->utmZone." E ".(int)$myCoord->utmEasting." N ".(int)$myCoord->utmNorthing;
    if (xm > 60 | ym > 60)
    {
        alert("FEHLER! > 60 min");
    }
    document.forms["formD"].elements["latD"].value = xr;
    document.forms["formD"].elements["lonD"].value = yr;
    checkIsEmpty("formD");
    document.forms["formDm"].elements["latDm"].value = xgm;
    document.forms["formDm"].elements["lonDm"].value = ygm;
    checkIsEmpty("formDm");
    document.forms["formGeo"].elements["latlonDm"].value = xygm;
    checkIsEmpty("formGeo");
}

// Konvertiert Geocashing
function convertGeo()
{
    latlon = document.forms["formGeo"].elements["latlonDm"].value;
    latlon = cleanInput(latlon,"Geo");
    if (latlon == -1)
    {
        alert("Eingegebener Wert ist ungültig!");
        return;
    } 
    xy = latlon
    splited = xy.split(" ");
    ns = splited[0];
    xg = splited[1];
    xm = splited[2];
    ew = splited[3];
    yg = splited[4];
    ym = splited[5];
    if (ns == "N")
    {
        ns = "";
    }
    if (ns == "S")
    {
        ns = "-";
    }
    if (ew == "E")
    {
        ew = "";
    }
    if (ew == "W")
    {
        ew = "-";
    }
    xmdr = Math.round(xm * 1000) / 1000;
    ymdr = Math.round(ym * 1000) / 1000;
    xgm = ns + xg + " " + Math.abs(xmdr);
    ygm = ew + yg + " " + Math.abs(ymdr);
    xmd = xm / 60;
    ymd = ym / 60;
    x = parseInt(xg) + xmd;
    y = parseInt(yg) + ymd;
    xr = Math.round(x * 100000) / 100000;
    yr = Math.round(y * 100000) / 100000;
    xr = ns + xr;
    yr = ew + yr;
    splited = xm.split(".");
    xmg = splited[0];
    splited = ym.split(".");
    ymg = splited[0];
    xs = (xm - xmg) * 60;
    ys = (ym - ymg) * 60;
    xsr = Math.round(xs * 10) / 10;
    ysr = Math.round(ys * 10) / 10;
    xgms = ns + xg + " " + Math.abs(xmg) + " " + Math.abs(xsr);
    ygms = ew + yg + " " + Math.abs(ymg) + " " + Math.abs(ysr);

   //$myCoord = new gPoint();
   //$myCoord->setLongLat($yr, $xr);
   //$myCoord->convertLLtoTM();
   //$utm = $myCoord->utmZone." E ".(int)$myCoord->utmEasting." N ".(int)$myCoord->utmNorthing;
    if (xm > 60 | ym > 60)
    {
        alert("FEHLER! > 60 min");
    }
    document.forms["formD"].elements["latD"].value = xr;
    document.forms["formD"].elements["lonD"].value = yr;
    checkIsEmpty("formD");
    document.forms["formDm"].elements["latDm"].value = xgm;
    document.forms["formDm"].elements["lonDm"].value = ygm;
    checkIsEmpty("formDm");
    document.forms["formDms"].elements["latDms"].value = xgms;
    document.forms["formDms"].elements["lonDms"].value = ygms;
    checkIsEmpty("formDms");  
}

function save()
{
    lat = document.forms["formD"].elements["latD"].value;
    lon = document.forms["formD"].elements["lonD"].value;

    if(lat > 0 && lon > 0)
    {
        document.forms["tl_cave"].elements["ctrl_latitude"].value = lat;
        document.forms["tl_cave"].elements["ctrl_longitude"].value = lon;   
    }
    clearFields('formD');
    clearFields("formDm");
    clearFields("formDms");
    clearFields("formGeo");           
}

function clearFields(formId)
{
    f = document.forms[formId];
    for (i = 0; i < f.length;i++)
    {
        if (f.elements[i].type == "text")
        {            
            f.elements[i].value = "";            
        } 
    } 
}
<?php
/*
 * Coorinate converter Form
 * 
 * Copyright (c) 2013 Ralf Rötzer
 *
 */

?>
    <p>Die Umrehnung kann von und in folgende Koordinatensysteme erfolgen:</p>
    <ul>
      <li>Dezimalgrad (<i>D.D</i>)</li> 
      <li>Dezimalminuten (<i>D DM</i>)</li> 
      <li>Grad, Minuten und Sekunden (<i>D M S</i>)</li>
      <!--<li>Universelle transversale Mercator-Projektion (UTM)</li>-->
      <li>Geocaching-Format (<i>N|S Dx Mx E|W Dy My</i>)</li>
    </ul>
    <p>Beim anklicken des Buttons <b>Übername</b> werden die Dezimalgrad-Werte in die entsprechenden Felder des Contao-Backendmoduls eingetragen.</p>
    <form id="formD" method='get' action=''>
        <fieldset>
            <legend>Dezimalgrad:</legend>
            <label for='latD' title='Länge'>Länge:</label>&nbsp;<input id='latD' class='converter-input-smal' type='text' name='latD' value='' onkeyup='checkIsEmpty("formD");'>
            <label for='lonD' title='Breite'>Breite:</label>&nbsp;<input id='lonD' class='converter-input-smal' type='text' name='lonD' value='' onkeyup='checkIsEmpty("formD");'>
            <input type='button' id="btnD" class="converter-button" value='Umrechnen' onclick="convertD();">
        </fieldset>
    </form>
    <form id="formDm" method='get' action=''> 
        <fieldset>
            <legend>Dezimalminuten:</legend>
            <label for='latDm' title='Länge'>Länge:</label>&nbsp;<input id='latDm' class='converter-input-smal' type='text' name='latDm' value='' onkeyup="checkIsEmpty('formDm');">
            <label for='lonDm' title='Breite'>Breite:</label>&nbsp;<input id='lonDm' class='converter-input-smal' type='text' name='lonDm' value='' onkeyup="checkIsEmpty('formDm');">
            <input type='button' id="btnDm" class="converter-button" value='Umrechnen' onclick="convertDm();">
        </fieldset>
    </form>
    <form id="formDms" method='get' action=''>
        <fieldset>
            <legend>Grad-Minuten-Sekunden:</legend>
            <label for='latDms' title='Länge'>Länge:</label>&nbsp;<input id='latDms' class='converter-input-smal' type='text' name='latDms' value='' onkeyup="checkIsEmpty('formDms');">
            <label for='lonDms' title='Breite'>Breite:</label>&nbsp;<input id='lonDms' class='converter-input-smal' type='text' name='lonDms' value=''onkeyup="checkIsEmpty('formDms');">
            <input type='button' id="btnDms" class="converter-button" value='Umrechnen' onclick="convertDms();">
        </fieldset>
    </form>
    <!--<form id="formUtm" method='get' action=''>
        <fieldset>
            <legend>UTM:</legend>
            <label for='utm' title='Länge'>&nbsp;</label>&nbsp;<input id='utm' class='converter-input' type='text' name='utm' value=''>
            <input type='button' value='Umrechnen' onclick="convertUtm();">
        </fieldset>
    </form>-->
    <form id="formGeo" method='get' action=''>
        <fieldset>
            <legend>Geocaching-Format:</legend>
            <label for='latlonDm' title='Länge'>&nbsp;</label>&nbsp;<input id='latlonDm' class='converter-input' type='text' name='latlonDm' value='' onkeyup="checkIsEmpty('formGeo');">
            <input type='button' id="btnGeo" class="converter-button" value='Umrechnen' onclick="convertGeo();">
        </fieldset>
    </form>
    
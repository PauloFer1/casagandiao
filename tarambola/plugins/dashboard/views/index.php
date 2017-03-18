<h1>Avisos</h1>
<?
    $nEnc = sizeof($encomendas);
    $nEnvio = sizeof($envio);
?>
<ul id="listaModulos">
    <li><a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/encomendas" title="Guia" class="newBigBtn"><? echo($nEnc); ?> ENCOMENDAS EM PROCESSAMENTO</a></li>
    <li><a href="<? echo(URL_PUBLIC); ?>tarabackend/plugin/encomendas/envios" title="Guia" class="newBigBtn"><? echo($nEnvio); ?> ENCOMENDAS PARA ENVIO</a></li>
</ul>
<!--
<br/>
<br/>
<br/>

<h1>Analytics Report</h1>

<hr>
<button id="authorize-button" style="visibility: hidden">Authorize</button>

<ul id="ga-table">
  <li><p class="c1">Table Id:</p>
      <input id="table-id" class="c2" value="ga:51463064"></input>
      <p class="note">Format is ga:xxx where xxx is your profile ID.</p></li>
  <li><p class="c1">Start Date:</p>
      <input id="start-date" class="c2"></input></li>
  <li><p class="c1">End Date:</p>
      <input id="end-date" class="c2"></input></li>
</ul>
<button id="run-demo-button" style="visibility: hidden">Run Demo</button>

<hr>
<div id="output">Loading, one sec....</div>




 <h1>Analytics Data Export API Script</h1>
    <button id="authButton">Loading...</button>
    <div id="dataControls" style="display:none">
      <p>
        For this user, retrieve the first 50 accounts with view (profile) ID and table ID
        <button id="getAccount">Get Account Data</button>
      </p>
      <p>
        For this view (profile), show visitor type and entrance/exit graphs:
        <input type="text" id="tableId"/> (insert Table ID)
        <button id="getData">Get Report Data</button>
      </p>
    </div>
    <table>
      <tr>
      <td><div id="visitorsDiv"></div></td>
      <td><div id="entrancesDiv"></div></td>
    </tr>
  </table>
  <img src="dummy.gif" style="display:none" alt="required for Google Data"/>-->
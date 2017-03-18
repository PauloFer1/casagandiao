<?php require_once('../../../config.php'); ?>
<script type="text/javascript">

        function setAnaVars()
        {
           var m=$('#month').val();
           var p = $('#profileId').val();
           $('#googleAnalytics').html('<img src="<?php echo(URL_PUBLIC); ?>tarambola/plugins/dashboard/img/ajax-loader.gif")"  class="loaderAjax"/>');
           getAnalytics(p, m);
        }
         $(document).ready(function() {
             $("#tabs").tabs();
            });
</script>
<?php

// use session to store credentials and auth hash
//session_start();

require 'analytics.class.php';

/*if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])){

    // set username & password
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];

    header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']));    
    exit;
}*/
$_SESSION['username'] = "noticiasdefafe.tarambola@gmail.com";
$_SESSION['password'] = "n0t1c1as";

if (isset($_SESSION['username'], $_SESSION['password'])){
    
    // construct the class
    $oAnalytics = new analytics($_SESSION['username'], $_SESSION['password']);
    
    // get an array with profiles (profileId => profileName)
    $aProfiles = $oAnalytics->getProfileList();  
      
    $aProfileKeys = array_keys($aProfiles);
    // set the profile tot the first account
    $oAnalytics->setProfileById($aProfileKeys[0]);
    $iSelectedMonth = date('n');
}

if (isset($_GET['profileId'])){
    // change profileId
    $oAnalytics->setProfileById($_GET['profileId']);
}
if (isset($_GET['month'])){
    // change month
    $iSelectedMonth = $_GET['month'];
}
// set the month
$oAnalytics->setMonth($iSelectedMonth, date('Y')); 

// alternativly set a date range:
// $oAnalytics->setDateRange('YYYY-MM-DD', 'YYYY-MM-DD');

/**
* Basic html table for displaying graphs
* 
* @param array $aData
*/
function graph($aData){
    
    $iMax = max($aData);
    if ($iMax == 0){
        echo 'No data';
        return;
    }
    echo '<table id="analyticsGraph">
            <tr>
                <td>Metric</td>
                <td>#</td>
                <td>Graph</td>
            </tr>';
    foreach($aData as $sKey => $sValue){
        echo '  <tr>
                    <td>' . $sKey . '</td>
                    <td>' . $sValue . '</td>
                    <td><div class="analyticsBar" style="width: ' . intval(($sValue / $iMax) * 300) . 'px;"></div>
                </tr>';
    }
    echo '</table>';
}


?>

        <form>
            <label for="profileId">Profile</label>
            <select id="profileId" name="profileId">
            <?php
            foreach ($aProfiles as $sKey => $sValue){
                //if($sValue=="www.tarambola.pt")
                    {
                    echo '<option value="' . $sKey . '">' . $sValue . '</option>';
                    }
            }
            ?>
            </select>
            <label for="month">Month</label>
            <select name="month" id="month">
            <?php
            $aMonth = range(1, date('n'));
            foreach($aMonth as $iMonth){
                echo '<option ' . ($iMonth == $iSelectedMonth ? 'selected="selected" ' : '') . 'value="' . $iMonth . '">' . date('F', mktime(0, 0, 0, $iMonth, 1, date('Y'))) . '</option>'; 
            }
            ?>
            </select>
            <a onclick="setAnaVars()" id="btnGoGraph">See Chart</a>
        </form>
<div id ="tabs">
    <ul id="analyticsTabs">
        <li><a href="#fragment-1"><span>Visits</span></a></li>
        <li><a href="#fragment-2"><span>Pageviews</span></a></li>
        <li><a href="#fragment-3"><span>Visits by Hour</span></a></li>
        <li><a href="#fragment-4"><span>Browsers</span></a></li>
        <li><a href="#fragment-5"><span>Referrers</span></a></li>
        <li><a href="#fragment-6"><span>Words of Research</span></a></li>
        <li><a href="#fragment-7"><span>Screen resolution</span></a></li>
    </ul>
    <div id="fragment-1" class="graphicCont">
        <h3 class="analyticsTitle">Visits:</h3>
        <?php graph($oAnalytics->getVisitors()); ?>
    </div>
    <div id="fragment-2" class="graphicCont">
        <h3 class="analyticsTitle">Pageviews:</h3>
        <?php graph($oAnalytics->getPageviews()); ?>
    </div>
    <div id="fragment-3" class="graphicCont">
        <h3 class="analyticsTitle">Visits by Hour:</h3>
        <?php graph($oAnalytics->getVisitsPerHour()); ?>
    </div>
    <div id="fragment-4" class="graphicCont">
        <h3 class="analyticsTitle">Browsers:</h3>
        <?php graph($oAnalytics->getBrowsers()); ?>
    </div>
    <div id="fragment-5" class="graphicCont">
        <h3 class="analyticsTitle">Referrers:</h3>
        <?php graph($oAnalytics->getReferrers()); ?>
    </div>
    <div id="fragment-6" class="graphicCont">
        <h3 class="analyticsTitle">Words of Research:</h3>
        <?php graph($oAnalytics->getSearchWords()); ?>
    </div>
    <div id="fragment-7" class="graphicCont">
        <h3 class="analyticsTitle">Screen resolution:</h3>
        <?php graph($oAnalytics->getScreenResolution()); ?>
    </div>
</div>
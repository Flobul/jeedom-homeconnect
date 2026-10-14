<?php
/* This file is part of Jeedom. */
if (!isConnect('admin')) {
    throw new Exception(__('401 - Accès non autorisé', __FILE__));
}

function homeconnectHealthEscape($_value)
{
    return htmlspecialchars((string) $_value, ENT_QUOTES, 'UTF-8');
}

$eqLogics = homeconnect::byType('homeconnect');
$connectedCount = 0;
$activeProgramCount = 0;
foreach ($eqLogics as $healthEqLogic) {
    $connected = $healthEqLogic->getCmd('info', 'connected');
    if (is_object($connected) && (bool) $connected->execCmd()) {
        $connectedCount++;
    }
    $activeProgram = $healthEqLogic->getCmd('info', 'GET::BSH.Common.Root.ActiveProgram');
    if (is_object($activeProgram) && trim((string) $activeProgram->execCmd()) !== '') {
        $activeProgramCount++;
    }
}
$requestCount = (int) cache::byKey('homeconnect::requests::total')->getValue();
?>
<?php include_file('desktop', 'plugin-banner', 'css', 'homeconnect'); ?>
<?php include_file('desktop', 'health', 'css', 'homeconnect'); ?>

<div id="homeconnectHealth">
    <header id="homeconnect-health-banner" class="homeconnect-health-hero">
        <div class="homeconnect-health-hero-icon"><i class="fas fa-heartbeat"></i></div>
        <div class="homeconnect-health-hero-copy">
            <span class="homeconnect-health-eyebrow">{{Diagnostic du plugin}}</span>
            <h2>{{Santé Home Connect}}</h2>
            <p>{{Connexion des appareils, programmes actifs et suivi des requêtes Home Connect.}}</p>
        </div>
        <button type="button" class="btn homeconnect-health-refresh" id="bt_refreshHealth_homeconnect"><i class="fas fa-sync-alt"></i> {{Rafraîchir}}</button>
    </header>

    <section class="homeconnect-health-summary" aria-label="{{Résumé de la santé Home Connect}}">
        <article class="homeconnect-health-summary-card">
            <span class="homeconnect-health-summary-icon"><i class="fas fa-home"></i></span>
            <div><span>{{Appareils}}</span><strong><?php echo count($eqLogics); ?></strong><small>{{configuré(s)}}</small></div>
        </article>
        <article class="homeconnect-health-summary-card">
            <span class="homeconnect-health-summary-icon"><i class="fas fa-wifi"></i></span>
            <div><span>{{Connectés}}</span><strong><?php echo (int) $connectedCount; ?></strong><small><?php echo max(0, count($eqLogics) - $connectedCount); ?> {{déconnecté(s)}}</small></div>
        </article>
        <article class="homeconnect-health-summary-card">
            <span class="homeconnect-health-summary-icon"><i class="fas fa-play-circle"></i></span>
            <div><span>{{Programmes actifs}}</span><strong><?php echo (int) $activeProgramCount; ?></strong><small>{{appareil(s) en cours}}</small></div>
        </article>
        <article class="homeconnect-health-summary-card">
            <span class="homeconnect-health-summary-icon"><i class="fas fa-exchange-alt"></i></span>
            <div><span>{{Requêtes aujourd'hui}}</span><strong><?php echo (int) $requestCount; ?></strong><small>{{sur 1000}}</small></div>
        </article>
    </section>

    <section class="homeconnect-health-panel">
        <div class="homeconnect-health-panel-header">
            <i class="fas fa-plug"></i>
            <h3>{{Équipements}}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped tablesorter" id="table_healthhomeconnect">
                <thead>
                    <tr>
                        <th>{{Appareil}}</th>
                        <th>{{Type}}</th>
                        <th>{{Connecté}}</th>
                        <th>{{Programme actif}}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eqLogics as $eqLogic) {
                        $connected = $eqLogic->getCmd('info', 'connected');
                        $isConnected = is_object($connected) && (bool) $connected->execCmd();
                        $activeProgram = $eqLogic->getCmd('info', 'GET::BSH.Common.Root.ActiveProgram');
                        $activeProgramValue = is_object($activeProgram) ? (string) $activeProgram->execCmd() : '';
                    ?>
                        <tr<?php echo $eqLogic->getIsEnable() ? '' : ' class="homeconnect-health-disabled"'; ?>>
                            <td><a href="<?php echo homeconnectHealthEscape($eqLogic->getLinkToConfiguration()); ?>"><?php echo $eqLogic->getHumanName(true); ?></a></td>
                            <td><span class="label label-info"><?php echo homeconnectHealthEscape($eqLogic->getConfiguration('type')); ?></span></td>
                            <td><span class="label label-<?php echo $isConnected ? 'success' : 'danger'; ?>"><?php echo $isConnected ? '{{Oui}}' : '{{Non}}'; ?></span></td>
                            <td><?php echo trim($activeProgramValue) !== '' ? homeconnectHealthEscape($activeProgramValue) : '<span class="text-muted">—</span>'; ?></td>
                        </tr>
                    <?php } ?>
                    <?php if (count($eqLogics) === 0) { ?>
                        <tr><td colspan="4" class="text-center text-muted">{{Aucun appareil Home Connect}}</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<?php include_file('desktop', 'health', 'js', 'homeconnect'); ?>
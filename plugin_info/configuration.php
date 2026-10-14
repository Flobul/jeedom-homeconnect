<?php
/* This file is part of Jeedom. */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect('admin')) {
    include_file('desktop', '404', 'php');
    die();
}

$plugin = plugin::byId('homeconnect');
$update = $plugin->getUpdate();
$redirectUri = network::getNetworkAccess('external') . '/plugins/homeconnect/x.php?k=' . jeedom::getApiKey('homeconnect');
?>

<?php include_file('desktop', 'plugin-banner', 'css', 'homeconnect'); ?>

<form class="form-horizontal" id="configuration_plugin_homeconnect">
    <div class="homeconnect-config-page">
        <header class="homeconnect-config-hero">
            <div class="homeconnect-config-identity">
                <div class="homeconnect-config-logo"><img src="plugins/homeconnect/plugin_info/homeconnect_icon.png" alt="Home Connect"></div>
                <div>
                    <span class="homeconnect-config-eyebrow">{{Configuration du plugin}}</span>
                    <h2>Home Connect</h2>
                    <p>{{Connectez vos appareils Home Connect à Jeedom et pilotez-les depuis votre compte.}}</p>
                </div>
            </div>
            <div class="homeconnect-config-meta">
                <span><i class="fas fa-code-branch"></i> {{Version}} <strong>v<?php echo htmlspecialchars((string) homeconnect::$_pluginVersion, ENT_QUOTES, 'UTF-8'); ?></strong></span>
                <?php if (is_object($update)) { ?>
                    <span><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars((string) $update->getLocalVersion(), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><i class="fas fa-stream"></i> <?php echo htmlspecialchars((string) $update->getConfiguration('version', 'stable'), ENT_QUOTES, 'UTF-8'); ?></span>
                    <span><i class="fas fa-cloud-download-alt"></i> <?php echo htmlspecialchars((string) $update->getSource(), ENT_QUOTES, 'UTF-8'); ?></span>
                <?php } ?>
            </div>
            <div class="homeconnect-config-links">
                <a class="btn btn-sm homeconnect-config-link-documentation" target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars((string) $plugin->getDocumentation(), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-book"></i> {{Documentation}}</a>
                <a class="btn btn-sm homeconnect-config-link-changelog" target="_blank" rel="noopener noreferrer" href="<?php echo htmlspecialchars((string) $plugin->getChangelog(), ENT_QUOTES, 'UTF-8'); ?>"><i class="fas fa-list"></i> {{Changelog}}</a>
                <a class="btn btn-sm homeconnect-config-link-community" target="_blank" rel="noopener noreferrer" href="https://community.jeedom.com/tag/plugin-homeconnect"><i class="fas fa-comments"></i> {{Communauté}}</a>
            </div>
        </header>

        <section class="homeconnect-config-card">
            <div class="homeconnect-config-card-header">
                <i class="fas fa-cog"></i>
                <div>
                    <h3>{{Paramètres généraux}}</h3>
                    <p>{{Choisissez la pièce par défaut et la fréquence de synchronisation des appareils.}}</p>
                </div>
            </div>
            <div class="homeconnect-config-card-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Pièce par défaut pour les appareils}}</label>
                    <div class="col-lg-4">
                        <select id="sel_object" class="configKey form-control" data-l1key="defaultParentObject">
                            <option value="">{{Aucune}}</option>
                            <?php foreach (jeeObject::all() as $object) { ?>
                                <option value="<?php echo (int) $object->getId(); ?>"><?php echo htmlspecialchars((string) $object->getName(), ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Auto-actualisation (cron)}}</label>
                    <div class="col-lg-4">
                        <select class="configKey form-control" data-l1key="autorefresh">
                            <option value="* * * * *">{{Toutes les minutes}}</option>
                            <option value="*/5 * * * *">{{Toutes les 5 minutes}}</option>
                            <option value="*/10 * * * *">{{Toutes les 10 minutes}}</option>
                            <option value="*/15 * * * *">{{Toutes les 15 minutes}}</option>
                            <option value="*/20 * * * *">{{Toutes les 20 minutes}}</option>
                            <option value="*/25 * * * *">{{Toutes les 25 minutes}}</option>
                            <option value="*/30 * * * *">{{Toutes les 30 minutes}}</option>
                            <option value="*/35 * * * *">{{Toutes les 35 minutes}}</option>
                            <option value="*/40 * * * *">{{Toutes les 40 minutes}}</option>
                            <option value="*/45 * * * *">{{Toutes les 45 minutes}}</option>
                            <option value="*/50 * * * *">{{Toutes les 50 minutes}}</option>
                            <option value="*/55 * * * *">{{Toutes les 55 minutes}}</option>
                            <option value="*/60 * * * *">{{Toutes les heures}}</option>
                            <option value="">{{Jamais}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </section>

        <section class="homeconnect-config-card">
            <div class="homeconnect-config-card-header">
                <i class="fas fa-key"></i>
                <div>
                    <h3>{{Application Home Connect et authentification}}</h3>
                    <p>{{Renseignez les identifiants OAuth puis autorisez Jeedom depuis votre compte Home Connect.}}</p>
                </div>
            </div>
            <div class="homeconnect-config-card-body">
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Création de l'application Jeedom sur le site Home Connect}}</label>
                    <div class="col-lg-5">
                        <a class="btn btn-info" title="{{Créer Application}}" href="https://developer.home-connect.com/applications/add" target="_blank" rel="noopener noreferrer"><i class="fas fa-plus"></i> {{Créer Application}}</a>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Redirect URI}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Cette URL sera demandée sur le site Home Connect pour la création des identifiants.}}"></i></sup>
                    </label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" id="redirectUriHomeconnect" readonly value="<?php echo htmlspecialchars($redirectUri, ENT_QUOTES, 'UTF-8'); ?>">
                        <span id="textRedirectUriHomeconnect" class="help-block"><i class="fas fa-info-circle"></i> <?php echo strlen($redirectUri); ?> {{caractères (max. 128)}}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Client ID}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Récupérez ce paramètre sur le site Home Connect.}}"></i></sup>
                    </label>
                    <div class="col-sm-6"><input type="text" class="configKey form-control" data-l1key="client_id"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Client Secret}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Récupérez ce paramètre sur le site Home Connect.}}"></i></sup>
                    </label>
                    <div class="col-sm-6"><input type="password" class="configKey form-control" data-l1key="client_secret"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{Mode démo (appareils simulés)}}</label>
                    <div class="col-sm-2"><input id="input_demo_mode" type="checkbox" class="configKey tooltips" data-l1key="demo_mode"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Client ID pour le mode démo}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Récupérez ce paramètre dans le carré API Web Client du site Home Connect.}}"></i></sup>
                    </label>
                    <div class="col-sm-4"><input type="text" class="configKey form-control" data-l1key="demo_client_id"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{Se connecter}}</label>
                    <div class="col-sm-7 homeconnect-config-login-actions">
                        <a class="btn btn-warning" id="bt_loginHomeConnect"><i class="fas fa-sign-in-alt"></i> {{Appareils réels}}</a>
                        <a class="btn btn-warning" id="bt_loginDemoHomeConnect"><i class="fas fa-sign-in-alt"></i> {{Démo (Simulateurs)}}</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="homeconnect-config-card">
            <div class="homeconnect-config-card-header">
                <i class="fas fa-list-alt"></i>
                <div>
                    <h3>{{Programmes}}</h3>
                    <p>{{Contrôlez la création et le regroupement des programmes exposés dans Jeedom.}}</p>
                </div>
            </div>
            <div class="homeconnect-config-card-body">
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Génère les programmes dans une seule commande en liste}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Cochez la case pour n’avoir qu’une seule commande select regroupant l’ensemble des programmes.}}"></i></sup>
                    </label>
                    <div class="col-sm-2"><input id="listValueProgram" type="checkbox" class="configKey tooltips" data-l1key="listValueProgram"></div>
                </div>
                <div class="form-group">
                    <label class="col-sm-4 control-label">
                        {{Force l'ajout de tous les programmes}}
                        <sup><i class="fas fa-question-circle tooltips" title="{{Ajoute les programmes actifs et sélectionnés à la liste, qu’ils soient supportés ou non par l’API.}}"></i></sup>
                    </label>
                    <div class="col-sm-2"><input id="forceAddProgram" type="checkbox" class="configKey tooltips" data-l1key="forceAddProgram"></div>
                </div>
            </div>
        </section>
    </div>
</form>

<?php include_file('desktop', 'configuration', 'js', 'homeconnect'); ?>
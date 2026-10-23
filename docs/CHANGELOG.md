# Changelog

## Non publié

## Version 2026-10-14 (14/10/2026)

- Refonte de la structure du code afin d'améliorer sa lisibilité et sa maintenabilité

## Version 2026-07-18 (18/07/2026)

> L’article WordPress présente cette entrée sous le titre « Version du 19/07/2026 ». La version déclarée par le plugin et introduite par le commit de référence est `2026-07-18`.

- Amélioration des journaux de débogage pour masquer les valeurs sensibles.
- Passage en JS Vanilla.
- Amélioration de la gestion des processus et ajout de vérifications d'autorisation dans le plugin Jeedom Home Connect
- Amélioration des paramètres de connexion pour une meilleure gestion des erreurs

## Version 2026-05-10 (10/05/2026)

- Modification du champ URL de redirection et copie direct dans le navigateur.
- Ajout de la visualisation des modification en cours sur la page de configuration.

## Version 2026-05-04 (04/05/2026)

- Correction de la connexion interne/externe.
- Ajout du climatiseur et ses commandes.
- Corrections mineures.

## Version 2026-05-03 (03/05/2026)

- Retrait de l'url de redirection dans les requêtes d'authentification suite à des erreurs d'authentification.

## Version 2025-09-19 (19/09/2025)

- Ajout de traductions (commandes, valeurs)

## Version 2024-10-16 (16/10/2024)

- Passage de la beta en stable.

## Version 2024-08-10 (10/08/2024)

- Ajout de nouveaux programmes.

## Version 2024-07-24 (24/07/2024)

- Correction du simulateur.

## Version 2024-04-22 (22/04/2024)

- Modification de l'URL de retour sur jeedom lors de la première connexion.
  Raccourcissement de l'URL lié au nombre de caractères réduis par le site développeur HC.

## Version 2024-03-01 (01/03/2024)

- Correction de l'info "commande information à mettre à jour" qui disparaissait à la sauvegarde de l'équipement. (il faut ré attribuer les liaisons entre commande infos et actions)<https://github.com/Flobul/homeconnect#version-du-29012024>

## Version 2024-01-29 (29/01/2024)

- Ajout du champ Value dans la page Configuration de la commande.
- Correction de la vérification des champs de getConfiguration.

## Version 2023-03-27 (27/03/2023)

<https://github.com/Flobul/homeconnect#version-du-27032023>

Ajout d'un bouton pour ajouter tous les programmes (actifs et sélectionnés) à la liste (supportés ou non par l'API)

## Version 2023-02-07 (07/02/2023)

Ajout d'un bouton Assistant commande pour créer ses commandes action.

## Version 2023-02-04 (04/02/2023)

<https://github.com/Flobul/homeconnect#version-du-04022023>

Ménage dans le code et harmonisation des logs et affichage

## Version 2023-02-03 (03/02/2023)

<https://github.com/Flobul/homeconnect#version-du-03022023>

Ajout d'un bouton pour sauvegarder les modifications de path, key, category d'une commande<https://github.com/Flobul/homeconnect#version-du-07052021>

## Version 2023-01-31 (31/01/2023)

<https://github.com/Flobul/homeconnect#version-du-31012023>

Modification page des commandes Prise en compte de tous les événements en commande info. Correction traduction et ajout.<https://github.com/Flobul/homeconnect#version-du-07022023>

## Version 2021-05-07 (07/05/2021)

Suite au changement de politique de Home Connect il est maintenant possible de piloter fours et plaques de cuisson. Traductions et améliorations visuelles contribuées par Flobul.

## Version 2020-05-07 (07/05/2020)

<https://github.com/Flobul/homeconnect#version-du-07052020>

- BUGFIX : les commandes action de type settings (réglages) ne pouvaient pas être exécutées, il y a avait un / de trop dans l'url
- Ajout du cron qui avait été supprimé pendant le développement. Le temps d'actualisation est réglable. Ne pas oublier d'aller dans la page de Configuration du plugin pour le régler.

## Version 2020-01-26 (26/01/2020)

- Première version beta
- Le mode démo fonctionne

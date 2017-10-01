Google Analytics API v4 Symfony bundle
======================================

Ceci est un fork du bundle mediafigaro/google-analytics-symfony en version 1.0. 
La documenttaion du bundle est disponible à l'adresse : https://mediafigaro.github.io

La version présente ajoute les élements suivants :
* Support Symfony 3 + php 7.0 ;
* Ajout du support des dimensions (1 max) ;
* Ajout d'une class pour le chargement des données pour un jour ou sur un intervalle (mode batch) ;
* Enregistremennt des données dans une base de données (ici, mongoDB) ;

 
>**Cette version s'appuie sur l'API Google et le bundle mediafigaro/google-analytics-api-symfony.**

# Nouveautés version 1.1
Cette version a été qualifiée pour Symfony 3.x et Php 7.0. Elle ajoute le support des dimensions et propose 
un mode batch pour récupérer les données sur une plage de temps donnée.

## Utilisation 

Comme pour les métriques, il suufit d'invoquer la méthode que vous souhaitez utiliser. 
Par exemple dans le cas d'une dimension :
```
    $browser = $analyticsService->getBrowserDateRange($viewId,$date,$date);
```

Pour afficher la répartition des utilisateurs avec le type de navigateur.

Le retour se fait sous la forme de quatre trableaux :
* labelDimensions = nom de la dimention (1 objet, ex. browser) ;
* labelMetrics = nom de la metric (1 objet, ex. sessions);
* arrayDimensions = les valeurs de la dimension (0/n objet(s), ex. Safari, Firefox,...) ;
* arrayMetrics = les valeurs de la métric (0/n objet(s), ex. 1, 4,...) ;
```            
    return [$labelDimensions, $labelMetrics, $arrayDimensions, $arrayMetrics];
```
En cas d'absence de données, on renvoi, deux tableaux à 0 pour la dimention et la metric :
```
    return [$labelDimensions, $labelMetrics, $arrayDimensions[0], $arrayMetrics[0]];
```

## Liste des metrics et dimensions

Le tableau ci-dessous liste les metrics et dimensions ajoutées :

 Metric                 | Dimension
 ------                 | ---------
 users                  | 
 sessionDuration        | 
 sessionsPerUser        |  
 bounces                |
 users                  | browser
 users                  | operatingSystem
 users                  | deviceCategory
 users                  | country
 users                  | city
 users                  | hour
 users                  | month
 uniquePageviews        | 

## Principales modifications

La classe **GoogleAnalyticsService.php** est enrichie d'une nouvelle méthode pour gérer
l'appel à une dimension/metric.
``` 
    private function getDimensionDataDateRange($viewId,$dateStart,$dateEnd,$metric,$dimension) {...}
```
Arguments:
* $viewId ;
* $dateStart ;
* $dateEnd ;
* $metric (nouveau) ;
* $dimension (nouveau) ;

Le controller **GoogleUpdateController.php** a été ajouté pour fournir deux services :
* normal : pour la récupération des données à j-1;
* batch  : pour la récupération des données depuis en date (par défaut : 2017-01-01) ;

La méthode test le $token passé dans l'URL (normal ou batch), puis appel la métode GoogleAPI 
avec deux arguements $date et 'QUOTIDIEN'. Le dernier paramètre est utilisé pour tagger 
l'enregistrement en base de données. 

En mode batch, un affichage sans passé par la vue permet de suivre la progression.
>**Attention, pour éviter les timeout, il faut choisir des intervals de moins de 50 jours**

Chaque données est sauvegarder dans une Collection MongoDB. Avant d'appeler l'API, 
on vérifie si les données sont présentes en base. Si oui, on renvoie **OK**, sinon
on renvoie **Enregistré**. Si une ereur se produit, on renvoie **Erreur**. 

Exemmple de traces.

    Procédure : batch
    ----------
    Date : 2017-01-01 -----> OK
    Date : 2017-01-02 -----> OK
    Date : 2017-01-03 -----> OK
    Date : 2017-01-04 -----> OK
    Date : 2017-01-05 -----> Enregistré
    Date : 2017-01-06 -----> Erreur


## Ajout d'un modèle MongoDB
le dossier Document a été ajouté avec la classe **GoogleAnalytics.php**. Elle
 permet de mapper dans une Collection des données collectées depuis GA.

le modèle est le suivant :
````
{ 
    "_id" : ObjectId("xxxxxxxxxxxxxxxxxxxxxx"), 
    "dateMesure" : "2017-09-30", 
    "ip" : "xxx.xxx.xxx.xxx", 
    "type" : "QUOTIDIEN", 
    "visiteur" : NumberInt(2), 
    "session" : NumberInt(2), 
    "sessionDuration" : 0.0, 
    "sessionsPerUser" : 1.0, 
    "bounces" : NumberInt(2), 
    "bounceRate" : 100.0, 
    "avgTimeOnPage" : 0.0, 
    "pageViewsPerSession" : 1.0, 
    "percentNewVisits" : 100.0, 
    "pageViews" : NumberInt(2), 
    "uniquePageviews" : NumberInt(2), 
    "avgPageLoadTime" : 0.0, 
    "browser" : {
        "0" : "browser", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }, 
    "operatingSystemDateRange" : {
        "0" : "operatingSystem", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }, 
    "deviceCategory" : {
        "0" : "deviceCategory", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }, 
    "country" : {
        "0" : "country", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }, 
    "city" : {
        "0" : "city", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }, 
    "month" : {
        "0" : "month", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }, 
    "hour" : {
        "0" : "hour", 
        "1" : "sessions", 
        "2" : [
            NumberInt(0)
        ], 
        "3" : [
            NumberInt(0)
        ]
    }
}
```` 


# Utilisation

>Ce bundle a été conçu par la division média du magazine Le Figaro http://media.figaro.fr, 
il permet la récupération des métriques issus de la plateforme Google Analytics. 

Il permet un accès simple à l'API Google Analytics API 4 et aux principaux paramètres GA.

Pour pouvoir l'utiliser, il est nécessire de configurer un projet sur Google Console pour Google Analytics, 
obtenir la clé json, puis configurer ce paquet en définissant son chemin. 

## Google Console pour GA

Google Console pour Google Analytics est disponible à l'adresse :
https://console.developers.google.com/apis/

Une fois connecté, il suffit de cliquer sur **identifiants** puis sur le bouton **créer des identifiants** :
![identifiants](doc/API-Google-000.jpg)

Il faut choisir un compte de service :
![creer-un-compte-de-service](doc/API-Google-001.jpg)

Il faut tout d'abord choisir l'option ** Nouveau compte de service ** et définir le compte. Ici, nous avons
choisi comme nom : **stats**

Nous avons choisi comme role le profile **Role viewer** 

L'Id du compte de service est ajouté automatiquement. Il correspond à l'adresse qu'il faudra utiliser dans Google Analytics.

Il faut enfin choisir une clée au format json. Cette clée sera utilisée pour autoriser le serveur à se connecter 
via l'API à Google Analytics.

![creation-compte-de-service](doc/API-Google-002.jpg)
 
Le fichier est téléchargée.
![fichier-json](doc/API-Google-003.jpg)

Le compte de service est créé.
![compte-de-service](doc/API-Google-004.jpg)

Une fois cette opération terminée, il faudra **activer** le service pour pouvoir l'utiliser depuis GA.
![dashboard](doc/API-Google-005.jpg)
 
## Google Analytics 

L'application GA permet de configurer et suivre les indicateurs d'activité d'un site Internet. 
Elle est disponible à l'adresse : https://analytics.google.com/analytics/web/

Pour que l'accès déclaré dans Google Console fonctionne, il est obligatoire d'autoriser 
l'accès avec l'adresse du compte de service. Pour cela, il suffit de cliquer sur le bouton ** Administration **
![administration](doc/API-Google-006.jpg)

et sur **Gestion des utilisateurs** pour ajouter une nouvelle autorisation.
![gestion-des-utilisateurs](doc/API-Google-007.jpg)

Dans la zone **Ajouter des autorisations pour** ajoutez l'adresse créée dans Google Console :
stats-611@med.iam.gserviceaccount.com (ie. dans notre exemple).
![autoriser-une-adresse](doc/API-Google-008.jpg)

Pour vérifier si l'accès est correct, il suffit de saisir l'URL suivante avec le numéro de profil (ex id: 111111111):

http://symfony.dev/app_dev.php/analytics-api/111111111 
![debug](doc/debug.png)


# installation

Il est possible d'installer le bundle depuis composer ou en clonnant le projet. 
```
    composer require lhadjadj/google-analytics-api-symfony
```
    
Puis il faut ajouter la réference du bundle dans /app/AppKernel.php :
```
    $bundles = [
        ...
        new lhadjadj\GoogleAnalyticsApi\GoogleAnalyticsApi(),
    ];
```
# configuration
```
    google_analytics_api.google_analytics_json_key
```

Il est recommandé d'enregistrer la clée dans un dossier inaccessible, 
par exemple dans le dossier app/data. C'est le fichier qui a été 
généré depuis Google Console, il est de la forme **auth-27cef1a4c0fd.json**

Ensuite, il faut ajouter le chemin de la clée dans le fichier **parameters.yml** 
et **parameters.yml.dist** 

/app/config/parameters.yml et /app/config/parameters.yml.dist
```
    google_analytics_json_key: "../app/data/auth-27cef1a4c0fd.json"
    google_analytics_view_id: '123456789'
```

Vous pouvez trouver l'Id sur la page cette page : https://ga-dev-tools.appspot.com/account-explorer/
Il faut trouver le paramètre **view** ou **table id** (sans ga:). 

Et dans le fichier **config.yml**.

/app/config/config.yml
```
    google_analytics_api:
        google_analytics_json_key: "%google_analytics_json_key%"
```
        
# Google Analytics API v4

**Ressources utilises**

* Documentation : https://developers.google.com/analytics/devguides/reporting/core/dimsmets 

* Exemples : https://developers.google.com/analytics/devguides/reporting/core/v4/samples

* Generateur de requête : https://ga-dev-tools.appspot.com/query-explorer/


# debug

Pour activer le debug, il est nécessaire d'ajouter la route suivantes :

/app/config/routing_dev.yml
```
    _google_analytics_api:
        resource: "@GoogleAnalyticsApi/Resources/config/routing_dev.yml"
```
http://symfony.dev/app_dev.php/analytics-api/000000000 

000000000 = correspond au numéro d'identification de la vue :

Le resultat de cette page est :

![debug](doc/debug.png)

# erreurs

En cas d'erreur 403, suivre le lien et authoriser l'accès à l'API v4.

    ...
        "message": "Google Analytics Reporting API has not been used in project xxxxxx-xxxxxx-000000 
        before or it is disabled. Enable it by visiting 
        https://console.developers.google.com/apis/api/analyticsreporting.googleapis.com/overview?project=xxxxxx-xxxxxx-000000 
        then retry. If you enabled this API recently, wait a few minutes for the action to propagate 
        to our systems and retry.",
        "domain": "global",
        "reason": "forbidden"
    }
    ],
    "status": "PERMISSION_DENIED"

# Exemples

Appel du service :
```
    $analyticsService = $this->get('google_analytics_api.api');
    $analytics = $analyticsService->getAnalytics();
```
    
Appel Use the method helpers to get the main metrics within a date range :
```    
    $viewId = '000000000'; // set your view id
```
```    
    // get some metrics (last 30 days, date format is yyyy-mm-dd)
    $sessions = $analyticsService->getSessionsDateRange($viewId,'30daysAgo','today');
    $bounceRate = $analyticsService->getBounceRateDateRange($viewId,'30daysAgo','today');
    $avgTimeOnPage = $analyticsService->getAvgTimeOnPageDateRange($viewId,'30daysAgo','today');
    $pageViewsPerSession = $analyticsService->getPageviewsPerSessionDateRange($viewId,'30daysAgo','today');
    $percentNewVisits = $analyticsService->getPercentNewVisitsDateRange($viewId,'30daysAgo','today');
    $pageViews = $analyticsService->getPageViewsDateRange($viewId,'30daysAgo','today');
    $avgPageLoadTime = $analyticsService->getAvgPageLoadTimeDateRange($viewId,'30daysAgo','today');
```
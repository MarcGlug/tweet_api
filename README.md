# tweet_api
Un exercice de création d'une API de lecture et écriture de tweets :)

N'oubliez pas de changer les données de connexion dans config/Database.php

La lecture des données se fait sous cette forme :

{
    "user" : "",
    "tag" : "",
    "page" : "",
    "per_page" : ""
}

Pour ne pas choisir d'utilisateur et/ou de tag, laisser les champs comme ceci "".
Si les  entrées "page" et "per_page" ne sont pas précisées, les valeurs par défaut seront respectivement 1 et 25.

Pour les tests, Perceval, Yoda_du_63 ou Gimli sont des utilisateurs réguliers de nos services. De même #VDM ou #cestpasfaux sont des tags existants

Pour l'écriture des données, la forme à respecter est la suivante:

{
    "user": "Perceval",
    "message": "Y'en a gros!",
    "tags": [
        {
            "nom": "#VDM"
        },
        {
            "nom": "#cestpasfaux"
        }
    ]
}

Vous pouvez mettre autant de tags que souhaité.

Good Luck Have Fun! ;) 
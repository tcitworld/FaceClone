# Faceclone

## Features

* Publier des messages, visibles dans leur timeline
* Inscription/Connexion/Modification/Ajout d'informations du profil
* Devenir ami avec quelqu'un : envoyer une demande qui doit être accepté
* Envoyer un MP à un ou plusieurs ami-e-s
* Créer un commentaire sur une conversation
* Aimer un post
* Voir un profil de membre

## Installation ##

* Importer dbcitharel.sql en tant que nouvelle BDD.
* composer update
* utiliser le compte (login : tcit@tcit.fr / mdp : titi) ou en créer un nouveau


## Reste à faire

* Pagination correcte (ici implémentée de manière assez ridicule)
* Trouver un moyen de modéliser l'amitié autrement. La solution actuelle est non statisfaisante. Pas certain qu'une table ami qui associe chaque membre avec un autre qui est son ami soit plus efficace.
* Mettre à jour la date de dernière connexion lors de celle-ci ou bien supprimer le champ vu qu'on n'utilise pas cette donnée
* ajouter un membre à une conversation

## Structure de la base de données ##

###Table Membre###

* __ID user unique__
* Nom
* Prenom
* Mail (qui devrait aussi être une clé, mais c'était mal choisi lors de la connexion, donc c'est un peu bancal ainsi (cf Modèle User)).
* Date de Naissance
* Mot de passe chiffré
* Date d'inscription
* Date de dernière connexion
* Education
* Location
* Skills
* Job
* Friends (Aïe)

### Table Post ###

* __ID post__
* ID membre
* Contenu post (markdown pour le formattage \o/)
* Date message
* attachment (cf table Attachment)


### Commentaires ###

* __ID Commentaire__
* ID Membre
* ID Post
* Contenu commentaire
* Date commentaire

### Messages privés ###

* __ID message__
* ID conversation
* id membre
* Contenu message (markdown pour le formattage)
* Date message

### Conversation ###
Table nécessaire ici juste pour un auto_increment non possible dans MP
* __ID conversation__
* titre (faculatif)

### Likes ###
* idpost
* idmembre

### AskFriend ###
Demande d'amitié de user à friend
* __user__
* __friend__

### Attachment ###
* __url__
* titre
* résumé de l'url
* urlimage

### Notifications ###
* idnotif
* idmembre (qui reçoit la notif)
* action (type de notif)
* autremembre (autre membre concerné)
* idpost (post si concerné)
* readstatus (statut : vu/non vu)
* datenotification

### Participent ###
Donne les membres participant à une conversation
* idconversation
* idmembre
* markread (si le membre est à jour de la conversation)
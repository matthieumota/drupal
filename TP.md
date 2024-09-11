### TP Drupal : Gestion des événements (nodes) avec sélection de ville à partir d'une table

#### Objectif :
Dans ce TP, vous allez créer un module Drupal permettant de gérer des **événements** (nodes) associés à des **villes** stockées dans une table personnalisée. Ce module comprendra :
1. La création d'une table pour les villes.
2. Un formulaire d'ajout d'événements avec un champ "ville", qui récupérera ses options à partir de la table des villes.
3. L'affichage des événements avec leurs villes associées via un contrôleur.
4. L'affichage du formulaire d'ajout d'événements dans un bloc.

### Étapes du TP :

---

### Étape 1 : Création du module

1. **Créer un module nommé `event_city_manager`.**
   - Dans le fichier `event_city_manager.info.yml`, définissez les informations de base du module, notamment son nom, sa description et ses dépendances (notamment sur le module "node" de Drupal pour la gestion des événements).

2. **Créer le fichier `event_city_manager.install`** pour créer une table `cities` dans la base de données avec les champs :
   - `id` : identifiant unique de la ville.
   - `city_name` : nom de la ville.

   Vous utiliserez les hooks d'installation pour définir la structure de la table lors de l'activation du module.

---

### Étape 2 : Utilisation des nodes pour les événements

1. **Créer un type de contenu "Événement"** :
   - Via l'interface d'administration de Drupal, créez un type de contenu "Événement" qui comprendra des champs comme le titre (nom de l'événement), la date de l'événement, et la description.

2. **Associer chaque événement à une ville** :
   - Dans le formulaire de création d'un événement, vous allez ajouter un champ de sélection (select) pour choisir une ville parmi celles présentes dans la table `cities`.

---

### Étape 3 : Création de la table des villes

1. **Créer une table `cities`** avec les champs suivants :
   - `id` : clé primaire, identifiant unique de la ville.
   - `city_name` : champ texte pour le nom de la ville.

2. **Insertion des données dans la table des villes** :
   - Vous pouvez insérer quelques villes dans cette table manuellement ou via un script afin d’avoir des données pour le test. Par exemple : Paris, Lyon, Marseille, etc.

---

### Étape 4 : Création d’un formulaire pour les événements

1. **Modifier le formulaire de création d'événements dans `src/Form/EventForm.php`** pour ajouter un champ "ville" :
   - Ce champ "ville" sera de type `select` et récupérera les villes de la table `cities`.
   - Vous utiliserez une requête à la base de données pour récupérer la liste des villes et les afficher dans le champ de sélection.
   
2. **Lorsque le formulaire est soumis** :
   - La ville sélectionnée par l'utilisateur sera sauvegardée dans un champ personnalisé (par exemple, un champ "ville" dans l'entité événement).

3. **Récupérer les villes** :
   - Dans le formulaire, vous allez récupérer les villes depuis la table `cities` et les afficher sous forme de liste déroulante.

---

### Étape 5 : Création d'un contrôleur pour afficher les événements et les villes

1. **Créer un contrôleur dans `src/Controller/EventController.php`** pour afficher la liste des événements avec les villes associées :
   - Ce contrôleur affichera les événements avec leurs informations principales (titre, date, description), ainsi que la ville associée.
   - Vous devrez utiliser une requête à la base de données pour récupérer le nom de la ville correspondant à chaque événement.

2. **Route pour accéder à la liste des événements** :
   - Dans le fichier `event_city_manager.routing.yml`, définissez une route pour accéder à cette page. Par exemple, la page pourrait être accessible via l'URL `/events`.

---

### Étape 6 : Afficher le formulaire d'ajout d'événements dans un bloc

1. **Créer un bloc dans `src/Plugin/Block/EventFormBlock.php`** :
   - Ce bloc permettra d'afficher le formulaire d'ajout d'événements dans une zone de bloc, afin que les utilisateurs puissent ajouter de nouveaux événements depuis le front-end.

2. **Ajouter le bloc via l'interface d'administration Drupal** :
   - Accédez à Structure > Blocs, et placez le bloc contenant le formulaire sur une page de votre site.

---

### Résultats attendus :

1. **Affichage des événements et des villes** :
   - La page `/events` affichera la liste des événements créés, avec leur nom, date, description, et la ville associée (récupérée depuis la table `cities`).

2. **Formulaire d'ajout d'événements** :
   - Le formulaire d'ajout d'événements permettra de sélectionner une ville dans une liste déroulante (les villes seront récupérées depuis la table `cities`).

3. **Bloc avec formulaire** :
   - Le formulaire d'ajout d'événements sera également disponible sous forme de bloc, que vous pourrez placer sur n’importe quelle page via l’interface de gestion des blocs de Drupal.

---

### Améliorations possibles :
- **Validation de la ville sélectionnée** : Assurez-vous que la ville sélectionnée dans le formulaire est valide et existe bien dans la table `cities`.
- **Pagination** : Si la liste des événements est longue, ajoutez une pagination pour améliorer l'affichage.
- **Filtrage par ville** : Ajoutez la possibilité de filtrer les événements par ville, afin de n'afficher que les événements d'une ville donnée.
- **Gestion des villes** : Créez une interface dans Drupal pour ajouter/modifier/supprimer des villes directement depuis le back-end, plutôt que de devoir les gérer manuellement dans la base de données.

<?php

return [

    'title' => [
	'dashboard' => 'Dashboard',
	'users' => 'Utilisateurs',
	'user_management' => 'Gestion utilisateurs',
	'groups' => 'Groupes',
	'roles' => 'Rôles',
	'permissions' => 'Permissions',
	'general' => 'Géneral',
	'cms' => 'CMS',
	'settings' => 'Paramètres',
	'emails' => 'Emails',
	'blog' => 'Blog',
	'posts' => 'Posts',
	'memberships' => 'Adhésions',
	'membership' => 'Adhésion / Renouvellement',
	'categories' => 'Catégories',
	'menus' => 'Menus',
	'menuitems' => 'Menu items',
	'files' => 'Fichiers',
	'subcategories' => 'Sous-catégories',
        'documents' => 'Documents',
    ],
    'user' => [
	'last_name' => 'Nom',
	'first_name' => 'Prénom',
	'civility' => 'Civilité',
	'mr' => 'M',
	'mrs' => 'Mme',
	'birth_name' => 'Nom de naissance',
	'birth_date' => 'Date de naissance',
	'birth_location' => 'Lieu de naissance',
	'citizenship' => 'Nationalité',
	'email' => 'Email',
	'role' => 'Rôle',
	'roles' => 'Rôles',
	'groups' => 'Groupes',
	'password' => 'Mot de passe',
	'confirm_password' => 'Confirmation du mot de passe',
	'create_user' => 'Créer un utilisateur',
	'edit_user' => 'Editer un utilisateur',
        'logout' => 'Déconnexion',
    ],
    'role' => [
	'super-admin' => 'Super Administrator',
	'admin' => 'Administrator',
	'manager' => 'Manager',
	'assistant' => 'Assistant',
	'registered' => 'Registered',
	'create_role' => 'Créer un rôle',
	'edit_role' => 'Editer un rôle',
    ],
    'group' => [
	'create_group' => 'Créer un groupe',
	'edit_group' => 'Editer un groupe',
	'add_selected_groups' => 'Ajouter les groupes sélectionnés',
	'remove_selected_groups' => 'Supprimer les groupes sélectionnés',
	'permission' => 'Permission groupe',
    ],
    'post' => [
	'create_post' => 'Créer un post',
	'edit_post' => 'Editer un post',
	'slug' => 'Slug',
	'content' => 'Contenu',
	'excerpt' => 'Extrait',
	'comments' => 'Commentaires',
	'show_excerpt' => 'Montrer extrait',
	'blog_global_settings' => 'Blog paramètres globaux',
	'post_ordering' => 'Ordre des posts',
	'show_post_excerpt' => 'Montrer l\'extrait du post',
	'show_post_categories' => 'Montrer les catégories du post',
	'show_post_image' => 'Montrer l\'image du post',
        'posts_per_page' => 'Posts par page',
        'customized_posts_per_page' => 'Posts par page personnalisé',
	'posted_by' => 'Posté par :author',
    ],
    'membership' => [
	'edit_membership' => 'Editer un adhérent',
	'expert' => 'Expert',
	'ceseda' => 'CESEDA',
	'liberal_profession' => 'Profession libérale',
	'micro_entrepreneur' => 'Micro-entrepreneur',
	'company' => 'Société',
	'court' => 'Tribunal judiciaire',
	'courts' => 'Tribunaux judiciaires',
	'appeal_court' => 'Cour d\'appel',
	'appeal_courts' => 'Cours d\'appel',
	'licences' => 'Certificats',
	'licence' => 'Certificat',
	'add_licence' => 'Ajouter une licence',
	'delete_licence' => 'Supprimer la licence',
	'add_attestation' => 'Ajouter une attestation',
	'delete_attestation' => 'Supprimer attestation',
	'add_skill' => 'Ajouter une compétence',
	'delete_skill' => 'Supprimer compétence',
	'skill' => 'Compétence',
	'language' => 'Langue',
	'languages' => 'Langue(s)',
	'add_language' => 'Ajouter une langue',
	'delete_language' => 'Supprimer langue',
	'associated_member' => 'Membre associé',
	'expiry_date' => 'Date d\'expiration',
	'interpreter' => 'Interprète',
	'translator' => 'Traducteur',
	'cassation' => 'Cour de cassation',
	'professional_status' => 'Statut professionel',
	'professional_status_info' => 'Information statut professionel',
	'professional_attestation' => 'Attestation professionnelle',
	'siret_number' => 'Numéro Siret',
	'naf_code' => 'Code NAF',
	'linguistic_training' => 'Expérience linguistique',
	'extra_linguistic_training' => 'Expérience extra linguistique',
	'professional_experience' => 'Expérience professionnelle',
	'observations' => 'Observations',
	'why_expertij' => 'Pourquoi voulez vous adhérer à Expertij ?',
	'submit_application' => 'Soumettre votre candidature',
	'update_licences' => 'Mettre à jour le(s) certificat(s)',
	'update_professional_status' => 'Mettre à jour le statut professionnel',
	'members' => 'Adhérents',
        'member_number' => 'Numéro adhérent',
        'member_since' => 'Membre depuis',
        'member_list' => 'Liste des membres',
        'in_member_list' => 'Apparaître dans la liste des membres',
        'pending' => 'En attente',
        'refused' => 'Refusé',
        'pending_subscription' => 'En attente d\'adhésion',
        'cancelled' => 'Annulé',
        'completed' => 'Terminé',
        'applicants' => 'Candidats',
        'member' => 'Membre',
        'pending_renewal' => 'En attente de renouvellement',
        'revoked' => 'Revoqué',
        'cancellation' => 'Annulation',
        'membership_cancellation' => 'Annuler votre adhésion',
        'pending_offline_payment' => 'En attente de chèque ou virement',
	'membership_global_settings' => 'Adhésion paramètres globaux',
	'member_type' => 'Type d\'adhérent',
	'insurance' => 'Assurance',
	'renewal' => 'Renouvellement',
	'prices' => 'Prix',
	'renewal_day' => 'Jour de renouvellement',
	'renewal_month' => 'Mois de renouvellement',
	'renewal_period' => 'Periode de renouvellement',
	'renewal_reminder' => 'Rappel de renouvellement',
	'free_period' => 'Periode de gratuité',
        'subscription_fee' => 'Adhésion',
        'associated_subscription_fee' => 'Adhésion membre associé',
        'insurance_fee_f1' => 'F1 - Assurance: Judiciaire option 1',
        'insurance_fee_f2' => 'F2 - Assurance: Judiciaire option 2',
        'insurance_fee_f3' => 'F3 - Assurance: Judiciaire option 3',
        'insurance_fee_f4' => 'F4 - Assurance: Extrajudiciaire option 1',
        'insurance_fee_f5' => 'F5 - Assurance: Extrajudiciaire option 2',
        'insurance_fee_f6' => 'F6 - Assurance: Extrajudiciaire option 3',
        'insurance_fee_f7' => 'F7 - Assurance: Judiciaire + Extrajudiciaire option 1',
        'insurance_fee_f8' => 'F8 - Assurance: Judiciaire + Extrajudiciaire option 2',
        'insurance_fee_f9' => 'F9 - Assurance: Judiciaire + Extrajudiciaire option 3',
        'insurance_coverage_f1' => 'F1 - Couverture: Judiciaire option 1',
        'insurance_coverage_f2' => 'F2 - Couverture: Judiciaire option 2',
        'insurance_coverage_f3' => 'F3 - Couverture: Judiciaire option 3',
        'insurance_coverage_f4' => 'F4 - Couverture: Extrajudiciaire option 1',
        'insurance_coverage_f5' => 'F5 - Couverture: Extrajudiciaire option 2',
        'insurance_coverage_f6' => 'F6 - Couverture: Extrajudiciaire option 3',
        'insurance_coverage_f7' => 'F7 - Couverture: Judiciaire + Extrajudiciaire option 1',
        'insurance_coverage_f8' => 'F8 - Couverture: Judiciaire + Extrajudiciaire option 2',
        'insurance_coverage_f9' => 'F9 - Couverture: Judiciaire + Extrajudiciaire option 3',
        'insurance_f1' => 'F1 - Judiciaire option 1',
        'insurance_f2' => 'F2 - Judiciaire option 2',
        'insurance_f3' => 'F3 - Judiciaire option 3',
        'insurance_f4' => 'F4 - Extrajudiciaire option 1',
        'insurance_f5' => 'F5 - Extrajudiciaire option 2',
        'insurance_f6' => 'F6 - Extrajudiciaire option 3',
        'insurance_f7' => 'F7 - Judiciaire + Extrajudiciaire option 1',
        'insurance_f8' => 'F8 - Judiciaire + Extrajudiciaire option 2',
        'insurance_f9' => 'F9 - Judiciaire + Extrajudiciaire option 3',
        'subscription' => 'Adhésion',
        'subscription_insurance_f1' => 'F1 - Adhésion + Judiciaire option 1',
        'subscription_insurance_f2' => 'F2 - Adhésion + Judiciaire option 2',
        'subscription_insurance_f3' => 'F3 - Adhésion + Judiciaire option 3',
        'subscription_insurance_f4' => 'F4 - Adhésion + Extrajudiciaire option 1',
        'subscription_insurance_f5' => 'F5 - Adhésion + Extrajudiciaire option 2',
        'subscription_insurance_f6' => 'F6 - Adhésion + Extrajudiciaire option 3',
        'subscription_insurance_f7' => 'F7 - Adhésion + Judiciaire + Extrajudiciaire option 1',
        'subscription_insurance_f8' => 'F8 - Adhésion + Judiciaire + Extrajudiciaire option 2',
        'subscription_insurance_f9' => 'F9 - Adhésion + Judiciaire + Extrajudiciaire option 3',
        'subscription_invoice_filename' => 'reçu-cotisation',
        'insurance_invoice_filename' => 'attestation-assurance',
	'send_emails' => 'Envoyer emails',
	'vote' => 'Voter',
	'votes' => 'Votes',
	'voting' => 'Vote',
	'coverage' => 'Couverture',
	'update_email' => 'Mise à jour email',
    ],
    'sharing' => [
	'create_sharing' => 'Créer un partage de document',
	'edit_sharing' => 'Editer un partage de document',
    ],
    'category' => [
	'create_category' => 'Créer une catégorie',
	'edit_category' => 'Editer une catégorie',
	'parent_category' => 'Catégorie parente',
    ],
    'menu' => [
	'create_menu' => 'Créer un menu',
	'edit_menu' => 'Editer un menu',
    ],
    'menuitem' => [
	'create_menu_item' => 'Créer un élément de menu',
	'edit_menu_item' => 'Editer un élément de menu',
	'parent_item' => 'Elément parent',
	'url' => 'URL',
	'model_name' => 'Nom de modèle',
	'model_example' => 'ex: Post',
	'class' => 'Classe',
	'anchor' => 'Ancre',
    ],
    'email' => [
	'subject' => 'Sujet',
	'html' => 'HTML',
	'plain_text' => 'Plain text',
	'create_email' => 'Créer un email',
	'edit_email' => 'Editer un email',
    ],
    'settings' => [
	'name' => 'Nom du site',
	'timezone' => 'Timezone',
	'date_format' => 'Format de date',
        'collation' => 'Collation à utiliser lors de la recherche',
        'autocomplete_max_results' => 'Autocomplete - lignes de résultat maximum',
	'per_page' => 'Nombre d\'élément par page',
	'admin_email' => 'Email de l\'administrateur du site',
	'theme' => 'Thème utilisé pour le site',
	'allow_registering' => 'Permettre aux invités de s\'enregistrer sur le site',
	'redirect_to_admin' => 'Rediriger les utilisateurs autorisés vers l\'administration une fois connectés.',
        'email_sending_method' => 'Méthode d\'envoi des emails',
	'synchronous' => 'Synchrone',
	'asynchronous' => 'Asynchrone',
    ],
    'address' => [
	'street' => 'Adresse',
	'additional_address' => 'Complément d\'adresse',
	'postcode' => 'Code postal',
	'city' => 'Ville',
	'country' => 'Pays',
	'phone' => 'Téléphone',
	'mobile' => 'Mobile',
    ],
    'generic' => [
	'title' => 'Titre',
	'name' => 'Nom',
	'slug' => 'Slug',
	'code' => 'Code',
	'locale' => 'Langue',
	'locales' => 'Langues',
	'alias' => 'Alias',
	'page' => 'Page',
	'profile' => 'Profil',
	'id' => 'ID',
	'description' => 'Description',
	'created_at' => 'Créé le',
	'owned_by' => 'Appartient à',
	'updated_at' => 'Mis à jour le',
	'updated_by' => 'Mis à jour par',
	'access_level' => 'Niveau d\'accès',
	'private' => 'Privé',
	'public_ro' => 'Public lecture seule',
	'public_rw' => 'Public lecture / écriture',
	'read_only' => 'Lecture Seule',
	'read_write' => 'Lecture Ecriture',
	'read_more' => 'En savoir plus',
	'status' => 'Statut',
	'published' => 'Publié',
	'unpublished' => 'Dépublié',
	'published_up' => 'Début publication',
	'published_down' => 'Fin publication',
	'access' => 'Accès',
	'category' => 'Catégorie',
	'ordering' => 'Ordre',
	'type' => 'Type',
	'size' => 'Taille',
	'date' => 'Date',
	'layout_items' => 'Elément de mise en page',
	'format' => 'Format',
	'submit' => 'Envoyer',
	'back' => 'Retour',
	'preview' => 'Aperçu',
	'none' => 'Aucun(e)',
	'all' => 'Tout(e)',
	'unknown' => 'Inconnu(e)',
	'unknown_user' => 'Utilisateur inconnu',
	'system' => 'Système',
	'image' => 'Image',
	'photo' => 'Photo',
	'extra' => 'Supplément',
	'alt_img' => 'Attribut image alt',
	'select_option' => '- Sélectionner -',
	'batch_title' => 'Champs à mettre à jour pour la sélection des éléments',
	'global_setting' => 'Paramètre global',
	'details' => 'Détails',
	'no_ordering' => 'Aucun ordre',
	'title_asc' => 'Titre ascendant',
	'title_desc' => 'Titre descendant',
	'created_at_asc' => 'Créé le ascendant',
	'created_at_desc' => 'Créé le descendant',
	'updated_at_asc' => 'Mis à jour le ascendant',
	'updated_at_desc' => 'Mis à jour le descendant',
	'order_asc' => 'Ordre ascendant',
	'order_desc' => 'Ordre descendant',
	'yes' => 'Oui',
	'no' => 'Non',
	'all' => 'Tous',
	'show_title' => 'Montrer le titre',
	'show_name' => 'Montrer le nom',
	'show_search' => 'Montrer la recherche',
	'show_description' => 'Montrer la description',
	'show_categories' => 'Montrer les catégories',
	'show_subcategories' => 'Montrer les sous-catégories',
	'show_owner' => 'Montrer le propriétaire',
	'show_created_at' => 'Montrer la date de création',
	'show_image' => 'Montrer l\'image',
	'allow_comments' => 'Autoriser les commentaires',
	'comment_alert' => 'Alerte commentaire',
	'comment' => 'Commentaire',
	'extra_fields' => 'Champs supplémentaires',
	'extra_field_1' => 'Champ supplémentaire 1',
	'extra_field_2' => 'Champ supplémentaire 2',
	'extra_field_3' => 'Champ supplémentaire 3',
	'extra_field_4' => 'Champ supplémentaire 4',
	'extra_field_5' => 'Champ supplémentaire 5',
	'extra_field_6' => 'Champ supplémentaire 6',
	'extra_field_7' => 'Champ supplémentaire 7',
	'extra_field_8' => 'Champ supplémentaire 8',
	'extra_field_9' => 'Champ supplémentaire 9',
	'extra_field_10' => 'Champ supplémentaire 10',
	'extra_field_aliases' => 'Alias de champ supplémentaire',
	'alias_extra_field_1' => 'Alias de champ supplémentaire 1',
	'alias_extra_field_2' => 'Alias de champ supplémentaire 2',
	'alias_extra_field_3' => 'Alias de champ supplémentaire 3',
	'alias_extra_field_4' => 'Alias de champ supplémentaire 4',
	'alias_extra_field_5' => 'Alias de champ supplémentaire 5',
	'alias_extra_field_6' => 'Alias de champ supplémentaire 6',
	'alias_extra_field_7' => 'Alias de champ supplémentaire 7',
	'alias_extra_field_8' => 'Alias de champ supplémentaire 8',
	'alias_extra_field_9' => 'Alias de champ supplémentaire 9',
	'alias_extra_field_10' => 'Alias de champ supplémentaire 10',
	'meta_data' => 'Métadonnées',
	'meta_page_title' => 'Titre de la page',
	'meta_name_description' => 'Description',
	'meta_name_robots' => 'Robots',
	'meta_og_title' => 'Open Graph - titre',
	'meta_og_description' => 'Open Graph - description',
	'meta_og_type' => 'Open Graph - type',
	'meta_og_image' => 'Open Graph - image',
	'meta_og_url' => 'Open Graph - url',
	'meta_og_local' => 'Open Graph - langue',
        'canonical_link' => 'Lien canonique',
        'cookies_privacy' => 'Cookies & Confidentialité',
        'no_update' => 'Pas encore mis à jour',
        'structured_data' => 'Données structurées',
        'since' => 'Depuis',
        'attestation' => 'Attestation',
	'personal_information' => 'Informations personnelles',
	'other' => 'Autre',
	'code_of_ethics' => 'Code de déontologie',
	'statuses' => 'Statuts',
	'internal_rules' => 'Réglement interne',
	'normal' => 'Normal',
	'payments' => 'Paiements',
	'payment' => 'Paiement',
	'insurances' => 'Assurances',
	'pay_now' => 'Payer',
	'missing_document' => 'Document manquant',
	'add_document' => 'Ajouter un document',
	'replace_document' => 'Remplacer un document',
        'january' => 'Janvier',
        'february' => 'Février',
        'march' => 'Mars',
        'april' => 'Avril',
        'may' => 'Mai',
        'june' => 'Juin',
        'july' => 'Juillet',
        'august' => 'Août',
        'september' => 'Septembre',
        'october' => 'Octobre',
        'november' => 'Novembre',
        'december' => 'Décembre',
        'amount' => 'Montant',
        'payment_mode' => 'Mode de paiement',
        'item' => 'Item',
        'cheque' => 'Chèque',
        'bank_transfer' => 'Virement bancaire',
        'website' => 'Site web',
        'photo_deleted' => 'Photo supprimée',
        'document_sharing' => 'Documents Partagés',
	'recipients' => 'Destinataires',
	'shared_with' => 'Partagé(s) avec',
	'document_n' => 'Document :number',
	'optional' => 'Optionel',
        'documents' => 'Documents',
        'document' => 'Document',
        'invoices' => 'Factures',
        'invoice' => 'Facture',
        'resume' => 'Curriculum vitae',
        'running' => 'En cours',
        'expired' => 'Expiré(e)',
        'customized' => 'Personnalisé',
    ],
    'filter' => [
	'search' => 'Recherche',
	'search_by_name' => 'Rechercher par nom',
	'sorted_by' => 'Trié par',
	'per_page' => 'Par page',
	'owned_by' => 'Appartient à',
    ],
    'button' => [
	'new' => 'Nouveau',
	'delete' => 'Supprimer',
	'search' => 'Rechercher',
	'clear' => 'Effacer',
	'clear_all' => 'Tout effacer',
	'save' => 'Sauvegarder',
	'save_close' => 'Sauvegarder & fermer',
	'cancel' => 'Annuler',
	'update' => 'Mettre à jour',
	'edit' => 'Editer',
	'rebuild' => 'Reconstruire',
	'batch' => 'Batch',
	'checkin' => 'Déverrouiller',
	'publish' => 'Publier',
	'unpublish' => 'Dépublier',
	'send_test_email' => 'Envoyer un email de test',
	'accept' => 'Accepter',
	'reject' => 'Rejeter',
	'add' => 'Ajouter',
	'replace' => 'Remplacer',
	'export' => 'Exporter',
    ],
    'pagination' => [
	'results' => 'Affiche :first à :last éléments sur :total résultats',
    ],
    'locales' => [
	'en' => 'Anglais',
	'fr' => 'Français',
	'es' => 'Espagnol',
    ],
];


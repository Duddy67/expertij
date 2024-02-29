<?php

return [

    'dashboard' => [
        'welcome' => 'Bonjour :name. Bienvenue sur Starter CMS.',
        'last_connection' => 'Votre dernière connexion date de: :date.',
        'last_users_logged_in' => 'Les derniers utilisateurs connectés',
    ],
    'user' => [
        'update_success' => 'Utilisateur mis à jour avec succès.',
        'create_success' => 'Utilisateur créé avec succès.',
        'delete_success' => 'L\'utilisateur ":name" a été supprimé avec succès.',
        'delete_list_success' => ':number utilisateurs ont été supprimés avec succès.',
        'edit_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer des utilisateurs.',
        'delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer des utilisateurs.',
        'edit_user_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer cet utilisateur.',
        'update_user_not_auth' => 'Vous n\'êtes pas autorisé(e) à mettre à jour cet utilisateur.',
        'delete_user_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer cet utilisateur.',
        'delete_list_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer cet utilisateur: :name',
        'alert_user_dependencies' => 'Vous ne pouvez pas supprimer l\'utilisateur: :name car il possède :number :dependencies. Veuillez modifier ces dépendances et essayer à nouveau.',
        'unknown_user' => 'Utilisateur inconnu.',
    ],
    'profile' => [
        'update_success' => 'Profil mis à jour avec succès.',
    ],
    'role' => [
        'create_success' => 'Rôle créé avec succès.',
        'update_success' => 'Rôle mis à jour avec succès.',
        'delete_success' => 'Le rôle ":name" a été supprimé avec succès.',
        'delete_list_success' => ':number rôles ont été supprimés avec succès.',
        'edit_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer des rôles.',
        'delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer des rôles.',
        'cannot_update_default_roles' => 'Vous ne pouvez pas modifier les rôles par défaut.',
        'cannot_delete_default_roles' => 'Vous ne pouvez pas supprimer les rôles par défaut.',
        'permission_not_auth' => 'Une ou plusieurs permissions sélectionnées ne sont pas autorisées.',
        'users_assigned_to_roles' => 'Un ou plusieurs utilisateurs sont assignés à ce rôle: :name',
        'cannot_delete_roles' => 'Les rôles suivants ne peuvent être supprimer: :roles',
    ],
    'permission' => [
        'role_does_not_exist' => 'Ce rôle :name n\'existe pas.',
        'invalid_permission_names' => 'Les permissions names: :names sont invalides.',
        'build_success' => ':number les permissions ont été construites avec succès.',
        'rebuild_success' => ':number permissions ont été reconstruites avec succès.',
        'no_new_permissions' => 'Aucune nouvelles permissions n\'ont été construites.',
        'missing_alert' => '(manquant !)',
    ],
    'group' => [
        'create_success' => 'Groupe créé avec succès.',
        'update_success' => 'Groupe mis à jour avec succès.',
        'delete_success' => 'Le groupe ":name" a été supprimé avec succès.',
        'delete_list_success' => ':number groupes ont été supprimés avec succès.',
        'edit_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer des groupes.',
        'delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer des groupes.',
    ],
    'membership' => [
        'create_success' => 'Votre demande d\'adhésion a été créée avec succès. Un administrateur va en être averti.',
        'update_success' => 'Adhésion mise à jour avec succès.',
        'licences_update_success' => 'Certificat(s) mis à jour avec succès.',
        'professional_status_info_required' => 'Le champ :attribute est obligatoire.',
        'appeal_court_required' => 'Le champ :attribute est obligatoire.',
        'court_required' => 'Le champ :attribute est obligatoire.',
        'interpreter_required' => 'Veuillez indiquer si vous êtes interprète ou traducteur.',
        'translator_required' => '',
        'alert_decision_makers' => 'Un email de notification a été envoyé aux décisionnaires.',
        'no_choice' => 'Aucun choix n\'a été fait.',
        'thanks_for_voting' => 'Merci d\'avoir voté. Votre vote a bien été pris en compte.',
        'already_voted' => 'Vous avez déjà voté concernant cette demande d\'adhésion.',
        'start_renewal' => 'La période de renouvellement a démarré et un email a été envoyé aux adhérents.',
        'stop_renewal' => 'La période de renouvellement a été réinitialisée.',
        'reminder_time' => 'Un email de rappel a été envoyé aux adhérents qui n\'ont pas encore payé leur cotisation.',
        'stop_reminder_time' => 'L\'envoi de l\'email de rappel a été réinitialisée.',
        'no_renewal_action' => 'La période de renouvellement est terminée ou le processus de renouvellement a déjà été lancé. Aucune action n\'a été effectuée.',
        'reminder_emails_sent' => 'Les emails de rappel ont bien été envoyés aux membres.',
        'renewal_period_no_reminders' => 'Les emails de rappel ne peuvent pas être envoyés durant la période de renouvellement.',
    ],
    'post' => [
        'create_success' => 'Post créé avec succès.',
        'update_success' => 'Post mis à jour avec succès.',
        'delete_success' => 'Le post ":title" a été supprimé avec succès.',
        'delete_list_success' => ':number posts ont été supprimés avec succès.',
        'edit_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer des posts.',
        'delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer des posts.',
        'publish_list_success' => ':number posts ont été publiés avec succès.',
        'unpublish_list_success' => ':number posts ont été dépubliés avec succès.',
        'create_comment_success' => 'Commentaire créé avec succès.',
        'update_comment_success' => 'Commentaire mis à jour avec succès.',
        'delete_comment_success' => 'Commentaire supprimé avec succès.',
        'edit_comment_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer ce commentaire.',
        'delete_comment_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer ce commentaire.',
        'comments_authentication_required' => 'Vous devez vous authentifier pour poster un commentaire.',
    ],
    'category' => [
        'create_success' => 'Catégorie créée avec succès.',
        'update_success' => 'Catégorie mise à jour avec succès.',
        'delete_success' => 'La catégorie ":name" a été supprimée avec succès.',
        'change_status_list_success' => 'Statuts de catégorie changés avec succès.',
        'no_subcategories' => 'Aucune sous-catégories',
    ],
    'email' => [
        'create_success' => 'Email créé avec succès.',
        'update_success' => 'Email mis à jour avec succès.',
        'delete_success' => 'L\'email ":name" a été supprimé avec succès.',
        'delete_list_success' => ':number emails ont été supprimés avec succès.',
        'edit_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer des emails.',
        'delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer des emails.',
        'test_email_sending' => 'Un email de test est sur le point d\'être envoyé à :email.',
        'test_email_sending_ok' => 'L\'email a été envoyé avec succès',
        'test_email_sending_error' => 'L\'email n\'a pas pu être envoyé. Veuillez vérifier les logs et les paramètres d\'email puis essayer à nouveau.',
    ],
    'document' => [
        'create_success' => 'Document créé avec succès.',
        'replace_success' => 'Document remplacé avec succès.',
        'delete_success' => 'Le document ":name" a été supprimé avec succès.',
        'no_document_to_delete' => 'Aucun document à supprimer.',
        'file_not_found' => 'Fichier non trouvé.',
    ],
    'sharing' => [
        'create_success' => 'Partage de document créé avec succès.',
        'update_success' => 'Partage de document mis à jour avec succès.',
        'delete_success' => 'Partage de document :name supprimé avec succès.',
        'delete_list_success' => ':number partages de document ont été supprimés avec succès.',
        'change_status_list_success' => 'Statuts de partage de document changés avec succès.',
        'alert_document' => 'Un email de notification a été envoyé aux adhérents.',
    ],
    'menu' => [
        'menu_not_found' => 'Le menu ayant le code: :code ne peut être trouvé.',
    ],
    'menuitem' => [
        'create_success' => 'Elément de menu créé avec succès.',
        'update_success' => 'Elément de menu mis à jour avec succès.',
        'delete_success' => 'Elément de menu :title supprimé avec succès.',
        'delete_list_success' => ':number éléments de menu ont été supprimés avec succès.',
        'change_status_list_success' => 'Statuts d\'élément de menu changés avec succès.',
    ],
    'search' => [
        'invalid_keyword_length' => 'Le mot clé doit comporter au moins :length caractères.',
        'no_matches_found' => 'Aucun résultat trouvé.',
    ],
    'message' => [
        'send_success' => 'Votre message a été envoyé avec succès.',
        'send_error' => 'Votre message n\'a pas pu être envoyé. Veuillez demander à l\administrateur de vérifier les logs et les paramètres d\'email.',
    ],
    'general' => [
        'update_success' => 'Paramètres sauvegardés avec succès.',
    ],
    'generic' => [
        'resource_not_found' => 'Resource non trouvé.',
        'access_not_auth' => 'Vous n\'êtes pas autorisé(e) à accéder à cette resource.',
        'edit_not_auth' => 'Vous n\'êtes pas autorisé(e) à éditer cette resource.',
        'create_not_auth' => 'Vous n\'êtes pas autorisé(e) à créer une resource.',
        'delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer cette resource.',
        'change_status_not_auth' => 'Vous n\'êtes pas autorisé(e) à changer le statut de cette resource.',
        'change_order_not_auth' => 'Vous n\'êtes pas autorisé(e) à changer l\'ordre de cette resource.',
        'user_id_does_not_match' => 'L\'id de l\'utilisateur supposé éditer cet élément ne correspond pas à votre id. Ou peut être vous avez été déverrouillé par un administrateur.',
        'owner_not_valid' => 'Le propriétaire de l\'élément n\'est pas valide.',
        'no_item_selected' => 'Aucun élément sélectionné.',
        'mass_update_success' => ':number élément(s) mis à jour avec succès.',
        'mass_delete_success' => ':number élément(s) supprimés avec succès.',
        'check_in_success' => ':number éléments déverrouillés avec succès.',
        'check_in_not_auth' => 'Vous n\'êtes pas autorisé(e) à déverrouiller certains des éléments sélectionnés.',
        'mass_update_not_auth' => 'Vous n\'êtes pas autorisé(e) à mettre à jour certains des éléments sélectionnés.',
        'mass_delete_not_auth' => 'Vous n\'êtes pas autorisé(e) à supprimer certains des éléments sélectionnés.',
        'mass_publish_not_auth' => 'Vous n\'êtes pas autorisé(e) à publier certains des éléments sélectionnés.',
        'mass_unpublish_not_auth' => 'Vous n\'êtes pas autorisé(e) à dépublier certains des éléments sélectionnés.',
        'must_not_be_descendant' => 'Le noeud ne doit pas être un descendant.',
        'item_is_private' => 'L\'élément :name est privé.',
        'item_deleted' => 'Elément supprimée avec succès.',
        'image_deleted' => 'Image supprimée avec succès.',
        'no_item_found' => 'Aucun élément n\'a été trouvé',
        'no_document_to_delete' => 'Aucun document à supprimer.',
        'can_no_longer_create_item' => 'Avertissement: L\'utilisateur ":name" est actuellement le propriétaire de cet élément. Toutefois, il n\'est plus autorisé à créer ce type d\'élément. Veuillez assigner cet élément à un autre utilisateur.',
        'form_errors' => 'Veuillez vérifier les erreurs dans le formulaire.',
        'cookie_info' => 'Ce site utilise des cookies pour offrir une meilleure expérience sur notre site web.',
        'checked_out' => 'Un utilisateur est déjà en train de consulter cet enregistrement. L\'enregistrement sera à nouveau consultable quand cet utilisateur aura terminé.',
        'cannot_send_email' => 'Un ou plusieurs emails n\'ont pas pu être envoyés. Veuillez contacter l\'administrateur du site.',
        'payment_update_success' => 'Le paiement a été mis à jour avec succès.',
        'cheque_payment_success' => 'Votre paiement par chèque a été pris en compte avec succès.',
        'bank_transfer_payment_success' => 'Votre paiement par transfert banquaire a été pris en compte avec succès.',
        'free_period_privilege_success' => 'Votre privilège de gratuité a été pris en compte avec succès.',
    ],
    'js_messages' => [
        'status_change_confirmation' => 'Le statut de l\'adhérent(e) est sur le point d\'être changé. Etes vous sûr(e) ?',
        'payment_status_confirmation' => 'Le statut de paiement est sur le point d\'être pris en compte. Etes vous sûr(e) ?',
        'payment_already_pending' => 'Le statut de paiement est déjà en attente.',
        'confirm_item_deletion' => 'Un élément est sur le point d\'être supprimé. Etes vous sûr(e) ?',
        'confirm_renewal_process' => 'Vous êtes sur le point de lancer le processus de renouvellement. Etes vous sûr(e) ?',
        'no_item_selected' => 'Aucun élément sélectionné.',
        'cheque_payment_confirmation' => 'Vous avez choisi de payer par chèque. Vous allez recevoir un email avec toutes les informations nécessaires. Souhaitez vous continuer ?',
        'bank_transfer_payment_confirmation' => 'Vous avez choisi de payer par virement bancaire. Vous allez recevoir un email avec toutes les informations nécessaires. Souhaitez vous continuer ?',
        'paypal_payment_confirmation' => 'Vous avez choisi de payer via PayPal. Vous allez être redirigé(e) vers PayPal. Souhaitez vous continuer ?',
        'sherlocks_payment_confirmation' => 'Vous avez choisi de payer via LCL. Une carte de credit va vous être proposée. Souhaitez vous continuer ?',
        'free_period_payment_confirmation' => 'Compte tenu de votre adhésion tardive pour cette année, l\'adhésion pour l\'année suivante est gratuite. Souhaitez vous continuer ?',
        'sending_emails_confirmation' => 'Ceci enverra un email de notification aux utilisateurs concernés. Etes vous sûr(e) ?',
        'export_list_confirmation' => 'Ceci exportera la liste d\'adhérents ci-dessous dans un fichier csv. Etes vous sûr(e) ?',
        'renewal_reminder_confirmation' => "Ceci enverra un email de rappel aux adhérents n'ayant toujours pas réglé leur cotisation.\nEtes vous sûr(e) ?\nDernier envoi: ",
    ]
];

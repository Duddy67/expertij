<?php

return [

    'dashboard' => [
        'welcome' => 'Hello :name. Welcome to Starter CMS.',
        'last_connection' => 'Your last connection dates back to: :date.',
        'last_users_logged_in' => 'The last users logged in',
    ],
    'user' => [
        'update_success' => 'User successfully updated.',
        'create_success' => 'User successfully created.',
        'delete_success' => 'The user ":name" has been successfully deleted.',
        'delete_list_success' => ':number users have been successfully deleted.',
        'edit_not_auth' => 'You are not authorised to edit users.',
        'delete_not_auth' => 'You are not authorised to delete users.',
        'edit_user_not_auth' => 'You are not authorised to edit this user.',
        'update_user_not_auth' => 'You are not authorised to update this user.',
        'delete_user_not_auth' => 'You are not authorised to delete this user.',
        'delete_list_not_auth' => 'You are not authorised to delete this user: :name',
        'alert_user_dependencies' => 'You cannot delete this user: :name as he/she owns :number :dependencies. Please modify these dependencies accordingly then try again.',
        'unknown_user' => 'Unknown user.',
    ],
    'profile' => [
        'update_success' => 'Profile successfully updated.',
    ],
    'role' => [
        'create_success' => 'Role successfully created.',
        'update_success' => 'Role successfully updated.',
        'delete_success' => 'The role ":name" has been successfully deleted.',
        'delete_list_success' => ':number roles have been successfully deleted.',
        'edit_not_auth' => 'You are not authorised to edit roles.',
        'delete_not_auth' => 'You are not authorised to delete roles.',
        'cannot_update_default_roles' => 'You cannot modify the default roles.',
        'cannot_delete_default_roles' => 'You cannot delete the default roles.',
        'permission_not_auth' => 'One or more selected permissions are not authorised.',
        'users_assigned_to_roles' => 'One or more users are assigned to this role: :name',
        'cannot_delete_roles' => 'The following roles cannot be deleted: :roles',
    ],
    'permission' => [
        'role_does_not_exist' => 'This role :name does not exist.',
        'invalid_permission_names' => 'The permission names: :names are invalid.',
        'build_success' => ':number permissions have been successfully built.',
        'rebuild_success' => ':number permissions have been successfully rebuilt.',
        'no_new_permissions' => 'No new permissions have been built.',
        'missing_alert' => '(missing !)',
    ],
    'group' => [
        'create_success' => 'Group successfully created.',
        'update_success' => 'Group successfully updated.',
        'delete_success' => 'The group ":name" has been successfully deleted.',
        'delete_list_success' => ':number groups have been successfully deleted.',
        'edit_not_auth' => 'You are not authorised to edit groups.',
        'delete_not_auth' => 'You are not authorised to delete groups.',
    ],
    'membership' => [
        'update_success' => 'Membership successfully updated.',
        'licences_update_success' => 'Licence(s) successfully updated.',
        'professional_status_info_required' => 'The :attribute field is required.',
        'appeal_court_required' => 'The :attribute field is required.',
        'court_required' => 'The :attribute field is required.',
        'interpreter_required' => 'Please indicate if you are a interpreter or a translator.',
        'translator_required' => '',
        'alert_decision_makers' => 'A notification email has been sent to the decision makers.',
        'no_choice' => 'No choice has been made.',
        'thanks_for_voting' => 'Thanks for voting. Your vote has been taken into account.',
        'already_voted' => 'You have already voted regarding this membership request.',
        'start_renewal' => 'The renewal period has started and an email has been sent to the members.',
        'stop_renewal' => 'The renewal period has been reset.',
        'reminder_time' => 'A reminder email has been sent to the members who haven\'t pay their subscription yet.',
        'stop_reminder_time' => 'The reminder sending period has been reset.',
        'no_renewal_action' => 'The renewal period is over or the renewal has already been performed. No action has been performed.',
        'reminder_emails_sent' => 'The reminder emails have been sent to the members.',
        'renewal_period_no_reminders' => 'Reminder emails cannot be sent during the renewal period.',
    ],
    'post' => [
        'create_success' => 'Post successfully created.',
        'update_success' => 'Post successfully updated.',
        'delete_success' => 'The post ":title" has been successfully deleted.',
        'delete_list_success' => ':number posts have been successfully deleted.',
        'edit_not_auth' => 'You are not authorised to edit posts.',
        'delete_not_auth' => 'You are not authorised to delete posts.',
        'publish_list_success' => ':number posts have been successfully published.',
        'unpublish_list_success' => ':number posts have been successfully unpublished.',
        'create_comment_success' => 'Comment successfully created.',
        'update_comment_success' => 'Comment successfully updated.',
        'delete_comment_success' => 'Comment successfully deleted.',
        'edit_comment_not_auth' => 'You are not authorised to edit this comment.',
        'delete_comment_not_auth' => 'You are not authorised to delete this comment.',
        'comments_authentication_required' => 'You must be authenticated to post a comment.',
    ],
    'category' => [
        'create_success' => 'Category successfully created.',
        'update_success' => 'Category successfully updated.',
        'delete_success' => 'The category ":name" has been successfully deleted.',
        'change_status_list_success' => 'Category statuses successfully changed.',
        'no_subcategories' => 'No sub-categories',
    ],
    'email' => [
        'create_success' => 'Email successfully created.',
        'update_success' => 'Email successfully updated.',
        'delete_success' => 'The email ":name" has been successfully deleted.',
        'delete_list_success' => ':number emails have been successfully deleted.',
        'edit_not_auth' => 'You are not authorised to edit emails.',
        'delete_not_auth' => 'You are not authorised to delete emails.',
        'test_email_sending' => 'A test email is about to be sent to :email.',
        'test_email_sending_ok' => 'The email has been successfully sent.',
        'test_email_sending_error' => 'The email could not be sent. Please check the logs and the email settings then try again.',
    ],
    'document' => [
        'create_success' => 'Document successfully created.',
        'replace_success' => 'Document successfully replaced.',
        'delete_success' => 'The document ":name" has been successfully deleted.',
        'no_document_to_delete' => 'No document to delete.',
        'file_not_found' => 'File not found.',
    ],
    'menu' => [
        'menu_not_found' => 'The menu with the code: :code cannot be found.',
    ],
    'menuitem' => [
        'create_success' => 'Menu item successfully created.',
        'update_success' => 'Menu item successfully updated.',
        'delete_success' => 'Menu item successfully deleted.',
        'delete_list_success' => ':number menu items have been successfully deleted.',
        'change_status_list_success' => 'Menu item statuses successfully changed.',
    ],
    'sharing' => [
        'create_success' => 'Document sharing successfully created.',
        'update_success' => 'Document sharing successfully updated.',
        'delete_success' => 'Document sharing successfully deleted.',
        'delete_list_success' => ':number document sharings have been successfully deleted.',
        'change_status_list_success' => 'Document sharing statuses successfully changed.',
        'alert_document' => 'A notification email has been sent to the members.',
    ],
    'search' => [
        'invalid_keyword_length' => 'Keyword must be at least :length characters.',
        'no_matches_found' => 'No matches found.',
    ],
    'message' => [
        'send_success' => 'Your message has been sent successfully.',
        'send_error' => 'Your message could not be sent. Please ask the administrator to check the logs and the email settings.',
    ],
    'general' => [
        'update_success' => 'Parameters successfully saved.',
    ],
    'generic' => [
        'resource_not_found' => 'Resource not found.',
        'access_not_auth' => 'You are not authorised to access this resource.',
        'edit_not_auth' => 'You are not authorised to edit this resource.',
        'create_not_auth' => 'You are not authorised to create a resource.',
        'delete_not_auth' => 'You are not authorised to delete this resource.',
        'change_status_not_auth' => 'You are not authorised to change the status of this resource.',
        'change_order_not_auth' => 'You are not authorised to change the order of this resource.',
        'user_id_does_not_match' => 'The id of the user supposed to edit this item doesn\'t match with your id. Or may be you\'ve been checked in by an administrator.',
        'owner_not_valid' => 'The owner of the item is not valid.',
        'no_item_selected' => 'No item selected.',
        'mass_update_success' => ':number items successfully updated.',
        'mass_delete_success' => ':number item(s) have been successfully deleted.',
        'check_in_success' => ':number items successfully checked-in.',
        'check_in_not_auth' => 'You are not authorised to check-in some of the selected items.',
        'mass_update_not_auth' => 'You are not authorised to update some of the selected items.',
        'mass_delete_not_auth' => 'You are not authorised to delete some of the selected items.',
        'mass_publish_not_auth' => 'You are not authorised to publish some of the selected items.',
        'mass_unpublish_not_auth' => 'You are not authorised to unpublish some of the selected items.',
        'must_not_be_descendant' => 'Node must not be a descendant.',
        'item_is_private' => ':name item is private.',
        'item_deleted' => 'Item successfully deleted.',
        'image_deleted' => 'image successfully deleted.',
        'no_item_found' => 'No item has been found.',
        'no_document_to_delete' => 'No document to delete.',
        'can_no_longer_create_item' => 'Warning: The user ":name" is currently the owner of this item. However, he or she is no longer allowed to create this item type. Please assign this item to a different user.',
        'form_errors' => 'Please check the form for errors.',
        'cookie_info' => 'This website uses cookies to ensure you get the best experience on our website.',
        'checked_out' => 'A user is already checking out this record. This record will be available again once the user is done with it.',
        'cannot_send_email' => 'One or more emails could not be sent. Please contact the website administrator.',
        'payment_update_success' => 'The payment status has been successfully updated.',
        'cheque_payment_success' => 'Your cheque payment has been successfully taken into account.',
        'bank_transfer_payment_success' => 'Your bank transfer payment has been successfully taken into account.',
        'free_period_privilege_success' => 'Your free period privilege has been successfully taken into account.',
    ],
    'js_messages' => [
        'status_change_confirmation' => 'The member status is about to be changed. Are you sure ?',
        'payment_status_confirmation' => 'The payment status is about to be changed. Are you sure ?',
        'payment_already_pending' => 'The payment status is already pending.',
        'confirm_item_deletion' => 'An item is about to be deleted. Are you sure ?',
        'confirm_renewal_process' => 'You are about to run the renewal process. Are you sure ?',
        'no_item_selected' => 'No item selected.',
        'cheque_payment_confirmation' => 'Vous avez choisi de payer par chèque. Vous allez recevoir un email avec toutes les informations nécessaires. Souhaitez vous continuer ?',
        'bank_transfer_payment_confirmation' => 'Vous avez choisi de payer par virement bancaire. Vous allez recevoir un email avec toutes les informations nécessaires. Souhaitez vous continuer ?',
        'paypal_payment_confirmation' => 'Vous avez choisi de payer via PayPal. Vous allez être redirigé(e) vers PayPal. Souhaitez vous continuer ?',
        'sherlocks_payment_confirmation' => 'Vous avez choisi de payer via LCL. Une carte de credit va vous être proposée. Souhaitez vous continuer ?',
        'free_period_payment_confirmation' => 'Compte tenu de votre adhésion tardive pour cette année, l\'adhésion pour l\'année suivante est gratuite. Souhaitez vous continuer ?',
        'sending_emails_confirmation' => 'This will send an email to the concerned users. Are you sure ?',
        'export_list_confirmation' => 'This will export the member list below through a csv file. Are you sure ?',
        'renewal_reminder_confirmation' => 'This will send an reminder to the members who haven\'t paid their membership fee yet. Are you sure ?',
    ]
];

<?php
/* 
------------------
Language: French
------------------
*/

$lang = array();

$lang['runningLang'] = 'fr';

//index
$lang['h1']='Le Trombinoscope';
$lang['title']='Le Trombinoscope';
$lang['title_text']='Ce site est un trombinoscope qui vous permettra, en tant qu\'enseignant d\'avoir une base de données de vos élèves, et en tant qu\'élève, de renseigner vos informations afin qu\'elles soient visibles par ces derniers.';
$lang['status_top'] = 'Quel est votre statut';
$lang['ens_status'] = 'Je suis enseignant';
$lang['stu_status'] = 'Je suis étudiant';
$lang['submit_status'] = 'Valider';

/************************ADMIN*/

$lang['admin_log_title'] = 'Page Administrateur';
$lang['admin_log_return'] = 'Retour';
$lang['admin_log_id'] = 'ADMINISTRATEUR';
$lang['admin_log_mdp'] = 'Mot de passe';
$lang['admin_log_submit'] = 'Connexion';
$lang['admin_log_error2'] = 'Login ou mot de passe incorrect';
$lang['admin_accueil'] = 'Accueil Administrateur';
$lang['error_fields'] = 'Tous les champs sont requis';

$lang['admin_menu_home'] = 'Accueil';
$lang['admin_menu_fil'] = 'Les filières';
$lang['admin_menu_td'] = 'Les groupes de TD';
$lang['admin_menu_ens'] = 'Les enseignants et secrétaires';
$lang['admin_menu_deco'] = 'Déconnexion';

$lang['admin_fil_title'] = 'Les Filières';
$lang['admin_fil_add'] = 'Ajouter une filière';
$lang['admin_fil_add_name'] = 'Nom de la filière';
$lang['admin_fil_add_eff'] = 'Effectif de la filière';
$lang['admin_fil_add_submit'] = 'Ajouter';
$lang['admin_fil_add_error_eff'] = "Votre effectif doit être supérieur à 1 et être un nombre entier";
$lang['admin_fil_add_error_exists'] = 'Cette filière existe déjà';
$lang['admin_fil_add_success'] = 'La filière a été créée';

$lang['admin_fil_supp'] = 'Supprimer une filière';
$lang['admin_fil_supp_name'] = 'Nom de la filière';
$lang['admin_fil_supp_submit'] = 'Supprimer';
$lang['admin_fil_supp_success'] = 'La filière a été supprimée';
$lang['admin_fil_supp_error_noexists'] = 'Cette filière n\'existe pas';

$lang['admin_fil_mod'] = 'Modifier une filière';
$lang['admin_fil_mod_name'] = 'Nom de la filière';
$lang['admin_fil_mod_eff'] = 'Nouvel effectif de la filière';
$lang['admin_fil_mod_submit'] = 'Modifier';
$lang['admin_fil_mod_error_eff'] ='L\'effectif donné est inférieur à la somme des effectifs de TD de la filière';
$lang['admin_fil_mod_success'] = 'La filière a été modififée';
$lang['admin_fil_mod_error_noexists'] = 'Cette filière n\'existe pas';

$lang['admin_td_title'] = 'Les Groupes de TD';
$lang['admin_td_add'] = 'Ajouter un groupe de TD';
$lang['admin_td_add_name'] = 'Nom du groupe de TD';
$lang['admin_td_add_eff'] = 'Effectif du groupe de TD';
$lang['admin_td_add_fil'] = 'Filière du groupe de TD';
$lang['admin_td_add_submit'] = 'Ajouter';
$lang['admin_td_add_error_eff'] = 'Votre effectif doit être compris entre 1 et 45 et être un nombre entier';
$lang['admin_td_add_error_exists'] = 'Ce groupe de TD existe déjà';
$lang['admin_td_add_success'] = 'Le groupe de TD a été créé';

$lang['admin_td_supp'] = 'Supprimer un groupe de TD';
$lang['admin_td_supp_name'] = 'Nom du groupe de TD';
$lang['admin_td_supp_submit'] = 'Supprimer';
$lang['admin_td_supp_error_exists'] = 'Ce groupe de TD n\'existe pas';
$lang['admin_td_supp_success'] = 'Le groupe de TD a été supprimé';

$lang['admin_td_mod'] = 'Modifier un groupe de TD';
$lang['admin_td_mod_name'] = 'Nom du groupe de TD';
$lang['admin_td_mod_eff'] = 'Nouvel effectif du groupe de TD';
$lang['admin_td_mod_submit'] = 'Modifier';
$lang['admin_td_mod_error_exists'] = 'Ce groupe de TD n\'existe pas';
$lang['admin_td_mod_success'] = 'Le groupe de TD a été modifiée';


$lang['admin_td_vide'] = 'Vider les groupes de TD (début d\'année)';
$lang['admin_td_vide_submit'] = 'Réinitialiser';
$lang['admin_td_vide_sure'] = 'Êtes-vous sûr de votre décision ? Cette action est irréversible';
$lang['admin_td_vide_oui'] = 'Oui';
$lang['admin_td_vide_non'] = 'Non';
$lang['admin_td_vide_success'] = 'Les groupes de TD ont été vidé';

$lang['admin_ens_title'] = 'Le Personnel de l\'enseignement';
$lang['admin_ens_add'] = 'Ajouter un personnel de l\'enseignement';
$lang['admin_ens_add_name'] = 'Nom du personnel de l\'enseignement';
$lang['admin_ens_add_forname']  = 'Prénom du personnel de l\'enseignement';
$lang['admin_ens_add_mdp'] = 'Mot de passe temporaire du personnel de l\'enseignement';
$lang['admin_ens_add_submit'] = 'Ajouter';
$lang['admin_ens_add_exist'] = 'Cet enseignant existe déjà';
$lang['admin_ens_add_success'] = 'Ce compte enseignant a été créé';

$lang['admin_ens_supp'] = 'Supprimer un personnel de l\'enseignement';
$lang['admin_ens_supp_name'] = 'Nom et Prénom du personnel de l\'enseignement';
$lang['admin_ens_supp_submit'] = 'Supprimer';
$lang['admin_ens_supp_success'] = 'Le compte a bien été supprimé';
$lang['admin_ens_supp_error'] = 'Une erreur est survenue';

$lang['admin_ens_mod'] = 'Donner un nouveau mot de passe à un personnel de l\'enseignement';
$lang['admin_ens_mod_name'] = 'Nom et Prénom du personnel de l\'enseignement';
$lang['admin_ens_mod_mdp'] = 'Mot de passe';
$lang['admin_ens_mod_submit'] = 'Modifier';
$lang['admin_ens_mod_success'] = 'Le mot de passe a bien été modifié';
$lang['admin_ens_mod_error'] = 'Une erreur est survenue';

$lang['admin_ens_list'] = 'Liste du personnel de l\'enseignement';

/************************ENSEIGNANTS*/

$lang['index_ens'] = 'PERSONNEL EDUCATIF';
$lang['index_ens_mdp'] = 'Mot de passe';
$lang['index_ens_submit'] = 'Connexion';
$lang['index_ens_retour'] = 'Retour';
$lang['index_ens_error'] = 'Login ou mot de passe incorrect';
$lang['index_ens_title'] = 'Page de connexion'; 

$lang['ens_menu_accueil'] = 'Accueil';
$lang['ens_menu_myfil']  = 'Mes filières';
$lang['ens_menu_fil'] = 'Toutes les filières';
$lang['ens_menu_td'] = 'Tous les groupes de TD';
$lang['ens_menu_deco'] = 'Déconnexion';

$lang['accueil_title'] = 'Page d\'accueil';
$lang['ens_accueil'] = 'Changement du mot de passe';
$lang['ens_accueil_old'] = 'Ancien mot de passe';
$lang['ens_accueil_new'] = 'Nouveau mot de passe';
$lang['ens_accueil_confnew'] = 'Confirmation du nouveau mot de passe';
$lang['ens_accueil_submit'] = 'Confirmer la modification';

$lang['ens_footer'] = 'Retour en haut de page';

$lang['trombi'] = 'Le Trombinoscope';
$lang['accueil_title_fav'] = ' - Mes Favoris';
$lang['ens_myfil_download'] = 'Télécharger mes filières au format PDF';
$lang['ens_fil_search'] = 'Rechercher dans le trombinoscope';
$lang['ens_fil_format1'] = '4 photos par rangée';
$lang['ens_fil_format2'] = '5 photos par rangée';
$lang['ens_fil_format3'] = '6 photos par rangée';
$lang['ens_myfil_add'] = 'Ajouter un Groupe de TD aux Favoris';
$lang['ens_myfil_supp'] = 'Supprimer un TD des Favoris';

$lang['ens_fils_download'] = 'Télécharger des filières au format PDF';
$lang['ens_fils_download_form'] = 'Quelle(s) filière(s) voulez-vous télécharger ?';
$lang['ens_fils_download_form_submit'] = 'Télécharger le pdf';
$lang['ens_fil_download'] = 'Télécharger la filière au format PDF';
$lang['ens_td_download'] = 'Télécharger le groupe de TD au format PDF';
$lang['group'] = 'Groupe';


/************************ETUDIANTS*/

$lang['etu_index_title'] = 'Page d\'inscription';
$lang['etu_index_h1'] = 'Le Trombinoscope';
$lang['etu_index_form_mdp'] = 'Mot de passe';
$lang['etu_index_form_forgot'] = 'Mot de passe oublié';
$lang['etu_index_form_error2'] = 'Login ou mot de passe incorrect';
$lang['etu_index_form_img'] = 'Votre login est votre numéro étudiant';
$lang['etu_index_form_submit'] = 'Connexion';

$lang['etu_index_insform_h2'] = 'Créer un compte';
$lang['etu_index_insform_p'] = 'Pour s\'inscrire, rien de plus simple ! Munissez vous de votre N° Etudiant et le tour est joué !';
$lang['etu_index_insform_num'] = 'Numéro étudiant';
$lang['etu_index_insform_name'] = 'Nom';
$lang['etu_index_insform_forname'] = 'Prénom';
$lang['etu_index_insform_email'] = 'E-mail';
$lang['etu_index_insform_mdp'] = 'Mot de passe';
$lang['etu_index_insform_conf'] = 'Confirmation du mot de passe';
$lang['etu_index_insform_fil'] = ' -- Filière -- ';
$lang['etu_index_insform_submit'] = 'Créer un compte';
$lang['etu_index_numetu_error'] = 'Votre numéro étudiant n\'est pas valide';
$lang['etu_index_mail_error'] = 'Votre adresse e-mail n\'est pas valide';
$lang['etu_index_insform_exists'] = 'Vous êtes déjà enregistré dans notre base de données';
$lang['etu_index_insform_success'] = "Votre compte a été créé ! Vous pouvez maintenant vous connecter";

$lang['etu_page_title'] = 'Ma Page Etu';
$lang['etu_page_mdp_title'] = 'Changer le mot de passe';
$lang['etu_page_mdp_old'] = 'Ancien mot de passe';
$lang['etu_page_mdp_new'] = 'Nouveau mot de passe';
$lang['etu_page_mdp_conf'] = 'Confirmation mot de passe';
$lang['etu_page_mdp_submit'] = 'Changer le mot de passe';

$lang['etu_page_mdp_error_match'] = 'Vos mots de passes ne correspondent pas';
$lang['etu_page_mdp_error_false'] = 'Le mot de passe actuel est faux';
$lang['etu_page_mdp_error_security'] = 'Veuillez entrer un mot de passe ayant minimum 6 caractères';
$lang['etu_page_mdp_error_security2'] = 'Veuillez entrer un mot de passe contenant au moins un chiffre et une majuscule';
$lang['etu_page_mdp_error_success'] = 'Votre mot de passe a été modifié avec succès';
$lang['etu_page_mdp_error'] = 'Erreur lors de la sauvegarde du mot de passe';

$lang['etu_page_name'] = 'Nom';
$lang['etu_page_submit'] = 'Modifier';

$lang['etu_page_forname'] = 'Prénom';

$lang['etu_page_email'] = 'Email';
$lang['etu_page_email_error'] = 'Adresse e-mail non valide';

$lang['etu_page_fil'] = 'Filière';

$lang['etu_page_td'] = 'Groupe de TD';

$lang['etu_page_img_error_td'] = 'Renseignez en premier votre Groupe de TD';
$lang['etu_page_img_error_format'] = 'Votre photo n\'est pas sous un format valide';

$lang['etu_page_deco'] = 'Déconnexion';

$lang['etu_mail_subject'] = 'Reinitialisation de votre mot de passe \'Le Trombinoscope\'';
$lang['etu_mail_body'] = 'Voici votre nouveau mot de passe : ';
$lang['etu_mail_success'] = "Email envoyé!";
$lang['etu_mail_error'] = 'Nous ne vous avons pas trouvé dans notre base de donnée...';
$lang['mail_title'] = 'Récupération du mot de passe';
$lang['mail_or'] = 'ou';
$lang['mail_submit'] = 'Réinitialiser';

/****Charts****/

$lang['global_fil_chart_title'] = "Les filières du Département d\'informatique";
$lang['barre_graph_fil_title'] = 'Les effectifs des filières';
$lang['barre_graph_fil_subtitle1'] = 'Effectif Des Groupes de TD de la filière';
$lang['barre_graph_fil_subtitle2'] = 'Effectif de la filière en plus';
$lang['barre_graph_td_eleves_title'] = 'Part d\'élèves inscrits dans les groupes de TD';
$lang['barre_graph_td_eleves_subtitle1'] = 'Elèves inscrits dans le groupe de TD';
$lang['barre_graph_td_eleves_subtitle2'] = 'Elèves manquants dans le groupe de TD';
$lang['barre_graph_td_eleves_subtitle3'] = 'Elèves en trop dans le groupe de TD';
$lang['barre_graph_fil_eleves_title'] = 'Part d\'élèves inscrits dans les filières';
$lang['barre_graph_fil_eleves_subtitle1'] = 'Elèves inscrits dans la filière';
$lang['barre_graph_fil_eleves_subtitle2'] = 'Elèves manquants dans la filière';
$lang['barre_graph_fil_eleves_subtitle3'] = 'Elèves en trop dans la filière';
?>
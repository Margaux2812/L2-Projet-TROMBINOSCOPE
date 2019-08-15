<?php
/* 
------------------
Language: English
------------------
*/

$lang = array();

$lang['runningLang'] = 'en';

$lang['h1']='The Trombinoscope';
$lang['title']='The Trombinoscope';
$lang['title_text']='This is a trombinoscope wich will allow you, as a teacher to have a database of your students, and as a student to fullfill your information so that they can be visible by your teachers.';
$lang['status_top'] = 'What is your status';
$lang['ens_status'] = 'I am a teacher';
$lang['stu_status'] = 'I am a student';
$lang['submit_status'] = 'Validate';
$lang['error_fields'] = 'All fields are required';

/************************ADMIN*/

$lang['admin_log_return'] = 'Return';
$lang['admin_log_id'] = 'ADMINISTRATOR';
$lang['admin_log_mdp'] = 'Password';
$lang['admin_log_submit'] = 'Connection';
$lang['admin_accueil'] = 'Administrator Home';

$lang['admin_log_error2'] = 'Login or password incorrect';

$lang['admin_menu_home'] = 'Home';
$lang['admin_menu_fil'] = 'Courses';
$lang['admin_menu_td'] = 'Tutorial groups';
$lang['admin_menu_ens'] = 'Teachers and secretaries';
$lang['admin_menu_deco'] = 'Log out';

$lang['admin_fil_title'] = 'Courses';
$lang['admin_fil_add'] = 'Add a course';
$lang['admin_fil_add_name'] = 'Name of the course';
$lang['admin_fil_add_eff'] = 'Size of course';
$lang['admin_fil_add_submit'] = 'Add';
$lang['admin_fil_add_error_eff'] = "Your number must be superior to 1 and be a whole number";
$lang['admin_fil_add_error_exists'] = 'This course already exists';
$lang['admin_fil_add_success'] = 'The course has been created';

$lang['admin_fil_supp'] = 'Delete a course';
$lang['admin_fil_supp_name'] = 'Name of the course';
$lang['admin_fil_supp_submit'] = 'Delete';
$lang['admin_fil_supp_success'] = 'The course has been removed';
$lang['admin_fil_supp_error_noexists'] = 'This course does not exist';

$lang['admin_fil_mod'] = 'Edit a course';
$lang['admin_fil_mod_name'] = 'Name  of the course';
$lang['admin_fil_mod_eff'] = 'New size of the course';
$lang['admin_fil_mod_submit'] = 'Edit';
$lang['admin_fil_mod_error_eff'] ='The given number is less than the sum of tutorials\' size in the course';
$lang['admin_fil_mod_success'] = 'The course has been modified';
$lang['admin_fil_mod_error_noexists'] = 'This course does not exist';

$lang['admin_td_title'] = 'Tutorials';
$lang['admin_td_add'] = 'Add a group of tutorial';
$lang['admin_td_add_name'] = 'Name of the tutorial group';
$lang['admin_td_add_eff'] = 'Size of the tutorial group';
$lang['admin_td_add_fil'] = 'Course of the tutorial group';
$lang['admin_td_add_submit'] = 'Add';
$lang['admin_td_add_error_eff'] = 'Your number must be between 1 and 45 and a whole number';
$lang['admin_td_add_error_exists'] = 'This tutorial group already exist';
$lang['admin_td_add_success'] = 'This tutorial group has been created';

$lang['admin_td_supp'] = 'Remove a tutorial group';
$lang['admin_td_supp_name'] = 'Name of the tutorial group';
$lang['admin_td_supp_submit'] = 'Remove';
$lang['admin_td_supp_error_exists'] = 'This tutorial group does not exist';
$lang['admin_td_supp_success'] = 'This tutorial group has been removed';

$lang['admin_td_mod'] = 'Edit a tutorial group';
$lang['admin_td_mod_name'] = 'Name of the tutorial group';
$lang['admin_td_mod_eff'] = 'New size of the tutorial group';
$lang['admin_td_mod_submit'] = 'Edit';
$lang['admin_td_mod_error_exists'] = 'This tutorial group does not exist';
$lang['admin_td_mod_success'] = 'This tutorial group has been edited';


$lang['admin_td_vide'] = 'Clear all the tutorial groups( beginning of the year)';
$lang['admin_td_vide_submit'] = 'Reset';
$lang['admin_td_vide_sure'] = 'Are you sure of your decision? This action is irreversible';
$lang['admin_td_vide_oui'] = 'Yes';
$lang['admin_td_vide_non'] = 'No';
$lang['admin_td_vide_success'] = 'Tutorial groups were emptied';

$lang['admin_ens_title'] = 'Educational Staff';
$lang['admin_ens_add'] = 'Add a teacher/secretary';
$lang['admin_ens_add_name'] = 'Teacher\'s last name';
$lang['admin_ens_add_forname']  = 'Teacher\'s first name';
$lang['admin_ens_add_mdp'] = 'Teacher\'s temporary password';
$lang['admin_ens_add_submit'] = 'Add';
$lang['admin_ens_add_exist'] = 'This teacher already exists';
$lang['admin_ens_add_success'] = 'This teacher\'s account has been created';

$lang['admin_ens_supp'] = 'Remove a teacher';
$lang['admin_ens_supp_name'] = 'Teacher\'s last name and first name';
$lang['admin_ens_supp_submit'] = 'Remove';
$lang['admin_ens_supp_success'] = 'The account has been removed';
$lang['admin_ens_supp_error'] = 'An error has occurred';

$lang['admin_ens_mod'] = 'Give a new password to a teacher';
$lang['admin_ens_mod_name'] = 'Teacher\'s last name and first name';
$lang['admin_ens_mod_mdp'] = 'Password';
$lang['admin_ens_mod_submit'] = 'Modify';
$lang['admin_ens_mod_success'] = 'The password has been modified';
$lang['admin_ens_mod_error'] = 'An error has occurred';

$lang['admin_ens_list'] = 'List of the educational staff';

/************************ENSEIGNANTS*/

$lang['index_ens'] = 'EDUCATIONAL STAFF';
$lang['index_ens_mdp'] = 'Password';
$lang['index_ens_submit'] = 'Connection';
$lang['index_ens_retour'] = 'Return';
$lang['index_ens_error'] = 'Login or password incorrect';
$lang['index_ens_title'] = 'Connection page'; 

$lang['ens_menu_accueil'] = 'Home';
$lang['ens_menu_myfil']  = 'My courses';
$lang['ens_menu_fil'] = 'All courses';
$lang['ens_menu_td'] = 'All tutorial groups';
$lang['ens_menu_deco'] = 'Log out';


$lang['accueil_title'] = 'Home Page';
$lang['accueil_title_fav'] = ' - My Favourites';
$lang['ens_accueil'] = 'Password change form';
$lang['ens_accueil_old'] = 'Former password';
$lang['ens_accueil_new'] = 'New password';
$lang['ens_accueil_confnew'] = 'Confirmation of the new password';
$lang['ens_accueil_submit'] = 'Confirm the modification';

$lang['ens_footer'] = 'Top of the page';

$lang['trombi'] = 'The Trombinoscope';
$lang['ens_myfil_download'] = 'Download my courses in PDF format';
$lang['ens_fil_search'] = 'Research in the chart';
$lang['ens_fil_format1'] = '4 photos per row ';
$lang['ens_fil_format2'] = '5 photos per row';
$lang['ens_fil_format3'] = '6 photos per row';
$lang['ens_myfil_add'] = 'Add a tutorial group to the favourites';
$lang['ens_myfil_supp'] = 'Remove a tutorial\'s group from the favourites';

$lang['ens_fils_download'] = 'Download some courses in PDF format';
$lang['ens_fils_download_form'] = 'Which courses do you want to download?';
$lang['ens_fils_download_form_submit'] = 'Download the PDF';
$lang['ens_fil_download'] = 'Download the course in PDF format';
$lang['ens_td_download'] = 'Download the tutorial group in PDF format';
$lang['group'] = 'Group';

/************************ETUDIANTS*/

$lang['etu_index_title'] = 'Inscription page';
$lang['etu_index_h1'] = 'The Trombinoscope';
$lang['etu_index_form_mdp'] = 'Password';
$lang['etu_index_form_forgot'] = 'Password forgotten';
$lang['etu_index_form_error2'] = 'Login or password incorrect';
$lang['etu_index_form_img'] = 'Your login is your student number';
$lang['etu_index_form_submit'] = 'Connection';

$lang['etu_index_insform_h2'] = 'Create an account';
$lang['etu_index_insform_p'] = 'To register, nothing easier! Input your student number and it\'s done!';
$lang['etu_index_insform_num'] = 'Student number';
$lang['etu_index_insform_name'] = 'Last name';
$lang['etu_index_insform_forname'] = 'First name';
$lang['etu_index_insform_email'] = 'E-mail';
$lang['etu_index_insform_mdp'] = 'Password';
$lang['etu_index_insform_conf'] = 'Confirmation of the password';
$lang['etu_index_insform_fil'] = ' -- Course -- ';
$lang['etu_index_insform_submit'] = 'Create an account';
$lang['etu_index_numetu_error'] = 'Your student number is not valid';
$lang['etu_index_mail_error'] = 'Your email adress is not valid';
$lang['etu_index_insform_exists'] = 'You already exist in our database';
$lang['etu_index_insform_success'] = "Your account has been created ! You can now sign in";

$lang['etu_page_title'] = 'My Student Page';
$lang['etu_page_mdp_title'] = 'Change the password';
$lang['etu_page_mdp_old'] = 'Former password';
$lang['etu_page_mdp_new'] = 'New password';
$lang['etu_page_mdp_conf'] = 'Password confirmation';
$lang['etu_page_mdp_submit'] = 'Change the password';
$lang['etu_page_mdp_error_match'] = 'Your passwords does not match';
$lang['etu_page_mdp_error_false'] = 'The actual password is incorrect';
$lang['etu_page_mdp_error_security'] = 'Please enter a password with a minimum of 6 characters';
$lang['etu_page_mdp_error_security2'] = 'Please enter a password with at least one digit and one uppercase letter';
$lang['etu_page_mdp_error_success'] = 'Your password has been edited with success';
$lang['etu_page_mdp_error'] = 'Error while backing up the password';

$lang['etu_page_name'] = 'Last name';
$lang['etu_page_submit'] = 'Edit';

$lang['etu_page_forname'] = 'First name';

$lang['etu_page_email'] = 'Email';
$lang['etu_page_email_error'] = 'Email address invalid';

$lang['etu_page_fil'] = 'Course';

$lang['etu_page_td'] = 'Tutorial group';

$lang['etu_page_img_error_td'] = 'First, fill your tutorial group';
$lang['etu_page_img_error_format'] = 'Your picture is not under a valid format';

$lang['etu_page_deco'] = 'Log out';

$lang['etu_mail_subject'] = 'Resetting your password of \'The Trombinoscope\'';
$lang['etu_mail_body'] = 'Here is your new password : ';
$lang['etu_mail_success'] = "Email sent!";
$lang['etu_mail_error'] = 'We were not able to find you in our database...';
$lang['mail_title'] = 'Password\'s recovery';
$lang['mail_or'] = 'or';
$lang['mail_submit'] = 'Reset';

/****Charts****/

$lang['global_fil_chart_title'] = "The courses of the Department of Informatics";
$lang['barre_graph_fil_title'] = 'Size of the courses';
$lang['barre_graph_fil_subtitle1'] = 'Size of the tutorial groups of the course';
$lang['barre_graph_fil_subtitle2'] = 'Overflow of the size of the course';
$lang['barre_graph_td_eleves_title'] = 'Portion of students registered in the tutorial groups';
$lang['barre_graph_td_eleves_subtitle1'] = 'Students registered in the tutorial group';
$lang['barre_graph_td_eleves_subtitle2'] = 'Students missing in the tutorial group';
$lang['barre_graph_td_eleves_subtitle3'] = 'Students in excess in the tutorial group';
$lang['barre_graph_fil_eleves_title'] = 'Portion of students registered in the courses';
$lang['barre_graph_fil_eleves_subtitle1'] = 'Students registered in the course';
$lang['barre_graph_fil_eleves_subtitle2'] = 'Students missing in the course';
$lang['barre_graph_fil_eleves_subtitle3'] = 'Students in excess in the course';
?>
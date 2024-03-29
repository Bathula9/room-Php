<?php

// fonction renvoyant true si l'utilisateur est connecté sinon false
function user_is_connected()
{
    if (!empty($_SESSION['membre'])) {
        return true;
    } else {
        return false;
    }
}

// fonction permettant de savoir si l'utilisateur est statut administrateur
function user_is_admin()
{
    if (user_is_connected() && $_SESSION['membre']['statut'] == 2) {
        return true;
    }
    return false;
}

function show_button()
{
    global $produit;
    if ($produit['etat'] == 'libre') {
        return true;
    } else {
        return false;
    }
}

// fonction pour gérer le <title> de la page
function get_name()
{
    $page_name = ucfirst(basename($_SERVER['PHP_SELF'], ".php"));
    $page_name = str_replace('_', ' ', $page_name);
    return $page_name;
}

//  fonction pour gérer une classe active sur les liens du menu
function class_act($page)
{
    $page_name = basename($_SERVER['PHP_SELF'], ".php");
    if (in_array($page_name, $page)) {
        return 'active';
    }
}

















// fonction pour gérer le <title> de la page
function get_title()
{
    $page_name = ucfirst(basename($_SERVER['PHP_SELF'], ".php"));
    $page_name = str_replace('_', ' ', $page_name);
    return $page_name;
}

// fonction pour gérer une classe active sur les liens du menu
function class_active($page)
{
    $page_name = basename($_SERVER['PHP_SELF'], ".php");
    if (in_array($page_name, $page)) {
        return 'active';
    }
}

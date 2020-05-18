<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Phishing Analyzer</title>
        <link rel="shortcut icon" href="<?= base_url()?>/assets/foto/favicon_phising.png">
        <link href="<?php echo base_url() ?>assets/css/landingstyle.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.css"/>
        
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
 
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/jq-3.3.1/dt-1.10.20/af-2.3.4/b-1.6.1/b-colvis-1.6.1/b-flash-1.6.1/b-html5-1.6.1/b-print-1.6.1/cr-1.5.2/fc-3.3.0/fh-3.1.6/kt-2.5.1/r-2.2.3/rg-1.1.1/rr-1.2.6/sc-2.0.1/sl-1.3.1/datatables.min.js"></script>    
    </head>
  <style>
    *{
      font-size : 99.2%
    }
    .loader {
      border: 2px solid #f3f3f3; /* Light grey */
      border-top: 2px solid #3498db; /* Blue */
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }


    @media(max-width:33.9em) {
    .navbar .collapse {max-height:250px;width:100%;overflow-y:auto;overflow-x:hidden;}
    .navbar .navbar-brand {float:none;display: inline-block;}
    .navbar .navbar-nav>.nav-item { float: none; margin-left: .1rem;}
    .navbar .navbar-nav {float:none !important;}
    .nav-item{width:100%;text-align:left;}
    .navbar .collapse .dropdown-menu {
      position: relative;
      float: none;
      background:none;
      border:none;
    }
  }
  
  .ph_section_one{
    overflow-y : hidden;
    padding-top: 2em;
    min-height: 800px ;
    padding-bottom: 2em;
  }


  .section_one{
    padding-top: 6em;
    padding-bottom: 6em;
  }
  
  .section_two{
    padding-top: 2.5em;
    background-color: #f5f5f5;
    padding-bottom: 0em;
  }
  
  .section_three{
    padding-top: 1.5em;
    background-color: #f5f5f5;
    padding-bottom: 1.5em;
  }
  
  .section_four{
    padding-top: 2.5em;
    background-color: #f5f5f5;
    padding-bottom: 2.5em;
  }
  
  .section_five{
    padding-top: 3.5em;
    background-color: #fff;
    padding-bottom: 2.5em;
  }
  
  .section_subheader{
    padding-top: 4.5em;
    background-color: #eee;
    padding-bottom: 3.5em;
  }
  
  .height_card{
    height: 60px;
  }
  
  .card_containers:hover{
    background: #fcfcfc;
  }
  
  .analyzeme{
    cursor: pointer;
    width:100%;
    background:rgb(0, 168, 104);
    color:#fff;
    font-weight:bold;
    padding:30px;
    font-size:20px;
    border : 3px solid rgb(0, 168, 104);
    position:relative;
    left:-1px;
    border-radius: 4px;
  }

  * {
    -webkit-backface-vibisility: hidden;
       -moz-backface-vibisility: hidden;
        -ms-backface-vibisility: hidden;
  }
  
  .breadcrumbs {
    list-style: none;
    margin: 0;
    padding: 0;
  }
  
  .breadcrumbs li {
    list-style: none;
    margin: 0;
    padding: 0;
    display: block;
    float: left;
    font-family: Helvetica Neue,sans-serif;
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: .05em;
    line-height: 20px;
    color: hsl(0, 0%, 30%);
  }
  
  .breadcrumbs li a {
    display: block;
    padding: 0 40px 0 0px;
    color: hsl(0, 0%, 30%);
    text-decoration: none;
    height: 20px;
    position: relative;
    perspective: 700px;
  }
  
  .breadcrumbs li a:after {
    content: '';
    top : 6px;
    width: 8px;
    height: 8px;
    border-color: #333;
    border-style: solid;
    border-width: 1px 1px 0 0;
    outline: 1px solid transparent;
    position: absolute;
    right: 20px;
    -webkit-transition: all .15s ease;
       -moz-transition: all .15s ease;
        -ms-transition: all .15s ease;
            transition: all .15s ease;
    -webkit-transform: rotateZ(45deg) skew(10deg, 10deg);
       -moz-transform: rotateZ(45deg) skew(10deg, 10deg);
        -ms-transform: rotateZ(45deg) skew(10deg, 10deg);
            transform: rotateZ(45deg) skew(10deg, 10deg);
  }
  
  
  .breadcrumbs li a:hover:after {
    right: 15px;
    -webkit-transform: rotateZ(45deg) skew(-10deg, -10deg);
       -moz-transform: rotateZ(45deg) skew(-10deg, -10deg);
        -ms-transform: rotateZ(45deg) skew(-10deg, -10deg);
            transform: rotateZ(45deg) skew(-10deg, -10deg);
  }
  </style>
  <body>
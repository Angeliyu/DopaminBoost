
<!DOCTYPE html>
<html lang="en">



  <head>

    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular.min.js"></script>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">  

    <!-- datepicker-->

    <link href="<?= base_url('assets/css/angular-datepicker.css') ?>" rel="stylesheet" type="text/css" />
    <script src="<?= base_url('assets/js/angular-datepicker.js') ?>"></script>
    <link rel="stylesheet" href="<?= base_url('assets/node_modules/angular-responsive-tables/release/angular-responsive-tables.min.css') ?>">
    
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/angular-responsive-tables/1.2.1/angular-responsive-tables.min.css" />     -->

    <!-- Angular UI Bootstrap -->  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-ui-bootstrap/2.5.0/ui-bootstrap-tpls.min.js"></script>
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Popper.js for Bootstrap -->  
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>  

    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.7.2/angular.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular.min.js"></script> -->
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular-resource.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.7.2/angular-sanitize.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>  
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />  

    <style>
      .header-image {  
        display: flex;  
        justify-content: space-between; /* Center horizontally */  
        align-items: center; /* Center vertically */  
        padding-left:2%;
        padding-top: 0.3%;
        width: 100%;  
        height: 20vh; /* 20% of viewport height */  
        background-image: url('<?= base_url('assets/img/header_bg.png') ?>');  
        background-size: contain;          
        background-repeat: no-repeat; /* No repeat */  
        background-position: center; /* Center the image */  
      } 
      .header-logo {
        font-size: 35px;
        text-align: center;
        font-weight: bold;
        flex-grow: 1; /* Push text to center */
        margin-right: 5%;
      } 
      a {
        text-decoration: none;
      }
    </style>

  </head>


  <body> 
    <div>
      <div class="header-image">
        <img src="<?= base_url('assets/img/Dopamin_Boost_logo.png') ?>" alt="Logo" style="max-width: 16%; margin-left: -1%; margin-top: -0.5%;" />
        <div class="header-logo">Dopamin Boost Admin Portal</div>
      </div>
    
    </div>

    
    

    
  



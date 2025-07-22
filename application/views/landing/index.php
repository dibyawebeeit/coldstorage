<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->

  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/bower_components/font-awesome/css/font-awesome.min.css') ?>">
 
</head>
<body class="hold-transition login-page">


  <div class="banner"  style="background-image: url(../../assets/dist/img/loginbg.jpg)">
        <a href="<?php echo base_url("auth/login"); ?>" class="login-link">LOGIN</a>
        <p class="welcome-tagline">Welcome to</p>
        <h1 class="welcome-text">Cold Storage Management</h1>
        <!--p class="welcome-subtext">Advanced temperature control and monitoring system</p-->
    </div>

<style>
  
  
    .banner {
            /*background:url(https://storage.viewourprojects.com/assets/dist/img/loginbg.jpg) no-repeat 0 0;*/			
            background-size: cover;
            background-position: center;
            /*height: 100vh;*/
            height: 900px;
            
            
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
			
        }


 
        .login-link {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2ecc71;
            color: white;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
            margin-bottom: 30px;
        }

        .login-link:hover {
            background-color: #27ae60;
            transform: scale(1.05);
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
        }

        .welcome-tagline {
            font-size: 1.8rem;
            font-weight: 400;
            margin-bottom: 10px;
            color: #ffffffcc;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
        }

        .welcome-text {
            font-size: 3rem;
            font-weight: 600;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            margin: 0;
            padding: 0 20px;
        }

        .welcome-subtext {
            font-size: 1.5rem;
            font-weight: 300;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            margin-top: 15px;
            max-width: 700px;
            padding: 0 20px;
        }

  </style>

<script src="<?php echo base_url('assets/bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
</body>
</html>

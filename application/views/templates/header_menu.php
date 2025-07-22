<?php
if ($this->session->userdata('logged_in')) {
  $user_id = $this->session->userdata('id'); // or however you're storing it
  $user = get_user_details($user_id);
  $firstName = $user['firstname'];
} else {
  $firstName = 'Annanomous';
}

?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url('dashboard') ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>ADN</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Coldstorage</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <h2 class="welcome-title">Welcome <?=$firstName?></h2>
       <div class="dropdown dropuser">
  <div id="notificationDropdown" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-bell" aria-hidden="true"></i>
    <span class="badge badge-danger" id="notifyCount">0</span>
  </div>
  <ul class="dropdown-menu" aria-labelledby="notificationDropdown">
    <h4 class="menu-title">Notifications</h4>
    <ul id="notificationItems">

      <!-- <li>
      <i class="fa fa-file notiicon"></i>
        <div class="notitxt">sara jonson comment <span> 1 · day ago</span></div>
      </li> -->
      
    </ul>
    
  </ul>
</div>
      
      

      
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  
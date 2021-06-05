<?php
  use App\App;
?>  
<nav id="sidebar">
    <div class="sidebar-header">
      <div class="float-left">
        <a href="#" id="close-sidebar" class="btn"><i class="fas fa-fw fa-times"></i></a>
      </div>
      <span class="sidebar-header-title">Thorbell</span>
    </div>
    <section class="row">
      <ul class="column sidebar-nav">
      <li>
          <a href="<?= App::url('/') ?>" class="hover-blue">
            <!--[https://ionicons.com/]-->
            <i class="icon ion-md-home"></i>
            <span>Home</span>
          </a>
        </li>
        <li>
          <a href="<?= App::url('/events') ?>" class="hover-blue">
            <!--[https://ionicons.com/]-->
            <i class="icon ion-md-camera"></i>
            <span>Event List</span>
          </a>
        </li>        
        <li class="nav-section-heading">
          Settings
        </li>
        <li>
          <a href="<?= App::url('/changepasswd') ?>" class="hover-deep-orange">
            <i class="icon ion-md-key"></i>
            <span>Change Password</span>
          </a>
        </li>
        <li>
          <a href="<?= App::url('/logout') ?>" class="hover-blue-grey">
            <i class="icon ion-md-exit"></i>
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </section>
  </nav>
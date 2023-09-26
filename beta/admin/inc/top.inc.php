<header class="main-header">
    <a href="" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="images/logo_white.png" alt=""/></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="images/logo_white.png" alt=""/></span> 
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="images/img-profile.png" class="user-image" alt="User">
                    <span class="hidden-xs"><?php echo $adminInfo['name'];?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="images/img-profile.png" class="img-circle" alt="User">
                            <p>
                                <?php echo $adminInfo['name'];?> - ADMIN
                            </p>
                            <p>
                                <a href="<?php echo getAdminLink('profile');?>" style="color:rgba(255,255,255,0.8);">Update Profile</a>
                            </p>
                        </li>
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="<?php echo getAdminLink('logout');?>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>
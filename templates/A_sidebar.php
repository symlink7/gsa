          <div class="scroll-sidebar">
            <ul id="sidebar-menu">
              <li class="header"><span>ADMIN</span></li>
              <li>
                <a href="#" title="Users"><i class="glyph-icon icon-user"></i><span>Users</span></a>
                <div class="sidebar-submenu">
                  <ul>
                    <li><a href="index.php?q=users/view_all" title="View Users"><span>View Users</span></a></li>
                    <li><a href="index.php?q=users/add_form" title="Add a User"><span>Add a User</span></a></li>
                  </ul>
                </div><!-- .sidebar-submenu -->
              </li>
              <li>
                <a href="#" title="Clients"><i class="glyph-icon icon-users"></i><span>Clients</span></a>
                <div class="sidebar-submenu">
                  <ul>
                    <li><a href="index.php?q=clients/view_all" title="View Clients"><span>View Clients</span></a></li>
                    <li><a href="index.php?q=clients/add_form" title="Add a Client"><span>Add a Client</span></a></li>
                  </ul>
                </div><!-- .sidebar-submenu -->
              </li>
              <li>
                <a href="#" title="Departments"><i class="glyph-icon icon-users"></i><span>Departments</span></a>
                <div class="sidebar-submenu">
                  <ul>
                    <li><a href="index.php?q=departments/view_all" title="View Departments"><span>View Departments</span></a></li>
                    <li><a href="index.php?q=departments/add_form" title="Add a Department"><span>Add a Department</span></a></li>
                  </ul>
                </div><!-- .sidebar-submenu -->
              </li>
              <li>
                <a href="#" title="Projects"><i class="glyph-icon icon-folder"></i><span>Projects</span></a>
                <div class="sidebar-submenu">
                  <ul>
                    <li><a href="index.php?q=projects/view_all" title="View All Projects"><span>View All Projects</span></a></li>
                    <li><a href="index.php?q=projects/view_all/archived" title="Archived Projects"><span>Archived Projects</span></a></li>
                    <li><a href="index.php?q=projects/view_mine" title="My Projects"><span>My Projects</span></a></li>
                    <li><a href="index.php?q=projects/add_form" title="Add a Project"><span>Add a Project</span></a></li>
                    <li><a href="index.php?q=project_statuses/need_update" title="Projects Needing Update"><span>Projects Needing Update</span></a></li>
                    <li><a href="index.php?q=project_statuses/pending" title="Projects Awaiting Approval"><span>Projects Awaiting Approval</span></a></li>
                    <li><a href="index.php?q=projects/export_form" title="Export Projects"><span>Export Projects</span></a></li>
                    <? if (is_arr_valid($_GET) && ($_GET["wd"])) { ?>
                    <li><a href="index.php?q=projects/bulk_api_status/public" title="Public Projects"><span>Public Projects</span></a></li>
                    <li><a href="index.php?q=projects/bulk_api_status/intranet" title="Projects to show on Intranet"><span>Projects to show on Intranet</span></a></li>
                    <? } ?>
                    <li><a href="index.php?q=city_zips/main_form" title="Cities & Zips"><span>Cities & Zips</span></a></li>
                  </ul>
                </div><!-- .sidebar-submenu -->
              </li>
              <li>
                <a href="#" title="Director's View"><i class="glyph-icon icon-folder"></i><span>Director's View</span></a>
                <div class="sidebar-submenu">
                  <ul>
                    <li><a href="index.php?q=projects/view_approved" title="All Approved Projects"><span>All Approved Projects</span></a></li>
                  </ul>
                  <ul>
                    <li><a href="index.php?q=projects/view_approved/red" title="Red Status"><span>Red Status</span></a></li>
                  </ul>
                  <ul>
                    <li><a href="index.php?q=projects/view_approved/yellow" title="Yellow Status"><span>Yellow Status</span></a></li>
                  </ul>
                  <ul>
                    <li><a href="index.php?q=projects/view_approved/green" title="Green Status"><span>Green Status</span></a></li>
                  </ul>  
                </div><!-- .sidebar-submenu -->
              </li>
            </ul><!-- #sidebar-menu -->
          </div><!-- scroll-sidebar -->
        </div><!-- page-sidebar -->

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">


      </div>

      <ul class="sidebar-menu" data-widget="tree">
        <li class="">
            <a href="{{url('/dashboard')}}">
              <i class="fa fa-dashboard"></i> <span>DASHBOARD</span>
            </a>
        </li>


        <li class="treeview">
            <a href="#">
              <i class="fa fa-bookmark"></i>
              <span>SERVICES</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="{{url('/new-service')}}"><i class="fa fa-circle-o"></i> Add New Service</a></li>
                <li><a href="{{url('/add-content')}}"><i class="fa fa-circle-o"></i> Add SMS Content</a></li>
                <li><a href="{{url('/all-services')}}"><i class="fa fa-circle-o"></i> All Services</a></li>
            </ul>
        </li>



          <li class="treeview">
            <a href="#">
              <i class="fa fa-file"></i>
              <span>AT PROMO</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li><a href="{{url('/add-at-questions')}}"><i class="fa fa-circle-o"></i> Add Questions</a></li>
              <li><a href="{{url('/at-questions')}}"><i class="fa fa-circle-o"></i> View Questions</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-map"></i>
              <span>VF PROMO </span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li><a href="{{url('/add-vf-questions')}}"><i class="fa fa-circle-o"></i> Add Questions</a></li>
              <li><a href="{{url('/vf-questions')}}"><i class="fa fa-circle-o"></i> View Questions</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#">
              <i class="fa fa-users"></i>
              <span>SHORT CODES</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li><a href="{{url('/short-codes')}}"><i class="fa fa-circle-o"></i> List Short codes</a></li>
            </ul>
          </li>

          <li class="treeview">
              <a href="#">
                  <i class="fa fa-users"></i>
                  <span>THIRD PARTIES</span>
                  <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
              </a>
              <ul class="treeview-menu">

                  <li><a href="{{url('/new-third-party')}}"><i class="fa fa-circle-o"></i> New Third Party</a></li>
                  <li><a href="{{url('/third-parties')}}"><i class="fa fa-circle-o"></i> Third Parties</a></li>
                  {{-- <li><a href="{{url('/add-third-party')}}"><i class="fa fa-circle-o"></i> New Third Party</a></li> --}}
              </ul>
          </li>


          <li class="treeview">
            <a href="#">
              <i class="fa fa-list"></i>
              <span>ACCOUNTS</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">

              <li><a href="{{url('/accounts')}}"><i class="fa fa-circle-o"></i> New Account</a></li>
              <li><a href="{{url('/accounts')}}"><i class="fa fa-circle-o"></i> Accounts</a></li>
            </ul>
          </li>







        <li>
          <a href="{{url('/logout')}}">
            <i class="fa fa-sign-out"></i> <span>LOGOUT</span>
          </a>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

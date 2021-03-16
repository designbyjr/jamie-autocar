
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Loggy Mc Logface
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- CSS Files -->
  <link href="../../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../../assets/css/now-ui-dashboard.css?v=1.5.0" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../../assets/demo/demo.css" rel="stylesheet" />
</head>

<body class=" sidebar-mini ">
  <div class="">

    <div class="" id="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="#pablo">Welcome Back <?php echo $params[0]['member_name']?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="/logout">
                    Logout
                  <i class="now-ui-icons users_single-02"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Account</span>
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="panel-header panel-header-sm">
      </div>
      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title"> <?php echo ucfirst($params[0]['member_name'])?>'s Password Table</h4>
              </div>
              <div class="card-body">
                  <div>
                      <?php if(isset($errors) && !empty($errors)):
                          foreach ($errors as $error):
                          ?>
                       <p><b style="color: red"><?php echo $error?></b></p>
                      <?php
                      endforeach;
                      endif;?>
                  </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                      <th>
                        Type
                      </th>
                      <th>
                        Website
                      </th>
                      <th>
                        Password
                      </th>
                      <th class="text-right">
                        Actions
                      </th>
                    </thead>
                    <tbody>
                      <tr>
                          <form action="/add" method="post">
                        <td>
                            <select name="type" id="type">
                                <option value="Social" selected>Social</option>
                                <option value="Finance">Finance</option>
                                <option value="General">General</option>
                                <option value="Bookmarked">Bookmarked</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="website" placeholder="Website: http://">
                        </td>
                        <td>
                            <input type="text" name="website_password" placeholder="Password">
                        </td>
                        <td class="text-right">
                            <button class="btn btn-default btn-square btn-icon" type="submit">
                                <i class="now-ui-icons ui-1_simple-add"></i>
                            </button>
                        </td>
                          </form>
                      </tr>

                      <?php foreach($params as $value):
                        if(isset($value['website'])):
                      ?>

                      <tr>
                          <form action="/delete" method="post" class="id<?php echo $value['id'];?>">
                        <td>
                            <p><?php echo $value['type'];?></p>
                        </td>
                        <td>
                            <p><?php echo $value['website'];?></p>
                        </td>
                        <td>
                            <p><?php echo $value['website_password'];?></p>
                            <input type="hidden" name="id" value="<?php echo $value['id'];?>">
                        </td>
                        <td class="text-right">
                            <button class="btn btn-default btn-square btn-icon" id="id<?php echo $value['id'];?>" type="button">
                                <i class="now-ui-icons arrows-1_cloud-upload-94"></i>
                            </button>
                            <button class="btn btn-danger btn-square btn-icon" type="submit">
                                <i class="now-ui-icons ui-1_simple-remove"></i>
                            </button>
                        </td>
                          </form>

                      </tr>
                            <tr class="id<?php echo $value['id'];?>"  hidden>
                                <td>
                                <form action="/update" method="post" class="id<?php echo $value['id'];?>" disabled>
                                    <td>
                                        <select name="type">
                                        <option value="Social">Social</option>
                                        <option value="Finance">Finance</option>
                                        <option value="General">General</option>
                                        <option value="Bookmarked">Bookmarked</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input  type="text" name="website" class="id<?php echo $value['id'];?>" placeholder="Website: http://" disabled hidden>
                                    </td>
                                    <td>

                                        <input type="text" name="website_password" class="id<?php echo $value['id'];?>" placeholder="Password" hidden disabled>
                                        <input type="hidden" name="id" value="<?php echo $value['id'];?>">

                                        <button class="btn btn-info btn-square btn-icon" type="submit">
                                            <i class="now-ui-icons arrows-1_cloud-upload-94"></i>
                                        </button>
                                    </td>
                                </form>
                                </td>


                            </tr>
                        <?php
                        endif;
                      endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer">

      </footer>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="../../assets/js/core/jquery.min.js"></script>
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../../assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
  <script src="../../assets/js/plugins/bootstrap-switch.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../../assets/js/plugins/sweetalert2.min.js"></script>

  <script src="../../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/now-ui-dashboard.min.js?v=1.5.0" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
  <script src="../../assets/demo/demo.js"></script>
  <script>
      $('document').ready(function (){

          $('button[type="button"]').click(function ()
              {

                  var classes = $(this).attr('id');
                  $("."+classes).each(function (){
                      console.log('hi');
                      $(this).attr('hidden',false);
                      $(this).attr('disabled',false);
                  });
                  $(this).hide();
              }
          );
      });
  </script>
</body>

</html>

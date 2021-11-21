<?php
session_start();
if(!isset( $_SESSION['user_uid'] )){
  header("Location:../");
}
include_once("../conf/config.php");
include_once("../../conf/dbconn.php");
?>
<!doctype html>
<html lang="ko">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>duviequizshow.com</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
  <link rel="stylesheet" href="../../lib/jquery-ui-1.12.1/jquery-ui.min.css" />
  <link rel="stylesheet" href="../../admin/css/style.css?v=<?php echo time(); ?>" />
  <script src="../../lib/jquery-3.4.1.min.js"></script>
  <script src="../../lib/jquery-ui-1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="../../admin/js/script.js?v=<?php echo time(); ?>"></script>
  <script>
  $(document).ready(function(){
    //openUserAdd();
  });
  </script>
</head>
<body>
  <header>
    <nav id="admin-navi" class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
<?php
foreach( $nav_arr as $nav_item ){
  if( AdminNavActivate( $nav_item ) ){
    addHTMLTagLine('<li class="nav-item active">');
  }else{
    addHTMLTagLine('<li class="nav-item">');
  }
addHTMLTagLine('<a class="nav-link" href="'.$nav_item->url.'" target="'.$nav_item->target.'">'.$nav_item->title.'</a>');
addHTMLTagLine('</li>');
}
?>
        </ul>
        <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" href="/admin/proc/logout.php?return=<?php echo $current_url; ?>">[<?php echo $_SESSION['user_id'];?>] 로그아웃</a>
          </div>
        </ul>
      </div>
    </nav>
  </header>
  <main role="main" class="container admin-container">

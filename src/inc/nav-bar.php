<body>
<div class="navbar navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container"> <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a> <a class="brand" href="/">AdPro</a>
      <div class="nav-collapse">
        <ul class="nav">
          <li <?php echo ($controller=="evaluations")? 'class="active"' : ''; ?> ><a href="/evaluations.php">Evaluaciones</a></li>
          <li <?php echo ($controller=="projects")? 'class="active"' : ''; ?> ><a href="/projects.php">Proyectos</a></li>
          <li <?php echo ($controller=="objectives")? 'class="active"' : ''; ?> ><a href="/objectives.php">Objetivos</a></li>
        </ul>
      </div>
      <!--/.nav-collapse --> 
    </div>
  </div>
</div>
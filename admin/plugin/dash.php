<h5>A-MAZE-ING - Administration</h5>
<div class="row">
    <div class="col s12">
        <!-- Moderator -->
<?php
        if(secCheckLevel() == 60) {
        ?>
        <?=($get['side'] == 'bruger') ? 'class="nav-active"' : ''?>
        <?=($get['side'] == 'baade') ? 'class="nav-active"' : ''?>
        <?=($get['side'] == 'arrangementer') ? 'class="nav-active"' : ''?>

        <div class="card grey lighten-4 col s6 m4">
                <div class="card-content center-align">
                           <a href="index.php?side=nyheder" >
                      <i class="fa fa-newspaper-o fa-5x"></i>
                      <h5>Nyheder</h5>
                      </a>
                </div>
        </div>
        <div class="card grey lighten-4 col s6 m4">
                <div class="card-content center-align">
                           <a href="index.php?side=spil" >
                      <i class="fa fa-gamepad fa-5x"></i>
                      <h5>Games</h5>
                      </a>
                </div>
        </div>

        Administrator

        <?php
        } if(secCheckLevel() >= 90) {
        ?>
        <div class="card grey lighten-4 col s6 m4 ">
                <div class="card-content center-align">
                           <a href="index.php?side=bruger" >
                      <i class="fa fa-users fa-5x"></i>
                      <h5>Brugere</h5>
                      </a>
                </div>
        </div>
        <div class="card grey lighten-4 col s6 m4">
                <div class="card-content center-align">
                           <a href="index.php?side=nyheder" >
                      <i class="fa fa-newspaper-o fa-5x"></i>
                      <h5>Nyheder</h5>
                      </a>
                </div>
        </div>
        <div class="card grey lighten-4 col s6 m4">
                <div class="card-content center-align">
                           <a href="index.php?side=spil" >
                      <i class="fa fa-gamepad fa-5x"></i>
                      <h5>Games</h5>
                      </a>
                </div>
        </div>
        <div class="card grey lighten-4 col s6 m4">
                <div class="card-content center-align">
                           <a href="index.php?side=beskeder" >
                      <i class="fa fa-envelope-o fa-5x"></i>
                      <h5>Beskeder</h5>
                      </a>
                </div>
        </div>
        <div class="card grey lighten-4 col s6 m4">
                <div class="card-content center-align">
                           <a href="index.php?side=abonnenter" >
                      <i class="fa fa-paper-plane-o fa-5x"></i>
                      <h5>Abonnenter</h5>
                      </a>
                </div>
        </div>

        <!-- Medlem -->
        
        <?php
        } if(secCheckLevel() == 30) {
         ?>
        <div class="card grey lighten-4 col s12 m4 offset-m4">
                <div class="card-content center-align">
                           <a href="index.php?side=profil" >
                      <i class="fa fa-users fa-5x"></i>
                      <h5>Min Side</h5>
                      </a>
                </div>
        </div>
        <?php
        }
        ?>
        
    </div>
</div>
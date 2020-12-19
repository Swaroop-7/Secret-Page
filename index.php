<?php
    
    session_start();
    
    include("header.php");
    
    if((array_key_exists("id", $_SESSION) AND $_SESSION['id'])) {
           
           header("Location: login.php");
       
     }
     else if(array_key_exists("logout", $_GET)) {

      if(($_GET["logout"] != 1){
       
       unset($_SESSION["id"]);
     }
       
     }
   $error="";
   
   $message="";
   
   if(array_key_exists("submit",$_POST)) {
       
       if(!$_POST["email"]) {
           
           $error.="<p>The Email field is required.<br></p>";
           
       }
       if(!$_POST["password"]) {
           
           $error.="<p>The Password field is required.<br></p>";
           
       }
       if($error!="") {
           
           $error="<p>There were error(s) in form:</p>".$error;
           
       } elseif($_POST["signup"]=="1"){
           
           $query="SELECT id FROM `user` WHERE email='".mysqli_real_escape_string($connection, $_POST['batch'])."'LIMIT 1";
           
           $result=mysqli_query($connection,$query);
           
           if(mysqli_num_rows($result)>0) {
               
               $error = "This Email address is already taken.";
               
           } else {
               
               $query="INSERT INTO `user`(`batch`,`password`) VALUES('".mysqli_real_escape_string($connection, $_POST['batch'])."','".mysqli_real_escape_string($connection, $_POST['password'])."')";
               
               if(!mysqli_query($connection,$query)) {
                   
                   $error = "Sorry! Try again later.";
               } else {
                   
                   $query="UPDATE `user` SET password='".md5(md5(mysqli_insert_id($connection)).$_POST['password'])."' WHERE id=".mysqli_insert_id($connection)." LIMIT 1";
                   
                   mysqli_query($connection,$query);
                   
                   $_SESSION["id"] = mysqli_insert_id($connection);
                   
                   $message="Sign Up Successful.<br>You can login.";
                   
               }
           }
        
       } else {
       
                $query="SELECT * FROM `users` WHERE email='".mysqli_real_escape_string($connection, $_POST["email"])."'";
                
                $result = mysqli_query($connection, $query);
                
                $row = mysqli_fetch_array($result);
                
                if(isset($row)) {
                    
                    $hashedPassword = md5(md5($row["id"]).$_POST['password']);
                    
                    if($hashedPassword == $row['password']) {
                        
                        $_SESSION['id'] = $row['id'];
                        
                        if($_POST['check'] == "1") {
                       
                       setcookie("id",$row['id'], time() + 60*60*24*365);
                       
                    }
                   
                       header("Location:login.php");
                        
                    }else {
                        
                        $error = "This Email/Password does not exist";
                        
                    }
                    
                } else {
                    
                    $error = "This Email/Password does not exist";
                    
                }
       
   }
       
}
?>
     
    <div class="container" id="homepage">
    
       <h1>Secret Page</h1>
       
       <p>Store your thoughts permanently and securely.</p>
     
     <div id="error">
         
         <?php
         
            if($error!=""){
                
                echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
                
            } elseif($message!=""){
                
                echo '<div class="alert alert-success" role="alert">'.$message.'</div>';
                
            }
         
         ?>
         
         </div>
    
    
<form method="post" id="signupform">
    
    <p>Interested? Sign up now.</p>
    
  <fieldset class=" mb-4 form-group">
      
    <input class="form-control" type="email" name="email" placeholder="Your Email">
    
  </fieldset>
  
  <fieldset class=" mb-4 form-group">
      
    <input class="form-control" type="password" name="password" placeholder="Password">
    
  </fieldset>
  
  <div class="checkbox">
      
    <label>
        
        <input type="checkbox" name="check" value="1">
        
        Stay Logged In
        
    </label>
    
  </div>
  
  <input type="hidden" name="signup" value="1">
  
  <fieldset class="mt-4 form-group">
      
  <input type="submit" name="submit" class="btn btn-primary" value="signup">
  
  </fieldset>
  
  <p><a class="toggleForms"><strong>Login</strong></a></p>
  
</form>

<form method="post" id="loginform">
    
    <p>Login using your Email.</p>
    
  <fieldset class=" mb-4 form-group">
      
    <input class="form-control" type="email" name="email" placeholder="Your Email">
    
  </fieldset>
  
  <fieldset class="mb-4 form-group">
      
    <input class="form-control" type="password" name="password" placeholder="Password">
    
  </fieldset>
  
  <div class="checkbox">
      
    <label>
        
        <input type="checkbox" name="check" value="1">
        
        Stay Logged In
        
    </label>
    
  </div>
  
  <input type="hidden" name="signup" value="0">
  
  <fieldset class=" mt-4 form-group">
      
  <input type="submit" name="submit" class="btn btn-primary" value="login">
  
  </fieldset>
  
  <p><a class="toggleForms"><strong>Signup</strong></a></p>
  
</form>

</div>

<script>
    
    $('.toggleForms').click(function() {
            
            $('#signupform').toggle();
            $('#loginform').toggle();
            
        });
    
</script>

<?php include("footer.php"); ?>
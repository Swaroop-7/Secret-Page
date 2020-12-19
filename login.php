<?php
   session_start();
   
   include("header.php");
   
   if(array_key_exists("id", $_COOKIE)) {
       
       $_SESSION["id"] = $_COOKIE["id"];
       
   }
   
   if(array_key_exists("id", $_SESSION)) {
       
       $query="SELECT diary FROM `users` WHERE id=".mysqli_real_escape_string($connection,$_SESSION["id"])." LIMIT 1";
       
       $row=mysqli_fetch_array(mysqli_query($connection,$query));
       
       $diaryContent=$row['diary'];
       
   } else {
       
       header("Location:index.php");
       
   }
   
   if(isset($_POST['submit']))
   {
       
  $textareaValue = trim($_POST['content']);
  
  $query = "UPDATE `users` SET `diary` = '".mysqli_real_escape_string($connection,$textareaValue)."' WHERE id = ".mysqli_real_escape_string($connection,$_SESSION['id'])." LIMIT 1";
  
  mysqli_query($connection, $query);
  
  $affectedRows = mysqli_affected_rows($connection);
  
  $query="SELECT diary FROM `users` WHERE id=".mysqli_real_escape_string($connection,$_SESSION["id"])." LIMIT 1";
       
    $row=mysqli_fetch_array(mysqli_query($connection,$query));
       
    $diaryContent=$row['diary'];
  
  if($affectedRows == 1)
  {
      
    $successMsg = "Record has been saved successfully";
  }
}
?>

<nav class="navbar navbar-light bg-light navbar-fixed-top mb-3">
    
  <a class="navbar-brand" href="#">Secret Page</a>
  
  <div class="pull xs-right">
      
      <a href="index.php?logout=1"><button class="btn btn-outline-success" type="submit">Logout</button></a>
      
  </div>
</nav>



      <div class="container">
        <?php if($successMsg) {
             
             echo '<div class="alert alert-success" role="alert">'.$successMsg.'</div>';
             
         } ?>
         
         </div>
         
         <div class="container-fluid">
		 <center>
            
            <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
   
    <div>
      <textarea class="mt-5 text" name="content" required><?php echo $diaryContent; ?></textarea>
    </div>
    
    <input class="mt-3 btn btn-primary" id="sub" type="submit" name="submit" value="Save">
    
  </form>
  </center>
            
</div>
         
<?php include("footer.php"); ?>
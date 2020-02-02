<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Chat - Customer Module</title>
<link type="text/css" rel="stylesheet" href="style.css" />
</head>
 
<?php
session_start();
function loginForm(){
   echo'
   <div id="loginform">
   <form action="index.php" method="post">
       <p>please enter your name to continue:</p>
       <label for="name">name:</label>
       <input type="text" name="name" id="name" />
       <input type="submit" name="enter" id="enter" value="enter" />
   </form>
   </div>
   ';
}
if(isset($_POST['enter'])){
   if($_POST['name'] != ""){
       $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
       $fp = fopen("log.html", 'a');
       fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has entered the chat session.</i><br></div>");
   }
   else{
       echo '<span class="error">Please type in a name</span>';
   }
}
 
if(isset($_GET['logout'])){
   
   //Simple exit message
   $fp = fopen("log.html", 'a');
   fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
   fclose($fp);
   
   session_destroy();
   header("Location: index.php"); //Redirect the user
}
?>
 
<style>
/* CSS Document */

@font-face{
    font-family: "Ostrich";
    src: url("img/ostrich-regular.ttf");
}

.navbar {
  overflow: hidden;
  background-color: #7d5b5b;
  position: fixed;
  top: 0;
  width: 101%;
  margin-left: -45px;
}
 
.navbar a {
  display: block;
  color: #fae1e1;
  text-align: center;
  padding: 5px;
  text-decoration: none;
  font-size: 30px;
  font-family: "Ostrich";
 
}
 
.navbar a:hover {
  background: #b38181;
  color: #fae1e1;
  }


body {
   font:12px arial;
   color: #222;
   text-align:center;
   padding:35px; }
 form, p, span {
   margin:0;
   padding:0; }
 input { font:12px arial; }
 a {
   color:#0000FF;
   text-decoration:none; }
    a:hover { text-decoration:underline; }
 #wrapper, #loginform {
   margin:0 auto;
   padding-bottom:25px;
   background:#EBF4FB;
   width:504px;
   border-radius: 20px;
   border:1px solid #ACD8F0; }
 #loginform {
   padding-top:18px;
   border-radius: 20px; }
    #loginform p { margin: 5px; }
 #chatbox {
   text-align:left;
   margin:0 auto;
   margin-bottom:25px;
   padding:10px;
   background:#fff;
   height:270px;
   width:430px;
   border:1px solid #ACD8F0;
   overflow:auto; }
 #usermsg {
   width:395px;
   border:1px solid #ACD8F0; }
 #submit { width: 60px; }
 .error { color: #ff0000; }
 #menu { padding:12.5px 25px 12.5px 25px; }
 .welcome { float:left; }
 .logout { float:right; }
 .msgln { margin:0 0 2px 0; }
</style>
 
<?php
if(!isset($_SESSION['name'])){
   loginForm();
}
else{
?>
<div id="wrapper">
   <div id="menu">
       <p class="welcome">Welcome, <b><?php echo $_SESSION['name']; ?></b></p>
       <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
       <div style="clear:both"></div>
   </div>   
   <div id="chatbox"><?php
   if(file_exists("log.html") && filesize("log.html") > 0){
       $handle = fopen("log.html", "r");
       $contents = fread($handle, filesize("log.html"));
       fclose($handle);
   
       echo $contents;
   }
   ?></div>
   
   <form name="message" action="">
       <input name="usermsg" type="text" id="usermsg" size="63" />
       <input name="submitmsg" type="submit"  id="submitmsg" value="Send" />
   </form>
</div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript">
// jQuery Document
$(document).ready(function(){
   $("#exit").click(function(){
       var exit = confirm("Are you sure you want to end the session?");
       if(exit==true){window.location = 'index.php?logout=true';}     
   });
   $("#submitmsg").click(function(){  
       var clientmsg = $("#usermsg").val();
       $.post("post.php", {text: clientmsg});             
       $("#usermsg").attr("value", "");
       return false;
   });
});
 
function loadLog(){    
       var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height before the request
       $.ajax({
           url: "log.html",
           cache: false,
           success: function(html){       
               $("#chatbox").html(html); //Insert chat log into the #chatbox div  
              
               //Auto-scroll          
               var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20; //Scroll height after the request
               if(newscrollHeight > oldscrollHeight){
                   $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
               }              
           },
       });
   }
setInterval (loadLog, 2500);   
</script>
<?php
}
?>
 
 <div class="navbar">
  <a href="http://localhost/UnwindResources/Unwind.html">Home</a>

</div>

</body>
</html>

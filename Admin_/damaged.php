<?php
  include "connection.php";
  include "navbar.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Computer Request</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<style type="text/css">

		.srch
		{
			padding-left: 70%;

		}
		.form-control
		{
			width: 300px;
			height: 40px;
			background-color: black;
			color: white;
		}
		
		body {
      background-image: url(images/com.jfif);
			background-repeat: no-repeat;
  	font-family: "Lato", sans-serif;
  	transition: background-color 0.5s;
    background-size: cover;
    background-position: center;
    position: relative;
    width: 100%;
}

.sidenav {
  height: 100%;
  margin-top: 50px;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #222;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: white;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

#main {
  transition: margin-left .5s;
  padding-left: 15px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}
.img-circle
{
	margin-left: 20px;
}
.h:hover
{
	color:white;
	width: 300px;
	height: 50px;
	background-color: #00544c;
}
.container
{
	height: 800px;
  width: 85%;
	background-color: black;
	opacity: .8;
	color: white;
  margin-top: -65px;
}
.scroll
{
  width: 100%;
  height: 400px;
  overflow: auto;
}
th,td
{
  width: 10%;
}

	</style>

</head>
<body>
<!--_________________sidenav_______________-->
	
	<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>

  			<div style="color: white; margin-left: 60px; font-size: 20px;">

                <?php
                if(isset($_SESSION['login_user']))

                { 	echo "<img class='img-circle profile_img' height=120 width=120 src='images/".$_SESSION['pic']."'>";
                    echo "</br></br>";

                    echo "Welcome ".$_SESSION['login_user']; 
                }
                ?>
            </div><br><br>

 
  <div class="h"> <a href="computers.php">Computers</a></div>
  <div class="h"> <a href="request.php">Computer Request</a></div>
  <div class="h"> <a href="issue_info.php">Issue Information</a></div>
  <div class="h"><a href="damaged.php">Damaged List</a></div>
</div>

<div id="main">
  
  <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; open</span>
	<script>
	function openNav() {
	  document.getElementById("mySidenav").style.width = "300px";
	  document.getElementById("main").style.marginLeft = "300px";
	  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
	}

	function closeNav() {
	  document.getElementById("mySidenav").style.width = "0";
	  document.getElementById("main").style.marginLeft= "0";
	  document.body.style.backgroundColor = "white";
	}
	</script>
  <div class="container">
    
    <?php
      if(isset($_SESSION['login_user']))
      {
        ?>

      <div style="float: left; padding: 25px;">
      <form method="post" action="">
          <button name="submit2" type="submit" class="btn btn-default" style="background-color: #06861a; color: yellow;">Sold</button> 
                      &nbsp&nbsp
          <button name="submit3" type="submit" class="btn btn-default" style="background-color: red; color: yellow;">DAMAGED</button>
      </form>
      </div>

          <div class="srch" >
          <br>
          <form method="post" action="" name="form1">
            <input type="text" name="username" class="form-control" placeholder="Username" required=""><br>
            <input type="text" name="cid" class="form-control" placeholder="CID" required=""><br>
            <button class="btn btn-default" name="submit" type="submit">Submit</button><br><br>
          </form>
        </div>
        <?php

        if(isset($_POST['submit']))
        {

          $res=mysqli_query($db,"SELECT * FROM `order_computer` where username='$_POST[username]' and cid='$_POST[cid]' ;");
      
      while($row=mysqli_fetch_assoc($res))
      {
        $d= strtotime($row['sold']);
        $c= strtotime(date("Y-m-d"));
        $diff= $c-$d;

        if($diff>=0)
        {
          $day= floor($diff/(60*60*24)); 
          $fine= $day*.10;
        }
      }
          $x= date("Y-m-d"); 
          mysqli_query($db,"INSERT INTO fine VALUES ('$_POST[username]', '$_POST[cid]', '$x', '$day', '$fine','not paid') ;");


          $var1='<p style="color:yellow; background-color:green;">SOLD</p>';
          mysqli_query($db,"UPDATE order_computer SET approve='$var1' where username='$_POST[username]' and cid='$_POST[cid]' ");

          mysqli_query($db,"UPDATE computers SET quantity = quantity+1 where cid='$_POST[cid]' ");
          
        }
      }
    
    $c=0;

      
         $ret='<p style="color:yellow; background-color:green;">SOLD</p>';
         $exp='<p style="color:yellow; background-color:red;">DAMAGED</p>';
        
        if(isset($_POST['submit2']))
        {
          
        $sql="SELECT customer.username,id,computers.cid,name,authors,edition,approve,request,order_computer.sold FROM customer inner join order_computer ON customer.username=order_computer.username inner join computers ON order_computer.cid=computers.cid WHERE order_computer.approve ='$ret' ORDER BY `order_computer`.`sold` DESC";
        $res=mysqli_query($db,$sql);

        }
        else if(isset($_POST['submit3']))
        {
        $sql="SELECT customer.username,id,computers.cid,name,authors,edition,approve,request,order_computer.sold FROM customer inner join order_computer ON customer.username=order_computer.username inner join computers ON order_computer.cid=computers.cid WHERE order_computer.approve ='$exp' ORDER BY `order_computer`.`sold` DESC";
        $res=mysqli_query($db,$sql);
        }
        else 
        {
        $sql="SELECT customer.username,id,computers.cid,name,authors,edition,approve,request,order_computer.sold FROM customer inner join order_computer ON customer.username=order_computer.username inner join computers ON order_computer.cid=computers.cid WHERE order_computer.approve !='' and order_computer.approve !='Yes' ORDER BY `order_computer`.`sold` DESC";
        $res=mysqli_query($db,$sql);
        }

        echo "<table class='table table-bordered' style='width:100%;' >";
        //Table header
        
        echo "<tr style='background-color: #6db6b9e6;'>";
        echo "<th>"; echo "Username";  echo "</th>";
        echo "<th>"; echo "id No";  echo "</th>";
        echo "<th>"; echo "CID";  echo "</th>";
        echo "<th>"; echo "Computer Name";  echo "</th>";
        echo "<th>"; echo "Authors Name";  echo "</th>";
        echo "<th>"; echo "Edition";  echo "</th>";
        echo "<th>"; echo "Status";  echo "</th>";
        echo "<th>"; echo "Request Date";  echo "</th>";
        echo "<th>"; echo "sold Date";  echo "</th>";

      echo "</tr>"; 
      echo "</table>";

       echo "<div class='scroll'>";
        echo "<table class='table table-bordered' >";
      while($row=mysqli_fetch_assoc($res))
      {
        echo "<tr>";
          echo "<td>"; echo $row['username']; echo "</td>";
          echo "<td>"; echo $row['id']; echo "</td>";
          echo "<td>"; echo $row['cid']; echo "</td>";
          echo "<td>"; echo $row['name']; echo "</td>";
          echo "<td>"; echo $row['authors']; echo "</td>";
          echo "<td>"; echo $row['edition']; echo "</td>";
          echo "<td>"; echo $row['approve']; echo "</td>";
          echo "<td>"; echo $row['request']; echo "</td>";
          echo "<td>"; echo $row['sold']; echo "</td>";
        echo "</tr>";
      }
    echo "</table>";
        echo "</div>";
    ?>
  </div>
</div>
</body>
</html>
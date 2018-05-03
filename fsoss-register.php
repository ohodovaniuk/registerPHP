<html>
  <head>
    <title>FSOSS Registration</title>
  </head>
  <body>
  <?php
    $servername = "localhost"; $username="161a01";$password="";$database="int_db";
    // create connection
    $conn = new mysqli($servername,$username,$password);
    // check connection
    if(mysqli_errno($conn)){
      die("Connection failed: " . mysqli_errno($conn));
    }
    // select database
     $result = mysqli_select_db($conn,$database);
     // check database
     if(!$result){
       die ("Failed to Connection to ".$database);
     }
  //  echo "Connection Successful";

      $firstNameErr="";$lastNameErr="";$orgNameErr="";$emailErr="";$phoneErr=""; $titleErr=""; $attendErr=""; $shirtErr="";
      $dataValid = true;

      if($_POST){

          if($_POST['t-shirt'][0] == "p"){
            $dataValid = false;
            $shirtErr = " Please Select a Shirt Size";
          }
          if(!$_POST['monday'] && !$_POST['tuesday']){
            $attendErr = "[Must Attend at least One Day]";
            $dataValid = false;
          }
          if($_POST['title'] != "Ms" && $_POST['title'] != "Mr" && $_POST['title'] != "Mrs"){
              $titleErr = "Error - A Title Must be Selected";
              $dataValid = false;
          }
          if($_POST['firstName'] == "") {
              $firstNameErr = "Error - First Name not Valid";
              $dataValid = false;
          }
          if($_POST['lastName'] == "") {
              $lastNameErr = "Error - Last Name not Valid";
              $dataValid = false;
          }
          if($_POST['organization'] == "") {
              $orgNameErr = "Error - Organization not Valid";
              $dataValid = false;
          }
          if($_POST['email'] == "") {
              $emailErr = "Error - Email not Valid";
              $dataValid = false;
          }
          if($_POST['phone'] == "") {
              $phoneErr = "Error - Phone number not Valid";
              $dataValid = false;
          }
      }
      if($_POST && $dataValid) {
        // assign variable to POST values, sql query is unable to work without them
        $phone_num = $_POST['phone'];
        $title = $_POST['title'];
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $org = $_POST['organization'];
        $email = $_POST['email'];
        $aMon = (isset($_POST['monday'])) ? $_POST['monday'] : "N/A";
        $aTue = (isset($_POST['tuesday'])) ? $_POST['tuesday'] : "N/A";
        $sShirt = $_POST['t-shirt'][0];

       $sql = "INSERT INTO FSOS (phone_no,title,first_name,last_name,org,email,attendingMon,attendingTue,shirt)
         VALUES ('$phone_num','$title','$fname','$lname','$org','$email','$aMon','$aTue','$sShirt')";
      if(mysqli_query($conn,$sql)){ // validate query
          echo "Record added succesfully";
      } else {
          echo "Error: ". $sql . "<br>" . mysqli_error($conn);
      }

      // Display database table in a html table
      $qur = "Select * from FSOS";
      $result = mysqli_query($conn,$qur);
      $rowcount = mysqli_num_rows($result);
      if($rowcount > 0){
        echo "<table border='2'><tr><th>Phone</th><th>title</th><th>First Name</th><th>Last Name</th><th>Organization</th><th>Email</th><th>Monday</th><th>Tuesday</th><th>Shirt Size</th></tr>";
          while($row = mysqli_fetch_array($result)){
            $qureyUp = "UPDATE FSOS SET attendingTue='N/A','attendingMon='N/A' WHERE phone_no = '".$fname."'";

            echo "<td>".$row['phone_no']."</td><td>".$row['title']."</td><td>".$row['first_name']."</td><td>"
            .$row['last_name']."</td><td>".$row['org']."</td><td>".$row['email']."</td><td>"
            .$row['attendingMon']."</td><td>".$row['attendingTue']."</td><td>".$row['shirt']."</td><td>
            <a href='something.php?num=".$row['phone_no']."'>CANCEL</a></td></tr>";
          }
          echo "</table>";
      }else{
        echo "0 results";
      }
      echo  "<a href='fsoss-register.php'>Go Back";
      mysqli_close($conn);
    ?>
    <?php
    } else {
     ?>
	<h1>FSOS Registration</h1>
  <form method="POST" action="">
	<table>
	<tr>
    	<td valign="top">Title:</td>
	<td>
		<table>
		<tr>
		<td><input type="radio" name="title" value="Mr" <?php if(isset($_POST['title'])=="Mr") echo "CHECKED";?>>Mr</td><?php echo $titleErr;?>
		</tr>
		<tr>
		<td><input type="radio" name="title" value="Mrs" <?php if(isset($_POST['title'])=="Mrs") echo "CHECKED";?>>Mrs</td>
		</tr>
		<tr>
		<td><input type="radio" name="title" value="Ms"<?php if(isset($_POST['title'])=="Ms") echo "CHECKED";?>>Ms</td>
		</tr>
		</table>
	</td>
	</tr>
	<tr>
    	<td>First name:</td>
	<td><input name="firstName" type="text" value="<?php if(isset($_POST['firstName'])) echo $_POST['firstName']; ?>"><?php echo $firstNameErr;?></td>
	</tr>
	<tr>
    	<td>Last name:</td>
	<td><input name="lastName" type="text" value="<?php if(isset($_POST['lastName'])) echo $_POST['lastName'];?>"><?php echo $lastNameErr;?></td>
	</tr>
	<tr>
    	<td>Organization:</td>
	<td><input name="organization" type="text" value="<?php if(isset($_POST['organization'])) echo  $_POST['organization'];?>"><?php echo $orgNameErr;?></td>
	</tr>
	<tr>
    	<td>Email address:</td>
	<td><input name="email" type="text" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>"><?php echo $emailErr;?></td>
	</tr>
	<tr>
    	<td>Phone number:</td>
	<td><input name="phone" type="text" value="<?php if(isset($_POST['phone'])) echo $_POST['phone'];?>"><?php echo $phoneErr;?></td>
	</tr>
	<tr>
    	<td>Days attending:</td>
	<td>
		<input name="monday" type="checkbox" value="monday"<?php if(isset($_POST['monday'])) echo "CHECKED";?>>Monday
		<input name="tuesday" type="checkbox" value="tuesday"<?php if(isset($_POST['tuesday'])) echo "CHECKED";?>>Tuesday<br><?php echo $attendErr;?></td>
	</tr>
	<tr>
	<td>T-shirt size:</td>
	<td>
	<select name="t-shirt[]">
	<option value="p">--Please choose--</option>
	<option value="s" <?php if(isset($_POST['t-shirt'])) { if (in_array("s",$_POST['t-shirt'])) echo "SELECTED";} ?>>Small</option>
	<option value="m"<?php if(isset($_POST['t-shirt'])) { if (in_array("m",$_POST['t-shirt'])) echo "SELECTED";} ?>>Medium</option>
	<option value="l"<?php if(isset($_POST['t-shirt'])) { if (in_array("l",$_POST['t-shirt'])) echo "SELECTED";} ?>>Large</option>
	<option value="xl"<?php if(isset($_POST['t-shirt'])) { if (in_array("xl",$_POST['t-shirt'])) echo "SELECTED";} ?>>X-Large</option>
	</select><?php echo $shirtErr;?>
	</td>
	</tr>
	<tr><td><br></td></tr>
	<tr>
	<td></td>
	<td><input name="submit" type="submit"></td>
	</tr>
  </form>
  <?php
  }
   ?>
  </body>
</html>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'includes/travel-config.inc.php';

// IN CLASS CODE (for reference only, not part of the project)

# Function to generate data
function generateDate($table, $con, $rowID, $rowValue){
  $sql = "SELECT * FROM $table";
  $result = $con->query($sql);

  while($rows = $result->fetch()){
    ?>
    <option value="<?php print($rows[$rowID]); ?>"><?php print($rows[$rowValue]); ?></option>
    <?php
  }
}

// Validate data

function validate_data($data){
  $data = trim($data);
  $data = htmlspecialchars($data);
  $data = stripslashes($data);
  return $data;
}

// Edit Logic 

if(isset($_POST['editbtn'])){
  if(($_POST['population'] != $_POST['hidden_population']) || ($_POST['neighbours'] != $_POST['hidden_neighbours'])){
    $population = intval(validate_data($_POST['population']));
    $neighbour = validate_data($_POST['neighbours']);
    $id = validate_data($_POST['id']);

    $sql = "UPDATE countries SET Population=?, Neighbours=? WHERE ISO=?";
    $statement = $con->prepare($sql);
    
    if ($statement->execute([$population, $neighbour, $id])) {
        $success_msg ="The update was successful";
    } else {
        $nosuccess_msg = "Error updating record: " . $statement->errorInfo()[2];
    }
    
    }
    else{
      $nosuccess_msg= "No changes were made";
    }

}


// Deleting items
if(isset($_GET['delid']))
{
$delid=$_GET['delid'];
$sql = "DELETE FROM countries WHERE ISO = ?";
$stmt = $con->prepare($sql);
$stmt->execute([$delid]);
echo "<script>alert('Data deleted successfully');</script>"; 
echo "<script>window.location.href = 'ch14-proj1.php'</script>";     
} 


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Chapter 14</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <form action="ch14-proj1.php" method="POST" >
          <div class="form-inline">
          <select name="continent" >
            <option value="0">Select Continent</option>
            <?php generateDate('continents', $con, "ContinentCode", "ContinentName"); ?>
          </select>     
          
          <select name="country">
          <select name="country">
            <option value="0">Select Country</option>
             <?php generateDate('countries', $con, "ISO", "CountryName"); ?>
          </select>    
          <button type="submit" class="btn-primary" name="filter">Filter</button>
          <button type="submit" class="btn-secondary">Reset</button>
          </div>
        </form>
    </header>   
                                    
    <main class="container">
      <?php
        if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['filter']) &&  ($_POST['continent'] !="0" || $_POST["country"] !="0")){
          $continent = $_POST['continent'];
          $country = $_POST['country'];
          

          if(isset($continent) && $continent !="0" && $country =="0"){
            $sql = "SELECT * FROM continents c1, countries c2 WHERE c1.ContinentCode = c2.Continent AND (c1.ContinentCode = :continent AND c2.continent = :continent)";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':continent', $continent, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt;
            

        ?>
          <table class="table">
            <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Continent</th>
                  <th>Country name</th>
                  <th>Capital</th>
                  <th>Population</th>
                  <th>Neighbouring Country Code</th>
                  <th>Edit</th>
                  <th>Delete</th>
                </tr>
            </thead>
        <tbody class="table-group-divider">
          <?php
            $i = 1;
            while ($row = $result ->fetch()){
              $modaledit = "edit".$row["ISO"];
              $id = $row["ISO"];
              
          ?>
          <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row["Time"]; ?></td>
            <td><?php echo $row["Day"]; ?></td>
            <td><?php echo $row["Staff"]; ?></td>
            <td><a href="?editid=<?php echo $row["ISO"]; ?>" data-bs-toggle="modal" data-bs-target="#<?php echo $modaledit;?>"> Edit</a></td>
            

              <!-- Model begin / Bootstrap -->
               <div class="modal fade" id="<?php echo $modaledit;?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Content</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="ch14-proj1.php" method="POST">
                            <div class="form-inline">
                              <p class="col2"><span> New City Population: </span>
                                  <input type="text" name="population" value="<?php  echo $row["Population"];?>"> 
                                  <input type="hidden" name="hidden_population" value="<?php  echo $row["Population"];?>">
                              </p>
                            </div>
                            <div>
                              <p><span>Neighbouring Country:</span>
                                <input type="text"  value="<?php  echo $row["Neighbours"];?>" name='neighbours'>
                                <input type="hidden"  value="<?php  echo $row["Neighbours"];?>" name='hidden_neighbours'>
                                <input type="hidden"  value="<?php  echo $id;?>" name='id'>
                              </p>  
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="editbtn">Save changes</button>
                          </div>
                        </form>
                    </div>
                  </div>
                </div>
              </div>

              <!-- modal ends -->
          <td> <a href="?delid=<?php echo $id;?>" class="delete" title="Delete" data-toggle="tooltip" onclick="return confirm('Do you really want to Delete ?');"> Delete </a> </td>
          </tr>
          <td> <a href="?delid=<?php echo $id;?>" class="delete" title="Delete" data-bs-toggle="tooltip" onclick="return confirm('Do you really want to Delete ?');"> Delete </a> </td>
              $i +=1;
            }
          ?>


        </tbody>
        </table>
        <?php

          }
 
        }

        if(isset($success_msg)){
            echo "<h3 style='color:green;'>".$success_msg."</h3>";
          }
          elseif(isset($nosuccess_msg)){
            echo "<h3 style='color:red;'>".$nosuccess_msg."</h3>";
          }
      
      ?>
      </main>

</body>

</html>
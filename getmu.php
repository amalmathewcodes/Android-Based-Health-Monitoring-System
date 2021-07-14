<?php


if(!empty($_GET))
{
    $uid = $_GET['uid'];
    require_once 'config.php';
    $json_data;
    /*if($uid == 0) // retrieving all medical user as JSON
    {
        $sql = "SELECT * FROM user_details";
        $result_muall = mysqli_query($connection,$sql) or die("Can't get all medical users");
        $all_data = array();
        while($row_all = mysqli_fetch_array($result_muall))
        {
            $single_data = getUserJSON($row_all['id']);
            array_push($all_data, $single_data);
        }
        $json_data = json_encode($all_data);
    }
    else
    {
    */   
      $json_data = json_encode(getUserJSON($uid));
     
    
    echo $json_data;
}
else
{
    echo "NoParams";
}
function getUserJSON($id)
{
    global $connection;
    $sql = "SELECT *,b.id as pid FROM user_details a,login b WHERE b.id =".$id." and a.uid=b.id";
    $result_mu = mysqli_query($connection,$sql) or die("Can't get medical user details, ".  mysqli_error($connection));
    while($row = mysqli_fetch_array($result_mu))
    {
        $data = array(
            'Id' => $row['pid'],
            'PName' => $row['pname'],
            'UName' => $row['username'],
            'Pass' => $row['password'],
            'DOB' => $row['dob'],
            'Address' => $row['address'],
            'MobNo' => $row['mobno'],
            'EMail' => $row['email'],
            'Emergency' => $row['emerg']
                
        );
        
        return $data;
    }
}

?>

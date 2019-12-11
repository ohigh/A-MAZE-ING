<?php
if(secCheckLevel() > 9){
$userId = $_SESSION['userid'];
// if (isset($get['id']) && !empty($get['id']) && is_numeric($get['id'])) {
//     $id = $get['id'];}
    $stmt = $conn->prepare("SELECT `profile_id`, `profile_firstname`, `profile_sirname`, `profile_username`, `profile_age`, `fk_profile_media`,`user_email`
                            FROM tbl_profile 
                            INNER JOIN tbl_user 
							ON tbl_user.fk_user_profile = tbl_profile.profile_id
                            WHERE user_id = :id ");
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

} else {
    echo '<h5>Du har ikke adgang til medlemmer!</h5>';
                $page = 'index.php?side=dash';
                $sec = "5";
                header("Refresh: $sec; url=$page");
            die();
        }
?>
<div class="row">
    <div class="col s12 m10 offset-m1">
        <div class="card">
            <div class="card-content">
                <span class="card-title">Min side</span>
                <div class="row">
                <div class="col s12 m12">
                    <!-- <div class="row"> -->
                        <table>
                            
                            <tbody>
                                <tr>
                                    <td width="150px"><img src="../Images/profile_placeholder.jpg" alt="avatar" class="responsive-img" max-width="90px"></td>
                                    <td width="10px"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>Navn:</b></td>
                                    <td></td>
                                    <td><?=$row["profile_firstname"].' '.$row["profile_sirname"]?></td>
                                </tr>
                                <tr>
                                    <td><b>Email:</b></td>
                                    <td></td>
                                    <td><?=$row["user_email"]?></td>
                                </tr>
                                <tr>
                                    <td><b>Færdighedsniveau:</b></td>
                                    <td></td>
                                    <td><?php if ($row["profile_age"] < "60"){echo "Begynder";} else if ($row["profile_age"] > "59" && $row["profile_age"] < "100") {echo "Øvet";} else if ($row["profile_age"] > "99") {echo "Rutineret";}?></td>
                                </tr>
                                <tr>
                                    <td><b>Roede kilometer:</b></td>
                                    <td></td>
                                    <td><?=$row["profile_age"].' km'?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><b>Tilmeldt:</b></td>
                                    <td></td>
                                    <td><?php
                                        $stmt = $conn->prepare('SELECT event_title, event_date 
                                                              FROM tbl_event
                                                              INNER JOIN tbl_entrant
                                                              ON event_id = fk_entrant_game
                                                              INNER JOIN tbl_user
                                                              ON fk_entrant_user = user_id
                                                              WHERE user_id = :id');
                                        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
                                        $stmt->execute();
                                        while($row = $stmt->fetch(PDO::FETCH_ASSOC))   { 
                                            $dag = date('d', strtotime($row['event_date']));
                                            $maaneder = array("Jannuar","Februar","Marts","April","Maj","Juni","Juli","August","Oktober","September","November","Decmber",);
                                            $pos = date('n', strtotime($row['event_date']))-1; //Er det i Marts giver date funktionen 3, trækkes 1 fra har vi positionen i arrayet ovenfor
                                            $aar = date('Y', strtotime($row['event_date']));
                                            
                                            echo '<p>'.$row['event_title'].' - '.$dag.'. '.$maaneder[$pos].' '.$aar.'</p>';}
                                    ?></td>
                                </tr>
                                
                            </tbody>
                        </table>

                    <div class="col s5">
                        
                    </div>
                    <div class="col s7"></div>
                    <!-- </div> -->
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

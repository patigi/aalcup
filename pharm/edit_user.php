<?php require_once('Connections/pharmConn.php'); ?>
<?php 
$edit_user = mysql_query("SELECT * FROM pharm_users where user_id = '{$_GET['edid']}'")or die(mysql_error());
$row_edituser = mysql_fetch_object($edit_user);

if(isset($_POST['MM_update'])){
$update = mysql_query("UPDATE pharm_users SET firstname='{$_POST['firstname']}', lastname='{$_POST['lastname']}', user_name='{$_POST['username']}', position='{$_POST['position']}', access_level='{$_POST['access_level']}', user_password='{$_POST['password']}' WHERE user_id = 4")or die(mysql_error()); 	

		//$update = mysql_query("UPDATE  `pharm`.`pharm_users` SET  `user_password` =  'adek' WHERE  `pharm_users`.`user_id` =4")or die(mysql_error()); 	
if($update){
	header("Location: index2.php?x11");
}
}
?>
<div style="width:500px;" class="col-lg-12">
                      <form action="edit_user.php" method="POST" name="schedulefrm">
                       
                            <h4 class="modal-title" id="exampleFormModalLabel">Edit User</h4>
                       
                        <div class="modal-body">
                          <div class="row">
                           
                           <div class="col-lg-6 form-group">
                            <label>First Name</label>
								<input class="form-control" type="text" value="<?php echo $row_edituser->firstname; ?>" autocomplete="off" name="firstname" required="required" />
								<input type="hidden" value="<?php echo $row_edituser->user_id; ?>" name="edid" />
								<div class="result"></div>
							</div>
                           <div class="col-lg-6 form-group">
                            <label>Last Name</label>
								<input class="form-control" type="text" value="<?php echo $row_edituser->lastname; ?>" autocomplete="off" name="lastname" required="required" />
								<div class="result"></div>
							</div>
                           <div class="col-lg-6 form-group ">
                            <label>Username</label>
								<input class="form-control" type="text" value="<?php echo $row_edituser->user_name; ?>" autocomplete="off" name="username" required="required" />
								<div class="result"></div>
							</div>
                           <div class="col-lg-6 form-group ">
                            <label>Password</label>
								<input class="form-control" type="text" value="<?php echo $row_edituser->user_password; ?>" autocomplete="off" name="password" required="required" />
								<div class="result"></div>
							</div>
                            <div class="col-lg-6 form-group">
                            <label>Position</label>
                              <select class="form-control" name="position" id="position" required="required">
                                <option value="<?php echo $row_edituser->position; ?>"><?php echo $row_edituser->position; ?></option>
                                <option value="Director">Director</option>
                                <option value="Manager">Manager</option>
                                <option value="Dispensary">Dispensary</option>
                              </select>
                            </div>
                            <div class="col-lg-6 form-group">
                            <label>Access Level</label>
                              <select class="form-control" name="access_level" id="access_level" required="required">
                                <option value="<?php echo $row_edituser->access_level; ?>"><?php echo $row_edituser->access_level; ?></option>
                                <option value="Director">Director</option>
                                <option value="Manager">Manager</option>
                                <option value="Dispensary">Dispensary</option>
                              </select>
                            </div>
                                                                                
                            <div class="col-sm-12 pull-right">
                              <button class="btn btn-primary btn-outline" type="submit">Update</button>
                            </div>
                            
                          </div>
                        </div>
                        <input type="hidden" name="MM_update" value="schedulefrm" />
                      </form>
		</div>
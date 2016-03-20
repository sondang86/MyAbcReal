<?php
if(!defined('IN_SCRIPT')) die("");
global $db, $commonQueries, $jobseeker_profile, $locations, $categories, $job_types, $job_experience, $job_availability;
//echo "<pre>";
//print_r($jobseeker_profile);
//echo "</pre>";
?>



<div class="fright">
<?php 
    echo LinkTile("profile","edit",$EDIT_YOUR_PROFILE,"","green");
    echo LinkTile("cv","description",$EDIT_YOUR_CV,"","yellow");
?>
</div>

<div class="clear"></div>
<br/>
<h3><?php echo $M_JOB_PREFERENCES;?></h3>
<i>
	<?php echo $M_PREFERRED_JOB_CATEGORIES;?>
</i>

<?php
    if(isset($_POST['submit'])){
        
        //Locations update
        if(isset($_POST['preferred_locations'])){
            //If user records exists
            $db->withTotalCount()->get('jobseeker_locations');

            if($db->totalCount !== "0"){            
                //Delete exists records 
                $db->where('jobseeker_id', $jobseeker_profile[0]['id']);
                if($db->delete('jobseeker_locations')){
                    echo 'successfully deleted';
                } else {
                    echo "There was a problem when deleting locations";die;
                }            
            }
            
            //Then insert records
            foreach ($_POST['preferred_locations'] as $preferred_location) {
                $location_data = array(
                    'location_id'   => $preferred_location,
                    'jobseeker_id'  => $jobseeker_profile[0]['id']
                );

                $id = $db->insert ('jobseeker_locations', $location_data);
                if($id){
                    echo 'records were created. Id=' . $id;
                } else {
                    echo 'problem while creating records';die;
                }
            }   
        }
        
        
        //categories update
        if(isset($_POST['preferred_categories'])){
            //If user records exists
            $db->withTotalCount()->get('jobseeker_categories');
            if($db->totalCount !== "0"){
                //Delete exists records first
                $db->where('jobseeker_id', $jobseeker_profile[0]['id']);
                if($db->delete('jobseeker_categories')){
                    echo 'successfully deleted';
                } else { //Problems when deleting
                    echo "There was a problem when deleting categories";die;
                }            
            }
            
            //Insert again
            foreach ($_POST['preferred_categories'] as $preferred_category) {
                $category_data = array(
                    'category_id'   => $preferred_category,
                    'jobseeker_id'  => $jobseeker_profile[0]['id']
                );

                $id = $db->insert ('jobseeker_categories', $category_data);
                if($id){
                    echo 'records were created. Id=' . $id;
                } else {
                    echo 'problem while creating records';die;
                }
            }
        }        
        
    }
?>

<div class="container">
    <form action="index.php?category=profile&action=job_preferences" method="POST">
        <input type="hidden" name="proceed_save" value="1"/>
        <input type="hidden" name="category" value="<?php echo $category;?>"/>
        <input type="hidden" name="action" value="<?php echo $action;?>"/>
        <div class="row">
            <!--JOB SEEKING DESCRIPTION-->
            <span class="col-md-12 top-bottom-margin">
                Mô tả ngắn gọn những loại công việc mà bạn đang tìm kiếm:
            </span>
            <section class="col-md-12">
                <textarea name="profile_description" class="fullWidth"></textarea>
            </section>
            <!--JOB SEEKING DESCRIPTION-->
        </div>
        
        <!--JOB TYPES-->
        <div class="row top-bottom-margin">
            <span class="col-md-3 col-sm-5 col-xs-12"><h5>Loại công việc: </h5></span>
            <section class="col-md-9 col-sm-7 col-xs-12">
                <select name="job_type">
                    <?php foreach ($job_types as $job_type):?>
                    <option value="<?php echo $job_type['id']?>" <?php if($job_type['id'] == $jobseeker_profile[0]['job_type']){echo "selected";}?>><?php echo $job_type['job_name']?></option>
                    <?php endforeach;?>
                </select>
            </section>
        </div>
        </section>                
        <!--JOB TYPE-->        
        
        <!--EXPERIENCE-->
        <div class="row top-bottom-margin">
            <span class="col-md-3 col-sm-5 col-xs-12">
                <h5>Kinh nghiệm làm việc: </h5>
            </span>
            <section class="col-md-9 col-sm-7 col-xs-12">
                <select name="experience">
                    <?php foreach ($job_experience as $experience):?>
                    <option value="<?php echo $experience['experience_id']?>" <?php if($experience['experience_id'] == $jobseeker_profile[0]['experience']){echo "selected";}?>><?php echo $experience['name']?></option>
                    <?php endforeach;?>
                </select>
            </section>
        </div>                
        <!--EXPERIENCE-->   
        
        <!--AVAILABILITY-->
        <div class="row top-bottom-margin">
            <span class="col-md-3 col-sm-5 col-xs-12">
                <h5>Thời gian nhận việc: </h5>
            </span>
            <section class="col-md-9 col-sm-7 col-xs-12">
                <select name="availability">
                    <?php foreach ($job_availability as $availability):?>
                    <option value="<?php echo $availability['availability_id']?>" <?php if($availability['availability_id'] == $jobseeker_profile[0]['availability']){echo "selected";}?>><?php echo $availability['availability']?></option>
                    <?php endforeach;?>
                </select>
            </section>
        </div>                
        <!--AVAILABILITY-->        
        
        <!--LOCATIONS-->
        <div class="row top-bottom-margin">
            <span><h4>Nơi làm việc mong muốn</h4></span>
            <?php foreach ($locations as $location) :?>
            <section class="col-md-2 col-sm-3 col-xs-6 locationCheckbox">
                <label>
                    <input type="checkbox" name="preferred_locations[]" value="<?php echo $location['id']?>">
                    <p><?php echo $location['City']?></p>
                </label>
            </section>
            <?php endforeach; ?>
        </div>
        <!--LOCATIONS-->
            
        <!--CATEGORIES-->
        <div class="row top-bottom-margin">
            <h4>Ngành nghề mong muốn</h4>
            <?php foreach ($categories as $value) :?>
            <div class="col-md-2 col-sm-3 col-xs-6 locationCheckbox">
                <label>
                    <input type="checkbox" name="preferred_categories[]" value="<?php echo $value['id']?>">
                    <p><?php echo $value['category_name_vi']?></p>
                </label>
            </div>
            <?php endforeach; ?>
        </div>
        <!--CATEGORIES-->
        <div class="row">
            <input type="submit" name="submit" class="btn btn-lg btn-primary pull-right" value=" <?php echo $SAUVEGARDER;?> "/>
        </div>
    </form>    
</div>
    
<div class="content-wrapper">
    <section class="content-header">
      <h1>New Admission</h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url().$this->uri->segment(1);?>/reception/dashbord"></i>Home</a></li>
        <li class="active">New Admission</li>
      </ol>
    </section>
	<script src="<?php echo base_url();?>assets/js/custom/new_admission.js"></script>
    <!-- Main content -->
	<section class="content">
		<div class="box box-primary" style="position: relative; left: 0px; top: 0px;">		
			<div class="box-header with-border ui-sortable-handle" style="cursor: move;">
			  <h3 class="box-title">Add New Admission Entry</h3>
			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
				  <i class="fa fa-minus"></i></button>
			  </div>
			</div>
			
			<form role="form" action="javascript:void(0);" class="form-horizontal" id="student_form" method="POST">
			<div class="box-body">
                <div class="form-group">
                  <label class="col-sm-3 control-label">Fee Criteria <span style="color:red;">*</span></label>
                  <div class="col-sm-6">
					<select id="fee_criteria" name="fee_criteria" class="form-control">
						<option value="">Fee Criteria</option>
						<?php foreach($fee_criteria as $fc){?>
							<option value="<?php echo $fc['fc_id'];?>"><?php echo $fc['fc_name'];?></option>
						<?php } ?>
					</select>
				  </div>
             	</div>
             	
             	<div class="form-group" style="display: none;" id="staff_child_row">
                  <label class="col-sm-3 control-label">Staff Child <span style="color:red;">*</span></label>
                  <div class="col-sm-6">
					<select id="staff_child" name="staff_child" class="form-control">
						<option value="">Staff Child</option>
						<?php foreach($staff_child as $sc){?>
							<option value="<?php echo $sc['sc_id'];?>"><?php echo $sc['name'];?></option>
						<?php } ?>
					</select>
				  </div>
             	</div>
             	
             	<div class="form-group" style="display: none;" id="sibling_details">
                  <label class="col-sm-3 control-label">Related Admission No. <span style="color:red;">*</span></label>
                  <div class="col-sm-3">
					<input type="text" name="related_adm_no"  id="related_adm_no" class="form-control" placeholder="Enter Related Student Admission Number">
					<div id="related_adm_no_err" class="error" style="display: none;"></div>
				  </div>
				  
                  <div class="col-sm-3">
					<button class="btn btn-success" id="verify_sibling_related_adm_no">Find Students</button>
				  </div>
             	</div>
             	
             	<div class="form-group">
                  <div id="related_student_details"></div>
             	</div>
             	
             	
                <div class="form-group">
                  <label class="col-sm-3 control-label">Admission No. <span style="color:red;">*</span></label>
                  <div class="col-sm-6">
					<input type="text" name="admission_no" id="admission_no" class="form-control only_int" placeholder="Enter admission no.">
					<div id="admission_no_err" style="display:none;color:red;"></div>
				</div>
                </div>
				
				<div class="form-group">
                  <label class="col-sm-3 control-label">Roll No.</label>
                  <div class="col-sm-6">
					<input type="text" name="roll_no" id="roll_no" class="form-control only_int" placeholder="Enter roll no.">
					<div id="roll_no_err"	 style="display:none;color:red;"></div>
				  </div>
                </div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Student Name <span style="color:red;">*</span></label>
						<div class="col-sm-6">
							<input type="text" name="student_name" id="student_name" class="form-control only_text" placeholder="Enter student name">
							<div id="student_name_err" class="text-danger" style="display:none;"></div>
						</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Session <span style="color:red;">*</span></label>
						<div class="col-sm-6">
							<select class="form-control" name="session" id="session">
								<option value="">Select Session</option>
								<option value="3">2019-20</option>
							</select>
							<div id="session_err" class="text-danger" style="display:none;"></div>
						</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Board <span style="color:red;">*</span></label>
						<div class="col-sm-6">
							<select class="form-control" name="school" id="school">
								<option value="">Select Board</option>
								<?php if($this->session->userdata('school_id') == 1){?>
								<option value="1">CBSE</option>
								<option value="3">CG State Board</option>
								<?php } else if($this->session->userdata('school_id') == 2){?>
								<option value="2">CBSE</option>
								<?php }?>
							</select>
							<div id="school_err" class="text-danger" style="display:none;"></div>
						</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Medium <span style="color:red;">*</span></label>
						<div class="col-sm-6">
							<select class="form-control" name="medium" id="medium">
								<option value="">Select Medium</option>
								<?php foreach($medium as $med){?>
								    <option value="<?php echo $med['med_id'];?>"><?php echo $med['med_name'];?></option>
								<?php }?>
							</select>
							<div id="medium_err" class="text-danger" style="display:none;"></div>
						</div>
				</div>
					
				<div class="form-group">
					<label class="col-sm-3 control-label">Class <span style="color:red;">*</span></label>
					<div class="col-sm-6">
						<select class="form-control" name="class" id="class">
							<option value="">Select Class</option>
							<?php foreach($class as $classes){?>
								<option value="<?php echo $classes['c_id'];?>"><?php echo $classes['class_name'];?></option>
							<?php } ?>
						</select>
						<div id="class_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Section</label>
					<div class="col-sm-6">
					<select class="form-control" name="section" id="section">
						<option value="">Select Section</option>
						<?php foreach($section as $sec){?>
						<option value="<?php echo $sec['sec_id'];?>"><?php echo $sec['section_name'];?></option>
						<?php }?>
					</select>
					<div id="section_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group" id="fit_section" style="display: none;">
                    <label class="col-sm-3 control-label">FIT</label>
					<div class="col-sm-6">
					<select class="form-control" name="fit" id="fit">
						<option value="">Select FIT</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
					<div id="fit_err" style="display:none; color:red;"></div>
					</div>
				</div>
				
				<div class="form-group" id="elective_section" style="display: none;">
                    <label class="col-sm-3 control-label">Elective Subject</label>
					<div class="col-sm-6">
					<select class="form-control" name="elective" id="elective_subject">
						<option value="">Select Elective Subject</option>
						<?php foreach($elective as $elect){?>
						<option value="<?php echo $elect['sub_id'];?>"><?php echo $elect['sub_name'];?></option>
						<?php }?>
					</select>
					<div id="elective_subject_err" style="display:none; color:red;"></div>
					</div>
				</div>
				
				<div class="form-group" id="sub_group_section" style="display: none;">
                    <label class="col-sm-3 control-label">Subject Group</label>
					<div class="col-sm-6">
					<select class="form-control" name="subject_group" id="subject_group">
						<option value="" >Select Subject Group</option>
						<?php foreach($group as $groups){?>
						<option value="<?php echo $groups['sg_id'];?>"><?php echo $groups['sg_name'];?></option>
						<?php } ?>
					</select>
					<div id="subject_group_err" style="display:none; color:red;"></div>
					</div>
				</div>
				
				<div class="form-group">
                  <label class="col-sm-3 control-label">Father's Name <span style="color:red;">*</span></label>
					<div class="col-sm-6">
						<input type="text" name="father_name" id="father_name" class="form-control only_text" placeholder="Enter father's name">
						<div id="father_name_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
					<label class="col-sm-3 control-label">Mother's Name <span style="color:red;">*</span></label>
					<div class="col-sm-6">
						<input type="text" name="mother_name" id="mother_name" class="form-control only_text" placeholder="Enter mother's name">
						<div id="mother_name_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
					<label class="col-sm-3 control-label">Date Of Birth <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					  <input type="date"max="<?php echo date('Y-m-d')?>" class="form-control" id="dob" name="dob" placeholder="dd/mm/yyyy"> 
					<div id="dob_err" class="text-danger" style="display:none;"></div> 
					</div>
					<!-- /.input group -->
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Gender <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					<select class="form-control" id="gender" name="gender">
						<option value="">Select Gender</option>
						<option value="Male">Male</option>
						<option value="Female">Female</option>
					</select>
					 <div id="gender_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">Date of Admission <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					  <input type="date" max="<?php echo date('Y-m-d')?>" value="<?php echo date('Y-m-d');?>" class="form-control" id="admission_date" name="admission_date" placeholder="dd/mm/yyyy">
					<div id="admission_date_err" style="display:none; color:red;"></div>
					</div>
					<!-- /.input group -->
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Caste <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					<select class="form-control" name="caste" id="caste">
						<option value="">Select Caste</option>
						<option value="General">General</option>
						<option value="OBC">OBC</option>
						<option value="ST">ST</option>
						<option value="SC">SC</option>
					</select>
					<div id="caste_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Blood Group</label>
					<div class="col-sm-6">
					<select class="form-control" name="blood" id="blood">
						<option value="">Select Blood Group</option>
						<option value="A+">A+</option>
						<option value="A-">A-</option>
						<option value="B+">B+</option>
						<option value="B-">B-</option>
						<option value="O+">O+</option>
						<option value="O-">O-</option>
						<option value="AB+">AB+</option>
						<option value="AB-">AB-</option>
					</select>
					<div id="blood_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                  <label class="col-sm-3 control-label">Aadhaar Card Number</label>
					<div class="col-sm-6">
						<input type="text" name="aadhaar" id="aadhaar" class="form-control only_int" placeholder="Enter aadhaar card no." maxlength="12">
						<div id="aadhaar_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Permanent Address <span style="color:red;">*</span></label>
					<div class="col-sm-6">
						<textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter permanent address"></textarea>
						<div id="address_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Guardian Name</label>
					<div class="col-sm-6">
						<input type="text" name="guardian" id="guardian" class="form-control only_text" placeholder="Enter guardian name">
						<div id="guardian_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Local Address </label>
					<div class="col-sm-6">
						<textarea name="local_address" id="local_address" class="form-control" rows="3" placeholder="Enter local address"></textarea>
						<div id="local_address_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Contact No. <span style="color:red;">*</span></label>
					<div class="col-sm-6">
						<input type="text" name="contact_no" id="contact_no" class="form-control only_int" placeholder="Enter contact no.">
						<div id="contact_no_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Email ID</label>
					<div class="col-sm-6">
						<input type="text" name="email" id="email" class="form-control" placeholder="Enter email id">
						<div id="email_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Medical History</label>
					<div class="col-sm-6">
						<input type="text" name="medical" id="medical" class="form-control" placeholder="Enter medical history">
						<div id="medical_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Height </label>
					<div class="col-sm-6">
						<input type="text" name="height" id="height" class="form-control" placeholder="Enter height in Inches">
						<div id="height_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                  <label class="col-sm-3 control-label">Weight</label>
					<div class="col-sm-6">
						<input type="text" name="weight" id="weight" class="form-control" placeholder="Enter weight in KG">
						<div id="weight_err" class="text-danger" style="display:none;"></div>
					</div>
                </div>
                
				<div class="form-group">
                    <label class="col-sm-3 control-label">TC Self <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					<select class="form-control" name="transfer" id="transfer">
						<option value="">Select TC Self</option>
						<option value="Bonafide">Bonafide</option>
						<option value="Fresher">Fresher</option>
					</select>
					<div id="transfer_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Hosteller <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					<select class="form-control" name="hostler" id="hostler">
						<option value="">Select Hosteller</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
					<div id="hostler_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">House</label>
					<div class="col-sm-6">
					<select class="form-control" name="hostel" id="hostel">
						<option value="">Select House</option>
						<?php foreach($hostel as $hostels){?>
							<option value="<?php echo $hostels['hid'];?>"><?php echo $hostels['hostel_name'];?></option>
						<?php }?>
					</select>
					<div id="house_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Bus <span style="color:red;">*</span></label>
					<div class="col-sm-6">
					<select class="form-control" name="bus" id="bus">
						<option value="">Select Bus</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
					</select>
					<div id="bus_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                    <label class="col-sm-3 control-label">Bus Stoppage</label>
					<div class="col-sm-6">
					<select class="form-control" name="bus_stoppage" id="bus_stoppage">
						<option value="">Select Bus Stoppage</option>
						<?php foreach($bus as $bus){?>
							<option value="<?php echo $bus['bs_id'];?>"><?php echo $bus['bus_stoppage'];?></option>
						<?php }?>
					</select>
					<div id="house_err" class="text-danger" style="display:none;"></div>
					</div>
				</div>
				
				<div class="form-group">
                  <label class="col-sm-3 control-label" for="exampleInputFile">Profile Image</label>
                  <div class="col-sm-6"><input type="file" name="std_image" id="std_image"></div>
                  <div id="std_image_err"></div>
                </div>
				<div class="form-group">
                      	<div class="col-sm-offset-3 col-sm-6">
                            <button type="button" id="submit" class="btn btn-success btn-space">Submit</button>
                        </div>
        			</div>
			</div>
					
			</form>
		</div>
        </section>
</div>

<input type="hidden" id="user_url" name="user_url" value="<?php echo $this->uri->segment(1).'/'.$this->uri->segment(2);?>"/>
<!-- Modal -->
<div id="sibling_verification" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">

        <div class="row">
        	<div class="form-group">
              <div class="col-sm-3">
				<select class="form-control" id="sibling_school">
					<option value="">Select BOARD</option>
					<option value="1">CBSE</option>
					<option value="2">CG Board</option>
				</select>
				<div id="sibling_school_err" class="error" style="display: none;"></div>
			</div>

              <div class="col-sm-3">
    			<select class="form-control" id="sibling_medium"> 
    				<option value="">Select Medium</option>
    				<?php foreach($medium as $med){?>
    				    <option value="<?php echo $med['med_id'];?>"><?php echo $med['med_name'];?></option>
    				<?php }?>
    			</select>
    			<div id="sibling_medium_err" class="error" style="display: none;"></div>
    		</div>

    		<div class="col-sm-3">
    			<select class="form-control" id="sibling_class">
    				<option value="">Select Class</option>
    				<?php foreach($class as $classes){?>
						<option value="<?php echo $classes['c_id'];?>"><?php echo $classes['class_name'];?></option>
					<?php } ?>
    			</select>
    			<div id="sibling_class_err" class="error" style="display: none;"></div>
    		</div>

    		<div class="col-sm-3">
    			<select class="form-control" id="sibling_section">
    				<option value="">Select Section</option>
    				<?php foreach($section as $sec){?>
						<option value="<?php echo $sec['sec_id'];?>"><?php echo $sec['section_name'];?></option>
					<?php }?>
    			</select>
    			<div id="sibling_section_err" class="error" style="display: none;"></div>
    		</div>

    		<div class="col-sm-3">
    			<button id="search_record" class="btn btn-sm btn-primary">Search</button>
    		</div>
      	 </div>

      	 <div style="width:100%; height: 400px;overflow-y: scroll;float: left;">
      	 	<table class="table" >
      	 		<thead>
      	 			<tr>
      	 				<th>S No.</th>
      	 				<th>Admission No.</th>
      	 				<th>Student Name</th>
      	 				<th>Father's Name</th>
      	 				<th>Contact No.</th>
      	 			</tr>
      	 		</thead>
      	 		<tbody id="student_list"></tbody>
      	 	</table>
      	 </div>
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
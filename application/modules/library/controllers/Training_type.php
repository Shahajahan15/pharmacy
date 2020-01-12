<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * company controller
 */
 
class Training_type extends Admin_Controller
{
	/**
	 * Constructor
	 * @return void
	 */
	 
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('training_type_model', NULL, true);
        $this->lang->load('training');	
		$this->lang->load('common');
		
		//Assets::add_module_js('library', 'training_info.js');		
		//Template::set_block('sub_nav', 'training_info/_sub_nav_traininginfo');
	}

  

    /**
     * company create
     */
	 
    public function training_type_create()
    {       		
		Template::set('toolbar_title', lang("Library_training_type_create"));
        Template::set_view('training_info/training_type_create');
        Template::render();
    }
	

	
	
	//====== Ajax Function of Employee Training ====//	
	public function trainingTypeAjax()
	{		
		$this->auth->restrict('Lib.Training.Type.Create');
		//$this->load->model('training_type_model', NULL, true);	
		
		$data = array();
		$data['TRAINING_TYPE_NAME']       		= $this->input->post('training_type_name');
		$data['TRAINING_TYPE_NAME_BENGALI']     = $this->input->post('training_type_name_bengali');
		$data['TYPE_DESCRIPTION']       		= $this->input->post('description');
		$data['TYPE_DESCRIPTION_BENGALI']       = $this->input->post('type_description_bengali');
		$data['TRAINING_TYPE']       			= $this->input->post('library_training_type_type');
		$data['TYPE_STATUS']      		    	= $this->input->post('status');
		
		$trainingTypeId      		= $this->input->post('trainingTypeId');		
				
		if($this->input->post('training_type_name')!='')
		{				
			if($trainingTypeId > 0)
			{
				$data['MODIFY_BY']		= $this->current_user->id;
				$data['MODIFY_DATE']	= date('Y-m-d H:i:s');					
				$this->training_type_model->update($trainingTypeId,$data);
				
			}else
			{	$data['CREATED_BY']		= $this->current_user->id;	
				$this->training_type_model->insert($data);	
			}			
		}	
																			
		echo    "<tr class='active strong'>
					<td colspan='7' align='center'>".lang("library_training_type_view")."</td>
				</tr>
				
				<tr>						
					<th>".lang("library_training_type")."</th>
					<th>".lang("library_training_type")."  " .lang("bengali")."</th>
					<th>".lang("library_training_type_description")."</th>
					<th>".lang("library_training_type_type")."</th>	
					<th>".lang("library_training_status")."</th>						
					<th>".lang("bf_actions")."</th>
				</tr>";		
					

		$records	= $this->training_type_model->find_all_by('IS_DELETED',0);
		
		foreach($records as $recorded): $trainingRecord = (object) $recorded;														
			$trainingTypeId=$trainingRecord->TRAINING_TYPE_ID;			
			
			if($trainingRecord->TYPE_STATUS == 1)
			{
				$status = "Active";
			}else
			{
				$status = "Inactive";
				
			}
			
			if($trainingRecord->TRAINING_TYPE == 1)
			{
				$trainingType = "Local Internal";
				
			}else if($trainingRecord->TRAINING_TYPE == 2)
			{
				$trainingType = "Local External";
				
			}else
			{
				$trainingType = "Foreign";
			}
				
			
		
			echo "			
				<tr>
					<td>".$trainingRecord->TRAINING_TYPE_NAME."</td>
					<td>".$trainingRecord->TRAINING_TYPE_NAME_BENGALI."</td>
					<td>".$trainingRecord->TYPE_DESCRIPTION."</td>	
					<td>".$trainingType."</td>	
					<td>".$status."</td>											
					<td>
						<span onclick='deleteTrainingType($trainingTypeId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>
						<span onclick='editTrainingType($trainingTypeId)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>		
					</td>
				</tr>";				
		endforeach;																		
	}
			

		//=====show for edit experience
	public function getTrainingType()
	{		
		if($typeId      = (int)$this->input->post('typeId'));
			
		{
			$trainingRecord 	 = $this->training_type_model->find($typeId);	
			
			$TRAINING_TYPE_NAME 			= $trainingRecord->TRAINING_TYPE_NAME;
			$TRAINING_TYPE_NAME_BENGALI 	= $trainingRecord->TRAINING_TYPE_NAME_BENGALI;
			$TYPE_DESCRIPTION 	   	 		= $trainingRecord->TYPE_DESCRIPTION;
			$TYPE_DESCRIPTION_BENGALI 	    = $trainingRecord->TYPE_DESCRIPTION_BENGALI;
			$TRAINING_TYPE     				= $trainingRecord->TRAINING_TYPE;
			$TYPE_STATUS 	     			= $trainingRecord->TYPE_STATUS;
			
		
			
		    echo "
				$('#library_training_type').val('$TRAINING_TYPE_NAME');
				$('#training_type_name_bengali').val('$TRAINING_TYPE_NAME_BENGALI');
				$('#library_training_type_description').val('$TYPE_DESCRIPTION');
				$('#type_description_bengali').val('$TYPE_DESCRIPTION_BENGALI');
				$('#library_training_type_type').val('$TRAINING_TYPE');
				$('#library_training_status').val('$TYPE_STATUS');
				$('#TRAINING_TYPE_ID').val('$typeId');
			";		
		}
				
	}
	
	
    //Delete TRAINING Data
	public function deleteTrainingType()
	{			
		//$this->load->model('training_type_model', NULL, TRUE);	
		$type_id 	= $this->input->post('type_id');
			//echo $type_id ; die;
		
		if($type_id)
		{	
			$data = array();
			$data['IS_DELETED'] 		= 1; 
			$data['DELETED_BY']			= $this->current_user->id;	
			$data['DELETED_DATE']    	= date('Y-m-d H:i:s');
			
			
			$this->training_type_model->update($type_id,$data);	
			
																	
		echo   "<tr class='active strong'>
					<td colspan='7' align='center'>".lang("library_training_type_view")."</td>
				</tr>
				
				<tr>						
					<th>".lang("library_training_type")."</th>
					<th>".lang("library_training_type")."  " .lang("bengali")."</th>
					<th>".lang("library_training_type_description")."</th>
					<th>".lang("library_training_type_type")."</th>	
					<th>".lang("library_training_status")."</th>								
					<th>".lang("bf_actions")."</th>
				</tr>";		
					

		$records	= $this->training_type_model->find_all_by('IS_DELETED',0);
		
		foreach($records as $recorded): $trainingRecord = (object) $recorded;														
															
			$trainingTypeId=$trainingRecord->TRAINING_TYPE_ID;			
			
			if($trainingRecord->TYPE_STATUS == 1)
			{
				$status = "Active";
			}else
			{
				$status = "Inactive";
				
			}
			
			
			if($trainingRecord->TRAINING_TYPE == 1)
			{
				$trainingType = "Local Internal";
				
			}else if($trainingRecord->TRAINING_TYPE == 2)
			{
				$trainingType = "Local External";
				
			}else
			{
				$trainingType = "Foreign";
			}
				
				
		
			echo "			
				<tr>
					<td>".$trainingRecord->TRAINING_TYPE_NAME."</td>
					<td>".$trainingRecord->TRAINING_TYPE_NAME_BENGALI."</td>
					<td>".$trainingRecord->TYPE_DESCRIPTION."</td>	
					<td>".$trainingType."</td>	
					<td>".$status."</td>											
					<td>
						<span onclick='deleteTrainingType($trainingTypeId)' class='btn btn-small btn-danger glyphicon glyphicon-trash'> </span>
						<span onclick='editTrainingType($trainingTypeId)' class='btn btn-small btn-primary glyphicon glyphicon-edit'> </span>		
					</td>
				</tr>";				
		endforeach;
						
		}
			
	}	
	
	
	public function checkTypeNameAjax()
	{				
		$trainingType	= $this->input->post('trainingType'); 
		
		if(trim($trainingType)!= '')
		{		
			$res =$this->db->query("SELECT TRAINING_TYPE_NAME FROM  bf_lib_training_type WHERE  TRAINING_TYPE_NAME  LIKE '%$trainingType%' AND IS_DELETED = 0");	
			
			$result = $res->num_rows();
			
			if($result > 0 )
			{
				echo 'The name "'.$trainingType.'" is already exist !!';	
				
			}else
			{
				echo "<span style='color:green !important;'>The name is '". $trainingType ."' Available. </span>";
			}								
		}	
	}

	
}


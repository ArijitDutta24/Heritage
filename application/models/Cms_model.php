<?php
class Cms_model extends Core {

	public function __construct()
    {
        parent::__construct('cms');
    }

	public function getlist()
	{
		$data=array();
		$this->db->select('*');
		$this->db->from('cms');
		$q=$this->db->get();
		if($q->num_rows()>0){
			foreach($q->result_array() as $result){
				$data[]=$result;
			}
		}
		
		return $data;
	}

	public function insert_cms($data)
	{
		
		$insert=$this->db->insert('cms',$data);
		
		
		
		if($insert)
		{
			
			$this->utility->setMsg('Data added successfully.','SUCCESS');
			$return=1;
		}
		else
		{
			$this->utility->setMsg('Failed to insert data. Please try again.','ERROR');
			$return=0;
		}
		return $return;
	}

	public function getcms($id)
	{
		$data=array();
		$this->db->select('*');
		$this->db->from('cms');
		$this->db->where('cms_id',$id);
		$q=$this->db->get();
		if($q->num_rows()>0){
			return $q->row_array();
			/* foreach($q->row_array() as $result){
				$data[]=$result;
			} */
		}
		
		//return $data;
	}
public function getcmsBySlug($slug_id)
	{
		$data=array();
		$this->db->select('*');
		$this->db->from('cms');
		$this->db->where('cms_slug',$slug_id);
		$q=$this->db->get();
		if($q->num_rows()>0){
			foreach($q->result_array() as $result){
				$data[]=$result;
			}
		}
		
		return $data;
	}

	public function getcmsname($cmsname,$id)
	{
		$this->db->where('cms_title',$cmsname);
		$this->db->where('cms_id!=',$id);
	    $cmsname_existance = $this->db->get('cms');
	    if($cmsname_existance->num_rows()>0){
	        
	        return $cmsname_existance;
	        
	    }
	}
	public function update_cms($data,$id)
	{
		
		$this->db->where('cms_id',$id);
		$update=$this->db->update('cms',$data);
		if($update)
		{
			
			$this->utility->setMsg('Data updated successfully.','SUCCESS');
			$return=1;
		}
		else
		{
			$this->utility->setMsg('Failed to update data. Please try again.','ERROR');
			$return=0;
		}
		return $return;
	}

	public function delete_cms($id)
	{

		$this->db->where('cms_id',$id);
		$delete=$this->db->delete('cms');
		


		if($delete)
		{
			
			$this->utility->setMsg('Data deleted successfully.','SUCCESS');
			$return=1;
		}
		else
		{
			$this->utility->setMsg('Failed to delete data. Please try again.','ERROR');
			$return=0;
		}
		return $return;
	}


	public function update_status()
	{
		if($this->input->post('status')==0)
		{
			$updated_status='1';
		}
		if($this->input->post('status')==1)
		{
			$updated_status='0';
		}
		$update_status=array('cms_status' => $updated_status);
		$this->db->where('cms_id',$this->input->post('edit_id'));
		$update=$this->db->update('cms',$update_status);
		if($update)
		{
			$this->utility->setMsg('Status updated successfully.','SUCCESS');
			$return=1;
		}
		else
		{
			$this->utility->setMsg('Failed to update status. Please try again.','ERROR');
			$return=0;
		}
		return $return;
	}


	public function about_fetchRecord()
	{
			$this->db->select('*');
			$this->db->from('cms');
			$resu=$this->db->get();
			return $resu->result_array();
	}

	public function dealFetch($params = array())
	{
			$this->db->select('*');
			$this->db->from('tblprod_category');
			$this->db->join('tblproducts', 'tblproducts.id = tblprod_category.product');
			$this->db->join('tblpictures', 'tblpictures.product_id = tblprod_category.product');
			$this->db->where($params['where']);
			if(array_key_exists("start",$params) && array_key_exists("limit",$params)){
            $this->db->limit($params['limit'],$params['start']);
	        }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){
	            $this->db->limit($params['limit']);
	        }
			$this->db->group_by('tblprod_category.product');
			$this->db->order_by('tblprod_category.id', 'DESC');
			$resu=$this->db->get();
			return $resu->result_array();
	}


	public function imgFetch()
	{
			$this->db->select('*');
			$this->db->from('tblprod_category');
			$this->db->join('tblpictures', 'tblpictures.product_id = tblprod_category.product');
			$this->db->where('category', 23);
			$resu=$this->db->get();
			return $resu->result_array();
	}


	public function sidebarFetch()
	{
			$this->db->select('*');
			$this->db->from('tblprod_category');
			
			$this->db->order_by('tblprod_category.id', 'RANDOM');
			$this->db->join('tblproducts', 'tblproducts.id = tblprod_category.product');
			$this->db->join('tblpictures', 'tblpictures.product_id = tblprod_category.product');
			$this->db->where('category', 23);
			
			$resu=$this->db->get();
			return $resu->result_array();
	}
}
<?php
namespace App\Models;
use CodeIgniter\Model;
class Image extends Model {
	protected $table = 'images';
	protected $primaryKey = 'id';
	protected $allowedFields = ['title','file_name','width','height','mimetype','extension','watermark','seo_alt','seo_description','added_by'];
	
	function get_random_name() {
		$i = 0;
		$str = '';
	    while($i<10) {
	        $str.=chr((rand()%26)+97);
	        $i++;
	    }
		$str = $str.substr(uniqid (""),0,22);
		return $str;		
	}
	
	function check_random_name($ext,$filename = null){
		if(empty($filename)) $filename = $this->get_random_name();
		$builder = $this->db->table('images');
		$builder->where('file_name', $filename.'.'.$ext);
		$query = $builder->get();
		$row = $query->getRowArray();
		if(!empty($row['id'])) return false;
		return $filename;
	}
	
	function get_by_filename($filename) {
		
		$builder = $this->db->table('images');
		$builder->where('file_name', $filename);
		$query = $builder->get();
		$row = $query->getRowArray();
		if(empty($row)) return false;
		return $row;
	}

	
	function fillParams($builder, $params = null)
    {
        if (is_array($params) && count($params)) {

            if (isset($params['term']) && !empty($params['term'])) {
                $builder->groupStart()
                    ->like('title', $params['term'])
                    ->orLike('file_name', $params['term'])
                    ->groupEnd();
            }
        }
        return $builder;
    }

    function getCount($params = null)
    {
        $builder = $this->builder('images')->select('count(*) as total_count');
        $builder = $this->fillParams($builder, $params);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['total_count'];
    }

    function getPaginatedList($page = null, $params = array())
    {
        if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'id';
        if (empty($params['sortingType'])) $params['sortingType'] = 'desc';

        $builder = $this->builder('images')->select('images.*, users.username as user_added');
        $builder = $this->fillParams($builder, $params);
        if (!empty($page)) $builder->limit(PAGE_LENGTH, ($page - 1) * PAGE_LENGTH);
		$sort = $params['sortingColumn'].' '.$params['sortingType'];
		//log_message('error',$sort);
		$builder->orderBy($sort);
		$builder->join('users','users.id = images.added_by');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
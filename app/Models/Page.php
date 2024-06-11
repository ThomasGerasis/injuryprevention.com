<?php

namespace App\Models;

use CodeIgniter\Model;

class Page extends Model
{
	protected $table = 'pages';
	protected $primaryKey = 'id';
	protected $allowedFields = ['title', 'seo_title', 'seo_description', 'permalink','opener_image_id', 'social_image_id', 'social_title', 'content', 'date_published','published', 'date_created', 'user_id', 'faq_title', 'faq_subtitle', 'faq_heading', 'faq_content', 'modified_by', 'modified_date'];

	private ActionLog $actionLogModel;
	
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }

	function fillParams($builder, $params = null)
    {
        if (is_array($params) && count($params)) {

            if (isset($params['published']) && $params['published'] != 'all') {
                $builder->where('published', $params['published']);
           	}
			
            if (isset($params['term']) && !empty($params['term'])) {
                $builder->groupStart()
                    ->like('title', $params['term'])
                    ->orLike('seo_title', $params['term'])
                    ->groupEnd();
            }
        }
        return $builder;
    }

    function getCount($params = null)
    {
        $builder = $this->builder('pages')->select('count(*) as total_count');
        $builder = $this->fillParams($builder, $params);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['total_count'];
    }

    function getPaginatedList($page = null, $params = array())
    {
        if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'modified_date';
        if (empty($params['sortingType'])) $params['sortingType'] = 'desc';

        $builder = $this->builder('pages')->select('pages.*, users.username as user_modified');
        $builder = $this->fillParams($builder, $params);
        if (!empty($page)) $builder->limit(PAGE_LENGTH, ($page - 1) * PAGE_LENGTH);
		$sort = $params['sortingColumn'].' '.$params['sortingType'];
		$builder->orderBy($sort);
		$builder->join('users','users.id = pages.modified_by');
        $query = $builder->get();
        return $query->getResultArray();
    }

	function publish($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(!empty($pageRow['published'])){
			return array('published'=>false,'message'=>'The page is already published.');
		}
		$this->db->transBegin();
		$this->update($id, array('published'=>'1','date_published'=>date('Y-m-d H:i:s')));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('published'=>false,'message'=>'The page was not published.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Page',
			'what' => 'Publish page'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('published'=>true,'message'=>'The page was published.');
	}

	function unpublish($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(empty($pageRow['published'])){
			return array('unpublished'=>false,'message'=>'The page is already unpublished.');
		}
		$this->db->transBegin();
		$this->update($id, array('published'=>'0','date_published'=>NULL));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('unpublished'=>false,'message'=>'The page was not unpublished.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Page',
			'what' => 'Unpublish page'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('unpublished'=>true,'message'=>'The page was unpublished.');
	}

	function deleteRow($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(!empty($pageRow['published'])){
			return array('deleted'=>false,'message'=>'The page is published and cannot be deleted.');
		}
		$this->db->transBegin();
		$this->delete($id);
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('deleted'=>false,'message'=>'The page was not deleted.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Page',
			'what' => 'Delete page'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('deleted'=>true,'message'=>'The page was deleted.');
	}

	function saveData($post_data, $user_id, $id = null)
	{
		$this->db->transBegin();

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_name' => 'Page'
		);

		$data = array(
			'title' => $post_data['title'],
			'seo_title' => $post_data['seo_title'],
			'seo_description' => $post_data['seo_description'],
			'permalink' => $post_data['permalink'],
			'opener_image_id' => (empty($post_data['opener_image_id']) ? NULL : $post_data['opener_image_id']),
			'social_image_id' => (empty($post_data['social_image_id']) ? NULL : $post_data['social_image_id']),
			'social_title' => $post_data['social_title'],
			'content' => fixPostContent(str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['content']),$user_id),
			'faq_title' => $post_data['faq_title'],
			'faq_subtitle' => $post_data['faq_subtitle'],
			'faq_heading' => $post_data['faq_heading'],
			'faq_content' => str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['faq_content'])
		);
		
		$data['modified_by'] = $user_id;
		$updateCache = false;
		$update_permalink_data = false;
		if (empty($id)) {
			$data['modified_date'] = date('Y-m-d H:i:s');
			$data['date_created'] = date('Y-m-d H:i:s');
			/*if(!empty($data['published'])){
				$data['date_published'] = date('Y-m-d H:i:s');
				$updateCache = true;
			}*/
			$id = $this->insert($data);
			$update_permalink_data = true;
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Created page';			
		} else {
			$original_data = $this->find($id);
			$this->update($id, $data);
			//if($original_data['published'] != $data['published'] || !empty($data['published'])) $updateCache = true;
			if($original_data['published']) $updateCache = true;
			if($original_data['permalink'] != $data['permalink']){
				$update_permalink_data = true;
			}
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Update page';
		}
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}
		
		if($update_permalink_data){
			$permalink_query = $this->builder('permalinks')
				->where('type','page')
				->where('type_id',$id)
				->get();
			$permalink_data = $permalink_query->getRowArray();
			if(empty($permalink_data['id'])){
				$this->db->table('permalinks')->insert(array(
					'type'=>'page',
					'type_id'=>$id,
					'permalink'=>$data['permalink'],
					'published'=>(empty($original_data['published']) ? 0 : 1)
				));
			}else{
				$this->db->table('permalinks')->where('id', $permalink_data['id'])->update(array('permalink'=>$data['permalink']));
			}
		}

		
		$savedFaqs = array();
		$pageFaqModel = model(PageFaq::class);
		if(!empty($post_data['faq'])){
			foreach($post_data['faq'] as $faq){
				$new_data = array(
					'page_id' => $id,
					'question' => $faq['question'],
					'answer' => str_replace(array(FRONT_SITE_URL,base_url()),'/',$faq['answer']),
					'order_num' => $faq['order_num']
				);
				if (!empty($faq['id'])) {
					$pageFaqModel->update($faq['id'], $new_data);
				} else {
					$faq['id'] = $pageFaqModel->insert($new_data);
				}
				$savedFaqs[] = $faq['id'];
			}
		}

		$faqsToDelete = $this->db->table('page_faqs')->where('page_id',$id);
		if (count($savedFaqs)) {
			$faqsToDelete = $faqsToDelete->whereNotIn('id', $savedFaqs);
		}
		$faqsToDelete = $faqsToDelete->delete();

		$this->actionLogModel->insert($actionData);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();
		return array('id' => $id, 'updateCache' => $updateCache, 'update_permalink_data' => ($updateCache && $update_permalink_data));
	}
}

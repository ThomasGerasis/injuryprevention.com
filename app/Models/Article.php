<?php

namespace App\Models;

use CodeIgniter\Model;

class Article extends Model
{
	protected $table = 'articles';
	protected $primaryKey = 'id';
	protected $allowedFields = ['article_category_id', 'title', 'short_title', 'seo_title', 'seo_description', 'permalink', 'opener_image_id', 'social_image_id', 'social_title', 'content', 'date_scheduled', 'date_published','published','is_locked', 'date_created', 'user_id', 'modified_by', 'modified_date'];

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
			
			if (isset($params['article_category_id']) && $params['article_category_id'] != 'all') {
                $builder->where('article_category_id', $params['article_category_id']);
           	}
            if (isset($params['term']) && !empty($params['term'])) {
				$builder->groupStart()
                    ->like('title', $params['term'])
                    ->orLike('seo_title', $params['term'])
                    ->orWhere('articles.id', $params['term'])
                    ->groupEnd();
            }
        }
        return $builder;
    }

    function getCount($params = null)
    {
        $builder = $this->builder('articles')->select('count(*) as total_count');
        $builder = $this->fillParams($builder, $params);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['total_count'];
    }

    function getPaginatedList($page = null, $params = array())
    {
        if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'modified_date';
        if (empty($params['sortingType'])) $params['sortingType'] = 'desc';

        $builder = $this->builder('articles')->select('articles.*, users.username as user_modified');
		$builder = $this->fillParams($builder, $params);
        if (!empty($page)) $builder->limit(PAGE_LENGTH, ($page - 1) * PAGE_LENGTH);
		$sort = $params['sortingColumn'].' '.$params['sortingType'];
		$builder->orderBy($sort);
		$builder->join('users','users.id = articles.modified_by');
        $query = $builder->get();
        return $query->getResultArray();
    }

	function cronPublish() {
		$updateIds = array();
		$builder = $this->builder('articles')
			->select('id')
        	->where('published', '0')
			->where('date_scheduled <= ', date('Y-m-d H:i:s'))
        	->orderBy('date_scheduled DESC');
		$articles = $builder->get()->getResultArray();
		foreach($articles as $article){
			$this->update($article['id'],array('published' => '1'));
			$updateIds[$article['id']] = $article['id'];
		}
		return $updateIds;
	}

	function publish($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(!empty($pageRow['published'])){
			return array('published'=>false,'message'=>'The article is already published.');
		}
		$this->db->transBegin();
		$this->update($id, array('published'=>'1','date_published'=>date('Y-m-d H:i:s')));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('published'=>false,'message'=>'The article was not published.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Article',
			'what' => 'Publish article'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('published'=>true,'message'=>'The article was published.');
	}

	function unpublish($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(empty($pageRow['published'])){
			return array('unpublished'=>false,'message'=>'The article is already unpublished.');
		}
		$this->db->transBegin();
		$this->update($id, array('published'=>'0','date_published'=>NULL));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('unpublished'=>false,'message'=>'The article was not unpublished.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Article',
			'what' => 'Unpublish article'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('unpublished'=>true,'message'=>'The article was unpublished.');
	}

	function schedule($id,$user_id, $date_scheduled) {
		$this->db->transBegin();
		
		$this->update($id, array('date_scheduled'=>$date_scheduled));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('scheduled'=>false,'message'=>'The article was not scheduled.');
		}

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Article',
			'what' => 'Schedule article'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('scheduled'=>true,'message'=>'The article was scheduled.');
	}
	
	function unschedule($id,$user_id) {
		$this->db->transBegin();
		
		$this->update($id, array('date_scheduled'=>NULL));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('unscheduled'=>false,'message'=>'The article was not unscheduled.');
		}

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Article',
			'what' => 'Unschedule article'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('unscheduled'=>true,'message'=>'The article was unscheduled.');
	}


	function deleteRow($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(!empty($pageRow['published'])){
			return array('deleted'=>false,'message'=>'The article is published and cannot be deleted.');
		}
		$this->db->transBegin();
		$this->delete($id);
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('deleted'=>false,'message'=>'The article was not deleted.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'Article',
			'what' => 'Delete article'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('deleted'=>true,'message'=>'The article was deleted.');
	}
	
	function saveData($post_data, $user_id, $id = null)
	{
		$this->db->transBegin();

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_name' => 'Article'
		);

		$data = array(
			'article_category_id' => $post_data['article_category_id'],
			'title' => $post_data['title'],
			'short_title' => str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['short_title']),
			'seo_title' => $post_data['seo_title'],
			'seo_description' => $post_data['seo_description'],
			'permalink' => $post_data['permalink'],
			'opener_image_id' => (empty($post_data['opener_image_id']) ? NULL : $post_data['opener_image_id']),
			'social_image_id' => (empty($post_data['social_image_id']) ? NULL : $post_data['social_image_id']),
			'social_title' => $post_data['social_title'],
			'is_locked' => (empty($post_data['is_locked']) ? '0' : '1'),
			'content' => fixPostContent(str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['content']),$user_id)
		);
		
		if(!empty($post_data['schedule_date']) && !empty($post_data['schedule_time'])){
			$data['date_scheduled'] = $post_data['schedule_date'].' '.$post_data['schedule_time'];
		}

		$data['modified_by'] = $user_id;
		$updateCache = false;
		$updateCategories = array();
		$update_permalink_data = false;
		if (empty($id)) {
			$data['modified_date'] = date('Y-m-d H:i:s');
			$data['date_created'] = date('Y-m-d H:i:s');
			$data['user_id'] = $user_id;
			$id = $this->insert($data);
			$update_permalink_data = true;
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Created article';			
		} else {
			$original_data = $this->find($id);
			$this->update($id, $data);

            $updateCache = true;
			if($original_data['published']){
				if($original_data['article_category_id'] != $data['article_category_id']){
					if(!empty($original_data['article_category_id'])) $updateCategories[$original_data['article_category_id']] = $original_data['article_category_id'];
					if(!empty($data['article_category_id'])) $updateCategories[$data['article_category_id']] = $data['article_category_id'];
				}
			}

			if($original_data['permalink'] != $data['permalink']){
				$update_permalink_data = true;
			}
			
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Update article';
		}
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}
		
		if($update_permalink_data){
			$permalink_query = $this->builder('permalinks')
				->where('type','article')
				->where('type_id',$id)
				->get();
			$permalink_data = $permalink_query->getRowArray();
			if(empty($permalink_data['id'])){
				$this->db->table('permalinks')->insert(array(
					'type'=>'article',
					'type_id'=>$id,
					'permalink'=>$data['permalink'],
					'published'=>(empty($original_data['published']) ? 0 : 1)
				));
			}else{
				$this->db->table('permalinks')->where('id', $permalink_data['id'])->update(array('permalink'=>$data['permalink']));
			}
		}

		$this->actionLogModel->insert($actionData);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();
		return array('id' => $id, 'updateCache' => $updateCache, 'updateCategories' => $updateCategories, 'update_permalink_data' => ($updateCache && $update_permalink_data));
	}

	function tokeniputSearch($params = array()){

		if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'modified_date';
        if (empty($params['sortingType'])) $params['sortingType'] = 'desc';

        $builder = $this->builder('articles')->select('articles.id, articles.title');
		$builder = $this->fillParams($builder, $params);
       	$sort = $params['sortingColumn'].' '.$params['sortingType'];
		$builder->orderBy($sort);
		$query = $builder->get();
		$response = array();
		foreach ($query->getResultArray() as $row) {
			$response[] = array(
				'id'=>$row['id'],
				'name'=>htmlspecialchars($row['title']),
			);
		}
		return $response;
	}
}

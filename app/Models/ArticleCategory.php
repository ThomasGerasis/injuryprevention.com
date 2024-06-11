<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticleCategory extends Model
{
	protected $table = 'article_categories';
	protected $primaryKey = 'id';
	protected $allowedFields = ['title', 'seo_title', 'seo_description', 'permalink', 'opener_image_id', 'social_image_id', 'social_title', 'show_article_grid', 'parent_id', 'content', 'date_published','published', 'date_created', 'user_id', 'modified_by', 'modified_date'];

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
			
			if (isset($params['parent_id']) && $params['parent_id'] != 'all') {
                $builder->where('parent_id', $params['parent_id']);
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
        $builder = $this->builder('article_categories')->select('count(*) as total_count');
        $builder = $this->fillParams($builder, $params);
        $query = $builder->get();
        $row = $query->getRowArray();
        return $row['total_count'];
    }

    function getPaginatedList($page = null, $params = array())
    {
        if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'modified_date';
        if (empty($params['sortingType'])) $params['sortingType'] = 'desc';

        $builder = $this->builder('article_categories')->select('article_categories.*, users.username as user_modified');
        $builder = $this->fillParams($builder, $params);
        if (!empty($page)) $builder->limit(PAGE_LENGTH, ($page - 1) * PAGE_LENGTH);
		$sort = $params['sortingColumn'].' '.$params['sortingType'];
		$builder->orderBy($sort);
		$builder->join('users','users.id = article_categories.modified_by');
        $query = $builder->get();
        return $query->getResultArray();
    }

	function publish($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(!empty($pageRow['published'])){
			return array('published'=>false,'message'=>'The article category is already published.');
		}
		$this->db->transBegin();
		$this->update($id, array('published'=>'1','date_published'=>date('Y-m-d H:i:s')));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('published'=>false,'message'=>'The article category was not published.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'ArticleCategory',
			'what' => 'Publish article category'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('published'=>true,'message'=>'The article category was published.');
	}

	function unpublish($id, $user_id)
	{
		$pageRow = $this->find($id);
		if(empty($pageRow['published'])){
			return array('unpublished'=>false,'message'=>'The article category is already unpublished.');
		}
		$this->db->transBegin();
		$this->update($id, array('published'=>'0','date_published'=>NULL));
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return array('unpublished'=>false,'message'=>'The article category was not unpublished.');
		}
		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => $id,
			'model_name' => 'ArticleCategory',
			'what' => 'Unpublish article category'
		);
		$this->actionLogModel->insert($actionData);
		$this->db->transCommit();
		return array('unpublished'=>true,'message'=>'The article category was unpublished.');
	}

	function saveData($post_data, $user_id, $id = null)
	{
		$this->db->transBegin();

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_name' => 'ArticleCategory'
		);

		$data = array(
			'title' => $post_data['title'],
			'seo_title' => $post_data['seo_title'],
			'seo_description' => $post_data['seo_description'],
			'permalink' => $post_data['permalink'],
			'show_article_grid' => (empty($post_data['show_article_grid']) ? '0' : '1'),
			'parent_id' => (empty($post_data['parent_id']) ? '0' : $post_data['parent_id']),
			'opener_image_id' => (empty($post_data['opener_image_id']) ? NULL : $post_data['opener_image_id']),
			'social_image_id' => (empty($post_data['social_image_id']) ? NULL : $post_data['social_image_id']),
			'social_title' => $post_data['social_title'],
			'content' => fixPostContent(str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['content']),$user_id)
		);
		
		$data['modified_by'] = $user_id;
		$updateCache = false;
		$update_permalink_data = false;
		if (empty($id)) {
			$data['modified_date'] = date('Y-m-d H:i:s');
			$data['date_created'] = date('Y-m-d H:i:s');
			$data['user_id'] = $user_id;
			$id = $this->insert($data);
			$update_permalink_data = true;
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Created article category';			
		} else {
			$original_data = $this->find($id);
			$this->update($id, $data);
			if($original_data['published']) $updateCache = true;
			if($original_data['permalink'] != $data['permalink']){
				$update_permalink_data = true;
			}
			$actionData['model_id'] = $id;
			$actionData['what'] = 'Update article category';
		}
		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}
		
		if($update_permalink_data){
			$permalink_query = $this->builder('permalinks')
				->where('type','articleCategory')
				->where('type_id',$id)
				->get();
			$permalink_data = $permalink_query->getRowArray();
			if(empty($permalink_data['id'])){
				$this->db->table('permalinks')->insert(array(
					'type'=>'articleCategory',
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
		return array('id' => $id, 'updateCache' => $updateCache, 'update_permalink_data' => ($updateCache && $update_permalink_data));
	}

	function getForSelect(){
        $rows = $this->select('id, title')->orderBy('title ASC')->findAll();
        $response = array();
        foreach($rows as $row){
            $response[$row['id']] = $row;
        }
        return $response;
    }

    public function getFeedLength($id = null)
    {
        $builder = $this->builder('articles')->select('count(*) as total_count')
            ->where('published', '1');
        if (!empty($id)) {
            $categories = array();
            $children = $this->select('id')->where('parent_id', $id)->findAll();
            foreach ($children as $child) {
                $categories[] = $child['id'];
            }
            if (!empty($categories)) {
                $categories[] = $id;
                $builder = $builder->whereIn('article_category_id', $categories);
            } else {
                $builder = $builder->where('article_category_id', $id);
            }
        }

        $row = $builder->get()->getRowArray();
        return $row['total_count'];
    }

    public function getFeedPage($page = 1, $id = null): array
    {
        $builder = $this->builder('articles')->select('id')
            ->where('published', '1');
        if (!empty($id)) {
            $categories = array();
            $children = $this->select('id')->where('parent_id', $id)->findAll();
            foreach ($children as $child) {
                $categories[] = $child['id'];
            }
            if (!empty($categories)) {
                $categories[] = $id;
                $builder = $builder->whereIn('article_category_id', $categories);
            } else {
                $builder = $builder->where('article_category_id', $id);
            }
        }
        $builder = $builder->orderBy('date_published DESC, id DESC');
        $builder = $builder->limit(6, ($page - 1) * 6);
        $rows = $builder->get()->getResultArray();

        $articles = array();
        foreach ($rows as $row) {
            $articles[] = $row['id'];
        }
        return $articles;
    }


	function tokeniputSearch($params = array()){

		if (empty($params['sortingColumn'])) $params['sortingColumn'] = 'modified_date';
        if (empty($params['sortingType'])) $params['sortingType'] = 'desc';

        $builder = $this->builder('article_categories')->select('article_categories.id, article_categories.title');
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

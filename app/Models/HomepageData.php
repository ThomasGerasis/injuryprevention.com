<?php

namespace App\Models;

use CodeIgniter\Model;

class HomepageData extends Model
{
	protected $table = 'homepage';
	protected $primaryKey = 'id';
	protected $allowedFields = [
	    'title', 'seo_title', 'seo_description','social_image_id',
        'social_title','about_us_text', 'content', 'date_published',
        'date_created', 'modified_by','welcome_title','welcome_text', 'modified_date',
        'faq_title','faq_subtitle','faq_heading','faq_content'
    ];

	private ActionLog $actionLogModel;
	
	public function __construct()
    {
        parent::__construct();
        $this->actionLogModel = model(ActionLog::class);
    }

	function saveData($post_data, $user_id)
	{
		$this->db->transBegin();

		$actionData = array(
			'user_id' => $user_id, 
			'when' => date('Y-m-d H:i:s'), 
			'model_id' => 1,
			'model_name' => 'Update home page'
		);

		$data = array(
			'title' => $post_data['title'],
			'seo_title' => $post_data['seo_title'],
			'seo_description' => $post_data['seo_description'],
			'social_image_id' => (empty($post_data['social_image_id']) ? NULL : $post_data['social_image_id']),
			'social_title' => $post_data['social_title'],
            'welcome_title' => $post_data['welcome_title'],
            'welcome_text' => $post_data['welcome_text'],
            'about_us_text' => fixPostContent(str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['about_us_text']),$user_id),
			'content' => fixPostContent(str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['content']),$user_id),
			'modified_by' => $user_id,
            'faq_title' => $post_data['faq_title'],
            'faq_subtitle' => $post_data['faq_subtitle'],
            'faq_heading' => $post_data['faq_heading'],
            'faq_content' => str_replace(array(FRONT_SITE_URL,base_url()),'/',$post_data['faq_content'])
		);


		$this->update(1, $data);


        $savedFaqs = array();
        $pageFaqModel = model(PageFaq::class);
        if(!empty($post_data['faq'])){
            foreach($post_data['faq'] as $faq){
                $new_data = array(
                    'page_id' => 1,
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

        $faqsToDelete = $this->db->table('page_faqs')->where('page_id',1);
        if (count($savedFaqs)) {
            $faqsToDelete = $faqsToDelete->whereNotIn('id', $savedFaqs);
        }

        $faqsToDelete->delete();

		$this->actionLogModel->insert($actionData);

		if ($this->db->transStatus() === false) {
			$this->db->transRollback();
			return false;
		}

		$this->db->transCommit();
		return true;
	}
}

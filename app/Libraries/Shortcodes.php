<?php

namespace App\Libraries;

class Shortcodes
{

    public function __construct()
    {
    }

    public function getShortcodes()
    {

        $shortcodes = array();
        $shortcodes['heading'] = $this->headingAttrs();
        $shortcodes['article_slider'] = $this->articleSlider();
        $shortcodes['vertical_space'] = $this->verticalSpaces();
        $shortcodes['contact_form'] = $this->contactForm();
        $shortcodes['articles_row'] = $this->articlesRow();
        return $shortcodes;
    }


    public function verticalSpaces()
    {
        return array(
            'name' => 'Vertical space',
            'attrs' => array(
                'height' => array(
                    'name' => 'Height',
                    'type' => 'select',
                    'multiple' => false,
                    'required' => 'required',
                    'values' => array('10' => '10px', '15' => '15px', '20' => '20px', '25' => '25px', '30' => '30px', '35' => '35px', '40' => '40px', '45' => '45px', '50' => '50px'),
                ),
            )
        );
    }

    public function contactForm()
    {
        return array(
            'name' => 'Contact Form',
            'attrs' => array(

            )
        );
    }


    public function headingAttrs()
    {
        return array(
            'name' => 'Heading div',
            'multigeo' => false,
            'attrs' => array(
                'text' => array(
                    'name' => 'Text*',
                    'type' => 'input',
                    'required' => 'required'
                ),
                'type' => array(
                    'name' => 'Heading type*',
                    'type' => 'select',
                    'multiple' => false,
                    'required' => 'required',
                    'values' => array('div' => 'No heading', 'h1' => 'H1', 'h2' => 'H2', 'h3' => 'H3', 'h4' => 'H4', 'h5' => 'H5'),
                ),
            )
        );
    }

    public function articlesRow()
    {
        return array(
            'name' => 'Article Row',
            'attrs' => array(
                'article_ids' => array(
                    'name' => 'Selected articles',
                    'type' => 'tokeninput',
                    'multiple' => true,
                    'sourceType' => 'url',
                    'sourceUrl' => 'admin/tokenInputSearch/searchArticles',
                    'dataSourceType' => 'model',
                    'dataSourceName' => 'Article',
                    'dataSourceField' => 'title',
                    'desc' => 'If no selected articles or less than limit, latest articles up to limit will be shown'
                ),
                'article_category_ids' => array(
                    'name' => 'Selected categories',
                    'type' => 'select',
                    'multiple' => true,
                    'sourceType' => 'model',
                    'sourceName' => 'ArticleCategory',
                    'sourceFunction' => 'getForSelect',
                    /*'sourceType' => 'url',
					'sourceUrl' => 'tokenInputSearch/searchArticleCategories',
					'dataSourceType' => 'model',
					'dataSourceName' => 'ArticleCategory',
					'dataSourceField' => 'title',*/
                    'desc' => 'If articles less than limit, latest articles only from these categories up to limit will be shown'
                ),
                'limit' => array(
                    'name' => 'Limit*',
                    'type' => 'input',
                    'required' => 'required',
                    'integer' => true
                ),
            )
        );
    }

    public function articleSlider()
    {
        return array(
            'name' => 'Article slider',
            'attrs' => array(
                'article_ids' => array(
                    'name' => 'Selected articles',
                    'type' => 'tokeninput',
                    'multiple' => true,
                    'sourceType' => 'url',
                    'sourceUrl' => 'admin/tokenInputSearch/searchArticles',
                    'dataSourceType' => 'model',
                    'dataSourceName' => 'Article',
                    'dataSourceField' => 'title',
                    'desc' => 'If no selected articles or less than limit, latest articles up to limit will be shown'
                ),
                'article_category_ids' => array(
                    'name' => 'Selected categories',
                    'type' => 'select',
                    'multiple' => true,
                    'sourceType' => 'model',
                    'sourceName' => 'ArticleCategory',
                    'sourceFunction' => 'getForSelect',
                    /*'sourceType' => 'url',
					'sourceUrl' => 'tokenInputSearch/searchArticleCategories',
					'dataSourceType' => 'model',
					'dataSourceName' => 'ArticleCategory',
					'dataSourceField' => 'title',*/
                    'desc' => 'If articles less than limit, latest articles only from these categories up to limit will be shown'
                ),
                'limit' => array(
                    'name' => 'Limit*',
                    'type' => 'input',
                    'required' => 'required',
                    'integer' => true
                ),
            )
        );
    }

    public function searchArticles()
    {
        return array(
            'name' => 'Search articles',
            'attrs' => array(
                'search_title' => array(
                    'name' => 'Title',
                    'required' => 'required',
                    'type' => 'input'
                ),
                'search_subtitle' => array(
                    'name' => 'Subtitle',
                    'type' => 'input'
                ),
                'search_placeholder' => array(
                    'name' => 'Placeholder for search input',
                    'type' => 'input'
                ),
                'article_ids' => array(
                    'name' => 'Selected articles',
                    'type' => 'tokeninput',
                    'multiple' => true,
                    'sourceType' => 'url',
                    'sourceUrl' => 'admin/tokenInputSearch/searchArticles',
                    'dataSourceType' => 'model',
                    'dataSourceName' => 'Article',
                    'dataSourceField' => 'title',
                    'desc' => 'to be shown once the user clicks the search input'
                ),
            )
        );
    }

}

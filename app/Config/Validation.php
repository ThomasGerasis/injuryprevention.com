<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $addUser = [
        'username' => 'required|is_unique[users.username]',
        'email' => 'required|is_unique[users.email]'
    ];

    public $editUser = [
        'id' => '',
        'email' => 'required|is_unique[users.email]',
        'username' => 'required|is_unique[users.username,id,{id}]',
    ];

    public $survey = [
        'name' => 'required',
        'question.*.type' => 'required|in_list[multiple, singular, freetext, casino_multiple, casino_singular]',
    ];

    public $addPoll = [
        'question' => 'required',
        'category_id' => 'required',
        'answers.*.value' => 'required',
        'answers.*.sequence_number' => 'required',
    ];

    public $addPollCategory = [
        'id' => 'if_exist|required',
        'name' => 'required|is_unique[poll_category.name, id, {id}]',
    ];

    public array $addMission = [
        'title' => 'required',
        'permalink' => 'required|is_unique[mission.permalink]',
        'starts_at_date' => 'required',
        'starts_at_time' => 'required',
        'expires_at_date' => 'required',
        'expires_at_time' => 'required'
    ];

    public array $mission = [
        'id' => 'if_exist|required',
        'title' => 'required',
        'permalink' => 'required|is_unique[mission.permalink, id, {id}]',
        'starts_at_date' => 'required',
        'starts_at_time' => 'required',
        'expires_at_date' => 'required',
        'expires_at_time' => 'required'
    ];

    public array $mission_errors = [
        'title'  => ['required' => 'Title field is required'],
        'permalink' => ['required' => 'Permalink field is required', 'is_unique' => 'Permalink field must be unique'],
    ];

    public $addMissionCategory = [
        'id' => 'if_exist|required',
        'name' => 'required|is_unique[mission_category.name, id, {id}]',
    ];
}

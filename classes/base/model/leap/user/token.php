<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Copyright 2012 CubedEye
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * This class represents a record in the "user_tokens" table.
 *
 * @package Leap
 * @category Model
 * @version 2012-03-27
 */
class Base_Model_Leap_User_Token extends DB_ORM_Model {

	public function __construct()
	{
		parent::__construct();
		$this->fields = array(
			'utID' => new DB_ORM_Field_Integer($this, array(
				'max_length' => 11,
				'nullable' => FALSE,
			)),
			'utUserAgent' => new DB_ORM_Field_String($this, array(
				'max_length' => 40,
				'nullable' => FALSE,
			)),
			'utToken' => new DB_ORM_Field_String($this, array(
				'max_length' => 40,
				'nullable' => FALSE,
			)),
			'utType' => new DB_ORM_Field_String($this, array(
				'max_length' => 100,
				'nullable' => TRUE,
			)),
			'utCreated' => new DB_ORM_Field_Integer($this, array(
				'max_length' => 11,
				'nullable' => TRUE,
			)),
			'utExpires' => new DB_ORM_Field_Integer($this, array(
				'max_length' => 11,
				'nullable' => TRUE,
			)),
			'uID' => new DB_ORM_Field_Integer($this, array(
				'max_length' => 11,
				'nullable' => TRUE,
			)),
		);
		
		$this->aliases = array(
			'id' => new DB_ORM_Field_Alias($this, 'utID'),
			'user_agent' => new DB_ORM_Field_Alias($this, 'utUserAgent'),
			'token' => new DB_ORM_Field_Alias($this, 'utToken'),
			'type' => new DB_ORM_Field_Alias($this, 'utType'),
			'created' => new DB_ORM_Field_Alias($this, 'utCreated'),
			'expires' => new DB_ORM_Field_Alias($this, 'utExpires'),
			'user_id' => new DB_ORM_Field_Alias($this, 'uID'),
		);
		
		$this->relations = array(
			'user' => new DB_ORM_Relation_BelongsTo($this, array(
				'child_key' => array('uID'),
				'parent_key' => array('uID'),
				'parent_model' => 'User',
			)),
		);
	}
	
	public static function data_source()
	{
		return 'default';
	}
	
	public static function table()
	{
		return 'user_tokens';
	}
	
	public static function primary_key()
	{
		return array('utID');	
	}
	
	public function save($reload = FALSE)
	{
		$this->token = $this->create_token();
		parent::save($reload);
	}
	
	public function create_token()
	{
		do
		{
			$token = sha1(uniqid(Text::random('alnum', 32), TRUE));
		}while(DB_SQL::select($this->data_source())->from($this->table())->where('utToken','=',$token)->query()->is_loaded());

		return $token;
	}
}
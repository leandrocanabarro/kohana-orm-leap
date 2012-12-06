<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Copyright 2011-2012 Spadefoot
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
 * This class builds a MS SQL insert statement.
 *
 * @package Leap
 * @category MS SQL
 * @version 2012-12-05
 *
 * @see http://msdn.microsoft.com/en-us/library/aa933206%28v=sql.80%29.aspx
 */
abstract class Base_DB_MsSQL_Insert_Builder extends DB_SQL_Insert_Builder {

	/**
	 * This function returns the SQL statement.
	 *
	 * @access public
	 * @override
	 * @param boolean $terminated           whether to add a semi-colon to the end
	 *                                      of the statement
	 * @return string                       the SQL statement
	 */
	public function statement($terminated = TRUE) {
		$sql = "INSERT INTO {$this->data['into']}";

		if ( ! empty($this->data['columns'])) {
			$rows = array_values($this->data['rows']);
			$rowCt = 1;
			$columns = array_keys($this->data['columns']);
			$columnCt = count($columns);
			$sql .= ' (' . implode(', ', $columns) . ') VALUES';
			for ($r = 0; $r < $rowCt; $r++) {
				if ($r > 0) {
					$sql .= ',';
				}
				$sql .= ' (';
				for ($c = 0; $c < $columnCt; $c++) {
					if ($c > 0) {
						$sql .= ', ';
					}
					$column = $columns[$c];
					$sql .= (isset($rows[$r][$column]))
						? $rows[$r][$column]
						: 'NULL';
				}
				$sql .= ')';
			}
		}

		if ($terminated) {
			$sql .= ';';
		}

		return $sql;
	}

}
?>
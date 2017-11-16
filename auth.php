<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * A captcha validation will be needed to login.
 *
 * @package auth_recaptcha
 * @author Román Huerta
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

/**
 * Plugin for no authentication.
 */
class auth_plugin_recaptcha extends auth_plugin_base {

	/**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'recaptcha';
        $this->config = get_config('auth_recaptcha');
    }

	public function user_login($username, $password){
		return true;
	}

	function can_be_manually_set() {
        return true;
    }

    function loginpage_hook() {
    	global $CFG, $SESSION, $DB;

    	redirect($CFG->wwwroot.'/auth/recaptcha/index.php');
    }

}
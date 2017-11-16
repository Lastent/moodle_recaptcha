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
 * Admin settings and defaults.
 *
 * @package auth_recaptcha
 * @copyright  2017 Roman Huerta
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_recaptcha/pluginname', '',
        new lang_string('auth_recaptchadescription', 'auth_recaptcha')));

    $settings->add( new admin_setting_configtext('auth_recaptcha/site_key',
        new lang_string('auth_recaptchasite_key', 'auth_recaptcha'),
        new lang_string('auth_recaptchasite_key_description', 'auth_recaptcha'),''));


    $settings->add( new admin_setting_configtext('auth_recaptcha/secret_key',
        new lang_string('auth_recaptchasecret_key', 'auth_recaptcha'),
        new lang_string('auth_recaptchasecret_key_description', 'auth_recaptcha'),''));

    // Display locking / mapping of profile fields.
    $authplugin = get_auth_plugin('recaptcha');
    display_auth_lock_options($settings, $authplugin->authtype, $authplugin->userfields,
            get_string('auth_fieldlocks_help', 'auth'), false, false);
}

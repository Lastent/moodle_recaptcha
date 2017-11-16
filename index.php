<?php
require('../../config.php');

if (!isset($CFG->additionalhtmlhead)) {
    $CFG->additionalhtmlhead = '';
}

if (!isset($CFG->additionalhtmlhead)) {
    $CFG->additionalhtmlhead = '';
}

$CFG->additionalhtmlhead .= '<meta name="robots" content="noindex" />';
$CFG->additionalhtmlhead .= '<script type="text/javascript">var sessionKey = "'.sesskey().'";</script>';

$context = context_system::instance();
$PAGE->set_url("$CFG->httpswwwroot/auth/recaptcha/index.php");
$PAGE->set_context($context);
$PAGE->set_pagelayout('login');

$PAGE->navbar->ignore_active();
$loginsite = get_string("loginsite");
$PAGE->navbar->add($loginsite);

$mb_siteKey = get_config("auth_recaptcha", 'site_key');
$mb_secret = get_config("auth_recaptcha", 'secret_key');

if (!empty($_POST)){

	$frm = base64_decode($_POST['data']);
	$frm = json_decode($frm);

	$username = $frm->username;
	$password = $frm->password;

	$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$mb_secret.'&response='.$frm->recaptcha);
	$responseData = json_decode($verifyResponse);

	if ($responseData->success){
		global $CFG, $DB;
        if ($user = $DB->get_record('user', array('username'=>$username, 'mnethostid'=>$CFG->mnet_localhost_id))) {
        	$validate = validate_internal_user_password($user, $password);
        }
        else{
        	$validate =  false;
        }

        if ($validate){
        	complete_user_login($user);
        	redirect($CFG->wwwroot."/my");
        }
        else{
        	redirect($CFG->wwwroot.'/auth/recaptcha/index.php');
        }
	}
	else{
    	redirect($CFG->wwwroot.'/auth/recaptcha/index.php');
    }

}
else{
	echo $OUTPUT->header();

	if (isloggedin() and !isguestuser()) {
	    // prevent logging when already logged in, we do not want them to relogin by accident because sesskey would be changed
	    echo $OUTPUT->box_start();
	    $logout = new single_button(new moodle_url($CFG->httpswwwroot.'/login/logout.php', array('sesskey'=>sesskey(),'loginpage'=>1)), get_string('logout'), 'post');
	    $continue = new single_button(new moodle_url($CFG->httpswwwroot.'/login/index.php', array('cancel'=>1)), get_string('cancel'), 'get');
	    echo $OUTPUT->confirm(get_string('alreadyloggedin', 'error', fullname($USER)), $logout, $continue);
	    echo $OUTPUT->box_end();
	}
	else{
		$logourl = $OUTPUT->get_logo_url();
		$logourl = $logourl->out(false);
?>
	<div class="row">
		<div class="col-xl-6 push-xl-3 m-2-md col-sm-8 push-sm-2">
			<div class="card">
				<div class="card-block">
					<div class="card-title text-xs-center">
						<h2><img src="<?php echo $logourl; ?>" title="<?php echo $SITE->shortname;?>" alt="<?php echo $SITE->shortname;?>"></h2>
						<hr>
					</div>
					<div class="row">
						<div class="col-md-4 push-md-1">
							<form class="m-t-1" action="<?php echo $CFG->httpswwwroot.'/auth/recaptcha/index.php'; ?>" method="post" id="login">
								<input id="anchor" name="anchor" value="" type="hidden">
								<script>document.getElementById('anchor').value = location.hash;</script>
								<label for="username" class="sr-only">
									Username
								</label>
								<input name="username" id="username" class="form-control" value="" placeholder="Username" autocomplete="off" type="text">
								<label for="password" class="sr-only">Password</label>
								<input name="password" id="password" value="" class="form-control" placeholder="Password" autocomplete="off" type="password">
								<script src="https://www.google.com/recaptcha/api.js" async="" defer=""></script>
                                <div class="g-recaptcha" data-sitekey="<?php echo $mb_siteKey; ?>"></div>
								<div class="rememberpass m-t-1">
									<input name="rememberusername" id="rememberusername" value="1" type="checkbox">
									<label for="rememberusername">Remember username</label>
								</div>
								<button type="submit" class="btn btn-primary btn-block m-t-1" id="loginbtn">Log in</button>
							</form>
						</div>
						<div class="col-md-4 push-md-3">
							<div class="forgetpass m-t-1">
								<p><a href="<?php echo $CFG->httpswwwroot.'/login/forgot_password.php' ?>">Forgotten your username or password?</a></p>
							</div>
							<div class="m-t-1">
								Cookies must be enabled in your browser
								<a class="btn btn-link p-a-0" role="button" data-container="body" data-toggle="popover" data-placement="right" data-content="<div class=&quot;no-overflow&quot;><p>Two cookies are used by this site:</p>
								<p>The essential one is the session cookie, usually called MoodleSession. You must allow this cookie into your browser to provide continuity and maintain your login from page to page. When you log out or close the browser this cookie is destroyed (in your browser and on the server).</p>
								<p>The other cookie is purely for convenience, usually called something like MOODLEID. It just remembers your username within the browser. This means when you return to this site the username field on the login page will be already filled out for you. It is safe to refuse this cookie - you will just have to retype your username every time you log in.</p>
							</div> " data-html="true" tabindex="0" data-trigger="focus">
							<i class="icon fa fa-question-circle text-info fa-fw " aria-hidden="true" title="Help with Cookies must be enabled in your browser" aria-label="Help with Cookies must be enabled in your browser"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
		$PAGE->requires->js_call_amd('auth_recaptcha/recaptcha', 'init');
		echo $OUTPUT->footer();
	}
}
?>
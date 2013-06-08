

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Actums</title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/reset.css');?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/login.css');?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('/assets/css/validationEngine.jquery.css');?>"/>
        <script type="text/javascript" src="<?php echo base_url('/assets/javascript/jquery.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/javascript/loginregister.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/javascript/jquery.validationEngine-nl.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('/assets/javascript/jquery.validationEngine.js');?>"></script>

    </head>

    <body>
        <div id="header">
            <div id="headerimage">
            </div>
        </div>
        <div id="headershadow">
        </div>
        <div id="content">
            <div id="sliderwrapper">
                <div id="slidercontent">

		    <?php if (!isset($registrationerror)): ?>

    		    <span id="loginform">
			    <?php if (isset($loginerror) && $loginerror === true): ?>
				<span class="error">Er ging iets mis. De combinatie van e-mailadres en wachtwoord zijn fout.</span>
			    <?php endif; ?>
			    <?php if (isset($newmembersuccess) && $newmembersuccess === true): ?>
				<span class="success">Je account werd aangemaakt. Je kan nu inloggen.</span>
			    <?php endif; ?>
			    <?php echo $this->load->view('forms/member_login'); ?>
    			<!--
    			<form id='login' action='' method='POST' accept-charset='UTF-8'>
    			    <input type='hidden' name='submitted' id='submitted' value='1'/>
    			    <label for="email">e-mail</label>
    			    <input id='email' name='email' type="text" value="" />
    			    <span id='login_email_errorloc' class='error'></span>
    			    <label for="password">wachtwoord</label>
    			    <input id='password' name='password' type="password" value="" />
    			    <span id='login_password_errorloc' class='error'></span>
    			    <span class='error'></span>
    			    <a href="" id="lostpasswordbutton">wachtwoord of login vergeten?</a>            
    			    <br/>
    			    <input type="submit" id="submitlogin" value=""/>              
    			</form>
    			-->
    		    </span>

    		    <span id="algemenevoorwaarden">
    			<textarea>algemene voorwaarden
    			</textarea>
    			<input type="checkbox" id="chckalgemenevoorwaarden"/> Ik ga akkoord
    		    </span>

		    <?php endif; ?>
                    <span id="registreerform">
			<?php if (isset($registrationerror) && $registrationerror === true): ?>
    			<span class="error">Er ging iets mis. Mogelijks is het e-mailadres al geregistreerd.</span>
			<?php endif; ?>
			<?php echo $this->load->view('forms/member_create'); ?>
                        <!--
                        <form id='register' action='' method='POST' accept-charset='UTF-8'>
                            <input type="text" placeholder="Voornaam" id="regVoornaam" name="regVoornaam" value=""/>
                            <span id='register_regVoornaam_errorloc' class='regError'></span>
                            <input type="text" placeholder="Naam" id="regNaam" name="regNaam" value=""/>
                            <span id='register_regNaam_errorloc' class='regError'></span>
                            <input type="text" placeholder="Email" id="regEmail" name="regEmail" value=""/>
                            <span id='register_regEmail_errorloc' class='regError'></span>
                            <input type="text" placeholder="Gsm" id="regGsm" name="regGsm" value=""/>
                            <div id="passBlock">
                                <input type="password" placeholder="wachtwoord" id="regPass" name="regPass" value=""/>
                                <span id='register_regPass_errorloc' class='regError'></span>
                                <input type="password" placeholder="herhaal wachtwoord" id="regRepPass" name="regRepPass" value=""/>
                                <span id='register_regRepPass_errorloc' class='regError'></span>
                            </div>
                            <input type="submit" id="submitregistration" name="submitregistration" value=""/>
                            <span class='regError'></span>
                        </form>
                        -->
                    </span>
                </div>
            </div>
        </div>
        <div id="footer">
            <div id="extra"><a href="">Help</a>
                <a href="">Over</a>
            </div>
            &copy; Actums
        </div>
    </body>
    <!-- VALIDATIE -->

</html>

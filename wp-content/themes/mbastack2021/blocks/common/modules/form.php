<?php
/*
Modules flexuble content template for layout: mismatched_images
blocks/common/modules/mismatched_images
/source/scss/common/whoWeAreIntro.scss
*/


// assume form is invalid to start with
$valid = false;

// collect errors
$errors = [];

// check to see if form has been posted
// check nonce and form action

$formPost = $_POST;

// echo "<pre>".print_r( $_SESSION, true )."</pre>";
// echo "<pre>".print_r( $formPost, true )."</pre>";

/* */
if ( array_key_exists('formaction', $formPost) && array_key_exists('nonce', $formPost) ) {
	
	// nonce and formaction exist
	
	if ( $formPost['formaction'] === 'test' ) {

		// correct formaction value

		// verify nonce
		$nonce = new Nonce();
		if ( $nonce->verifyNonce( $formPost['nonce'] ) ) {
			// correct form action and veriried nonce

			// clear nonce so form can't be re-submitted
			session_destroy();

			// validate form fields
			// ...

			// forename: not blank, alphebetic characters, '-', '''
			// blank
			// invalid characters

			// surname: not blank, alphebetic characters, '-', '''
			// blank
			// invalid characters

			// email1: valid email address
			// blank
			// invalid characters
			// invalid structure/format

			// email2: valid email address
			// blank
			// invalid characters
			// invalid structure/format

			// email1 = email2
			// email addresses dpo not match

				$valid = true;


		} else {

			// nonce verificatino fails

			// form invalid
		}
	} else {
		// formaction value incorrect

		// form invalid
	}
} else {
	// $_POST fields 'formaction' and 'nonce' not present

	// form invalid
}
/* */

// form is not valid
if ( $valid ) {
	// process
	
} else {
	// invalid
	// generate new nonce
	$nonce = new Nonce();
	$myToken = $nonce->generateNonce(5, "form_login", 10);
}
?>

<style>
	.form .field{
		width:100%;
		display:flex;
	}
	.form .field label{
		margin-right:1rem;
	}
</style>
<section class="module form">
	<div class="copy">
		<div class="container">

			<?php if ( $valid ) : ?>
			<h2>valid</h2>	
			<?php else : ?>
			<form name="textform" action="" method="post">

				<input type="text" name="formaction" id="formaction" value="test">
				<input type="text" name="nonce" id="nonce" value="<?= $myToken ?>">

				<div class="field">
					<label for="forename">Forename</label>
					<input type="text" name="forename" id="forename" value="">
				</div>

				<div class="field">
					<label for="surname">Surname</label>
					<input type="text" name="surname" id="surname" value="">
				</div>

				<div class="field">
					<label for="email1">Email</label>
					<input type="email" name="email1" id="email1" value="">
				</div>
				<div class="field">
					<label for="email2">Confirm Email</label>
					<input type="emai2" name="email2" id="email2" value="">
				</div>

				<input type="submit" id="submit01" value="submit">
			</form>
			<?php endif; ?>

		</div>
	</div>

</section>

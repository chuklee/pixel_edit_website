<?php
/*
Fonction captcha, merci au site http://www.codewhirl.com/2011/04/very-simple-captcha-function-for-forms/ pour le captcha! ;)
*/
function Captcha( $session_var = "CaptchaPdsContact", $font = 'monofont.ttf',$image_width=120, $image_height=40,$characters_on_image = 6,$random_dots = 20,$random_lines = 4,$captcha_text_color="0x142864",$captcha_noice_color = "0xCCCCCC",	$possible_letters = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghjklmnopqrstuvwxyz@#'){
	//    Note: JPEG support is only available if PHP was compiled	against GD-1.8 or later.
	$code = '';
	$i = 0;
		while ($i < $characters_on_image) {
			$code .= substr($possible_letters,mt_rand(0, strlen($possible_letters)-1), 1);
			$i++;
		}

	$font_size = $image_height * 0.75;
	$image = @imagecreate($image_width, $image_height);

	/* setting the background, text and noise colours here */
	$background_color = imagecolorallocate($image, 255, 255, 255);
	$arr_text_color =  array("red" => 0xFF & ( hexdec( $captcha_text_color ) >> 0x10),	"green" => 0xFF & ( hexdec( $captcha_text_color ) >> 0x8),	"blue" => 0xFF & hexdec( $captcha_text_color ) );
	$text_color = imagecolorallocate($image, $arr_text_color['red'],
	$arr_text_color['green'], $arr_text_color['blue']);
	$arr_noice_color =  array("red" => 0xFF & ( hexdec( $captcha_noice_color ) >> 0x10),"green" => 0xFF & ( hexdec( $captcha_noice_color ) >> 0x8),	"blue" => 0xFF & hexdec( $captcha_noice_color ) );
	$image_noise_color = imagecolorallocate($image, $arr_noice_color['red'],$arr_noice_color['green'], $arr_noice_color['blue']);

	/* generating the dots randomly in background */
		for( $i=0; $i<$random_dots; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$image_width),mt_rand(0,$image_height), 2, 3, $image_noise_color);
		}

	/* generating lines randomly in background of image */
		for( $i=0; $i<$random_lines; $i++ ) {
			imageline($image, mt_rand(0,$image_width), mt_rand(0,$image_height),mt_rand(0,$image_width), mt_rand(0,$image_height),$image_noise_color);
		}

	/* create a text box and add 6 letters code in it */
	$textbox = imagettfbbox($font_size, 0, $font, $code);
	$x = ($image_width - $textbox[4])/2;
	$y = ($image_height - $textbox[5])/2;
	imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code);

	/* Show captcha image in the page html page */
	ob_start();
	imagejpeg( $image );
	echo '<img src="data:image/jpeg;base64,'.chunk_split( base64_encode( ob_get_clean() ) ) .'" alt="Captcha" title="Captcha">';
	imagedestroy($image);//destroying the image instance
	$_SESSION[ $session_var ] = $code;
}

function CreerFormulaireContact(){
	?>
	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="<?php echo URL_SCRIPT; ?>/PdsContactSimple.css" />
	<script type="text/javascript" src= "http://code.jquery.com/jquery-latest.min.js"></script>
	<script type="text/javascript" src="<?php echo URL_SCRIPT; ?>/PdsContactSimple.js"></script>
	<section>
		<form action="" method="post" id="FormulaireContactSimple">
			<fieldset>
			Votre email:<br />
			<input type="text" name="EmailContact" id="ChampEmailContact"/><br />
			Sujet de votre message:<br />
			<input type="text" name="SujetContact" id="ChampSujetContact"/><br />
			Votre Message:<br />
			<textarea name="MessageContact" id="ChampMessageContact"></textarea><br />
			<?php
			//On regarde si le HTML est autorisé ou non et on l'affiche
				if (AUTORISER_HTML==="oui") {
					echo '<small>Le code <strong>HTML</strong> est autorisé<br /><br />';
				}
				else{
					echo '<small>Le code <strong>HTML</strong> n\'est pas autorisé<br /><br />';
				}
			//On affiche la fonction captcha
			Captcha();
			?><br />
			Indiquez les 6 caract&egrave;res de l'image ci dessus:<br />
			<input type="text" name="AntiSpamContact" id="ChampAntiSpamContact" /><br />
            <div class="12u$">
			<input type="button" name="BoutonValiderFormulaire" value="Envoyer le message" onclick="ValiderPdsContact();"/></div>
			<div id="ResultatPdsContactSimple"></div>
			
			</fieldset>
		</form>
		</section>
		</div>
	</div>
	<?php
}
?>
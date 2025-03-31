<?php
// app/Helpers/Helper.php

if ( ! function_exists( 'replace_url' ) ) {
	function replace_url( $url ) {
		return str_replace( 'http://127.0.0.1:8000/', 'https://ciakit.com/', $url );
	}
}
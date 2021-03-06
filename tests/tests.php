<?php

/*

Here, have some unit tests.

Author: fluffy, http://beesbuzz.biz/

 */

?><!DOCTYPE html>
<html><head><title>tests</title><style>
.pass { background: #7f7; }
.fail { background: #f7f; }
</style></head>
<body>
<?php

require_once '../src/urljoin.php';

function test($base, $url, $expected) {
	$out = urljoin($base, $url);
	echo "<div>$base + $url => ";
	if ($out == $expected) {
		echo '<span class="pass">' . $out . '</span>';
	} else {
		echo '<span class="fail">' . $out . '</span> (expected: ' . $expected . ')';
	}
	echo '</div>';
}

# A file in the same directory
test("http://beesbuzz.biz/foo/bar", "test.jpg", "http://beesbuzz.biz/foo/test.jpg");
# A file in the root directory
test("http://beesbuzz.biz/foo/bar", "/test.jpg", "http://beesbuzz.biz/test.jpg");
# A file in the parent directory
test("http://beesbuzz.biz/foo/bar/baz", "../test.jpg", "http://beesbuzz.biz/foo/test.jpg");
# A file more directories up than there are directories to escape
test("http://beesbuzz.biz/foo/bar/baz/quux", "../../../../../test.jpg", "http://beesbuzz.biz/../../test.jpg");

# The current directory itself
test("http://beesbuzz.biz/foo/", ".", "http://beesbuzz.biz/foo/");
test("http://beesbuzz.biz/foo/", "./", "http://beesbuzz.biz/foo/");
test("http://beesbuzz.biz/foo", ".", "http://beesbuzz.biz/");
test("http://beesbuzz.biz/foo", "./", "http://beesbuzz.biz/");

# Different server, same scheme
test("http://beesbuzz.biz/foo/bar", "//sockpuppet.us/test.jpg", "http://sockpuppet.us/test.jpg");
test("https://beesbuzz.biz/foo/bar", "//sockpuppet.us/test.jpg", "https://sockpuppet.us/test.jpg");

# Ensure queries work right
test("https://beesbuzz.biz/foo/bar.cgi?hello=goodbye", "moo.cgi?yes=no", "https://beesbuzz.biz/foo/moo.cgi?yes=no");
test("http://beesbuzz.biz/foo/?qwer=poiu", "bar", "http://beesbuzz.biz/foo/bar");
test("http://beesbuzz.biz/foo/", "bar?qwer=poiu", "http://beesbuzz.biz/foo/bar?qwer=poiu");

# Users and passwords should transfer for relative links
test("http://fluffy:poopbutt@beesbuzz.biz/foo/bar", ".", "http://fluffy:poopbutt@beesbuzz.biz/foo/");
test("http://fluffy:poopbutt@beesbuzz.biz/foo/bar", "/test/url", "http://fluffy:poopbutt@beesbuzz.biz/test/url");
test("http://spambot@beesbuzz.biz/foo/bar", "/test/url", "http://spambot@beesbuzz.biz/test/url");

# But shouldn't transfer to other servers
test("https://fluffy:poopbutt@beesbuzz.biz/foo/bar", "//sockpuppet.us/test/url", "https://sockpuppet.us/test/url");

# Port specifiers
test("https://beesbuzz.biz:8000/foo/bar", "//sockpuppet.us/test/url", "https://sockpuppet.us/test/url");
test("https://beesbuzz.biz:8000/foo/bar", "/test/url", "https://beesbuzz.biz:8000/test/url");

# File paths are fiddly
test("file:///path/to/file", "other-file", "file:///path/to/other-file");
test("/path/to/file", "other-file", "/path/to/other-file");

# Anchors are too
test("http://beesbuzz.biz/test/foo#anchor", "bar", "http://beesbuzz.biz/test/bar");
test("http://beesbuzz.biz/test/foo", "bar#anchor", "http://beesbuzz.biz/test/bar#anchor");

# Mixing non-relative and relative url
test("http://beesbuzz.biz/foo/bar", "javascript:void(0)", "javascript:void(0)");

# Sanity checks
test("http://beesbuzz.biz/foo/bar", false, "http://beesbuzz.biz/foo/bar");
test(false, "http://beesbuzz.biz/foo/bar", "http://beesbuzz.biz/foo/bar");

?>
</body></html>

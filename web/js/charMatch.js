//字符匹配input oninput 监听enter
function charMatch(e, i) {
	let chars = origins[i].querySelectorAll('span');
	for (let pos = 0; pos < answers[i].value.length; pos++) {
		if (chars[pos].innerHTML == answers[i].value[pos]) {
			if (!chars[pos].classList.contains('correct')) {
				chars[pos].classList.add('correct');
			}
			if (chars[pos].classList.contains('incorrect')) {
				chars[pos].classList.remove('incorrect');
			}
		}
		else {
			if (chars[pos].classList.contains('correct')) {
				chars[pos].classList.remove('correct');
			}
			if (!chars[pos].classList.contains('incorrect')) {
				chars[pos].classList.add('incorrect');
			}
		}
	}
	for (let pos = answers[i].value.length; pos < chars.length; pos++) {
		if (chars[pos].classList.contains('correct')) {
			chars[pos].classList.remove('correct');
		}
		if (chars[pos].classList.contains('incorrect')) {
			chars[pos].classList.remove('incorrect');
		}
	}
	if (lineOrigin[i].value.indexOf('#') == -1 && lineOrigin[i].value.indexOf('&') == -1) {
		if (answers[i].value.length == lineOrigin[i].value.length) {
			answers[i].setAttribute('disabled', '');
			answers[i + 1].removeAttribute('disabled');
			answers[i + 1].focus();
		}
	}
}

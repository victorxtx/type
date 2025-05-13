var lineWraps = document.querySelectorAll('.line-wrap');
var origins = document.querySelectorAll('.origin');
var lineOrigin = document.querySelectorAll('.line-origin');//input[hidden]
var answers = document.querySelectorAll('.answer');
var backspaceCount = 0;
var deleteCount = 0;
var currentLineFocus = 0;
answers[0].focus();
window.scrollTo(0, 0);
var lineFocus = 0;
window.popup = function(title, msg,	parent = document.body,	width = 144, height = 72){
	let box = document.createElement('div');//大框
	box.classList.add("pop-box");
	let divTitle = document.createElement('div');//标题wrap
	divTitle.classList.add("pop-title")
	divTitle.innerHTML = title;
	let divMain = document.createElement('div');//内容wrap
	divMain.classList.add("pop-main")
	divMain.innerHTML = msg;
	let btn = document.createElement('button')//button
	btn.innerHTML = "知道了";
	btn.style.position = "absolute";
	btn.classList.add("pop-button")
	// btn.
	box.appendChild(divTitle);
	box.appendChild(divMain);
	box.appendChild(btn)
	box.style.width = width + 'px';
	box.style.height = height + 'px';
	parent.appendChild(box);
	btn.addEventListener('click', () => {
		for (let i = 0; i < answers.length; i++){
			answers[i].value = "";
		}
		let tempAllChars = document.querySelectorAll('.chars');
		for (let key = 0, len = tempAllChars.length; key < len; key++){
			if (tempAllChars[key].classList.contains("correct")){
				tempAllChars[key].classList.remove("correct");
			}
			if (tempAllChars[key].classList.contains("incorrect")){
				tempAllChars[key].classList.remove("incorrect");
			}
		}
		backspaceCount = 0;
		deleteCount = 0;
		document.querySelector('.pop-box').remove();
		window.scrollTo(0, 0);
		answers[0].focus();
		
	})
	// setTimeout(() => {
	// 	box.remove();
	// }, timeout);
}
for (let i = 0; i < origins.length; i++){
	lineWraps[i].addEventListener('click', function(e){
		wrapClick(e, i);
	});
	lineWraps[i].addEventListener('mouseover', function(e){
		overPoint(e, i);
	});
	lineWraps[i].addEventListener('mouseout', function(e){
		outPoint(e, i);
	});
	answers[i].addEventListener('input', function(e){
		charMatch(e, i);
	});
	answers[i].addEventListener('keyup', function(e){
		charEnter(e, i);
	});
	answers[i].addEventListener('focus', function(e){
		highLine(e, i);
	})
	answers[i].addEventListener('blur', function(e){
		inputBlur(e, i);
	});
}
function wrapClick(e, i){
	if (!answers[i].disabled){
		answers[i].focus();
	}
}
function overPoint(e, i){
	if (!answers[i].disabled){
		lineWraps[i].style.cursor = "pointer";
	}
}
function outPoint(e, i){
	lineWraps[i].style.cursor = "";
}
//字符匹配input oninput 监听enter
function charMatch(e, i){
	let chars = origins[i].querySelectorAll('span');
	for (let pos = 0; pos < answers[i].value.length; pos++){
		if (chars[pos].innerHTML == answers[i].value[pos]){
			if (!chars[pos].classList.contains('correct')){
				chars[pos].classList.add('correct');
			}
			if (chars[pos].classList.contains('incorrect')){
				chars[pos].classList.remove('incorrect');
			}
		}
		else{
			if (chars[pos].classList.contains('correct')){
				chars[pos].classList.remove('correct');
			}
			if (!chars[pos].classList.contains('incorrect')){
				chars[pos].classList.add('incorrect');
			}
		}
	}
	for (let pos = answers[i].value.length; pos < chars.length; pos++){
		if (chars[pos].classList.contains('correct')){
			chars[pos].classList.remove('correct');
		}
		if (chars[pos].classList.contains('incorrect')){
			chars[pos].classList.remove('incorrect');
		}
	}
	if (lineOrigin[i].value.indexOf('#') == -1 && lineOrigin[i].value.indexOf('&') == -1){
		if (answers[i].value.length == lineOrigin[i].value.length){
			answers[i].setAttribute('disabled','');
			answers[i + 1].removeAttribute('disabled');
			answers[i + 1].focus();
			window.scrollBy(0, 100);
		}
	}
	if (lineOrigin[i].value.indexOf('&') != -1){
		if (answers[i].value.length == lineOrigin[i].value.length - 1){
			setTimeout(() => {
				ending();
			}, 5);
		}
	}
}
function charEnter(e, i){
	if (e.key == 'Backspace'){
		backspaceCount++;
	}
	if (e.key == 'Delete'){
		deleteCount++;
	}
	if (lineOrigin[i].value.indexOf('#') != -1){//存在#
		if (answers[i].value.length == lineOrigin[i].value.length - 1){
			if (e.key == 'Enter'){
				answers[i].setAttribute('disabled','');
				answers[i + 1].removeAttribute('disabled');
				answers[i + 1].focus();
				window.scrollBy(0, 110);
			}
		}
	}
}
function highLine(e, i){
	document.querySelectorAll('.line-wrap')[i].classList.add('active');
}
function inputBlur(e, i){
	document.querySelectorAll('.line-wrap')[i].classList.remove('active');
}
// menu

document.querySelector('.debug').addEventListener('click', () => {
	ending();
});
function ending(e){
	let arrChars = document.querySelectorAll('.chars');
	let numChars = arrChars.length;
	let bingos = document.querySelectorAll('.correct');
	let numBingos = bingos.length;
	let misses = document.querySelectorAll('.incorrect');
	let numMisses = misses.length;
	if (numBingos == 0 && numMisses == 0){
		window.popup('简要结果', '白卷', document.body, 240, 300, 10);
	}
	else{
		let mistakes = backspaceCount + deleteCount;
		let missChance = (mistakes + numMisses) / numChars;
		// console.log(mistakes)
		// console.log(missChance)
		let hitChance = 1 - missChance;
		window.popup('简要结果', '总计字数: <b>' + numChars + '</b> 个<br>正确字数: <b style="color:rgba(127, 255, 0, 1)">' + numBingos + '</b> 个<br>错误字数: <b style="color:rgba(220, 20, 60, 1)">' + numMisses + '</b> 个<br>修正字数: <b style="color:rgba(255, 69, 0, 1)">' + mistakes + '</b> 个<br>命中率: <span style="color:rgba(66, 184, 20, 1)">' + hitChance.toFixed(4) + '</span> %<br>错误率: <span style="color:rgba(220, 20, 60, 1)">' + missChance.toFixed(4) + '</span> %', document.body, 240, 300, 10)
	}
	for (let i = 0, len = answers.length; i < len; i++){
		if (i == 0){
			if (answers[i].hasAttribute('disabled')){
				answers[i].removeAttribute('disabled')
			}
		}
		else{
			if (!answers[i].hasAttribute('disabled')){
				answers[i].setAttribute('disabled','')
			}
		}
	}
	setTimeout(() => {
		let popBox = document.querySelector('.pop-box');
		if (popBox){
			popBox.remove()
		}
	}, 10000);
	
}
var hideSpace = document.querySelector('.hide-space');
hideSpace.addEventListener('click', (e) => {
	let spaces = document.querySelectorAll('.space');
	if (hideSpace.checked){
		for (let i = 0, len = spaces.length; i < len; i++){
			spaces[i].classList.remove('show');
		}
	}
	else{
		for (let i = 0, len = spaces.length; i < len; i++){
			spaces[i].classList.add('show');
		}
	}
});
var hideEnter = document.querySelector('.hide-enter');
hideEnter.addEventListener('click', (e) => {
	let eols = document.querySelectorAll('.end-of-line');
	if (hideEnter.checked){
		for (let i = 0, len = eols.length; i < len; i++){
			eols[i].classList.remove('show');
		}
	}
	else{
		for (let i = 0, len = eols.length; i < len; i++){
			eols[i].classList.add('show');
		}
	}
})
//menu
var sections = document.querySelectorAll('.section');
var selectBook = document.querySelector('.select-book');
var selectVolume = document.querySelector('.select-volume');
var dropdownBook = document.querySelector('.dropdown-book');
var dropdownVolume = document.querySelector('.dropdown-volume');
dropdownBook.addEventListener('mouseover', () => {
	selectBook.classList.add('active')
});
dropdownBook.addEventListener('mouseout', () => {
	selectBook.classList.remove('active')
});
dropdownVolume.addEventListener('mouseover', () => {
	selectVolume.classList.add('active')
});
dropdownVolume.addEventListener('mouseout', () => {
	selectVolume.classList.remove('active')
});
dropdownBook.addEventListener('scroll', () => {
	window.scrollTo(0, 0);
}, true);
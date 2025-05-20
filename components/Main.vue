<template>
    <header id="home">
		<div class="header">
			<div class="background-pattern"></div>
			<div class="wrapper">

				<div class="header_con">

					<button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
						<div class="toggle-track">
							<div class="toggle-icon">
								{{ isDark ? '☀️' : '🌙' }}
							</div>
						</div>
					</button>

					<picture class="me">
						<source srcset="/images/me.webp" type="image/webp">
						<img data-src="/images/me.jpg" src="/images/me.jpg" alt="James Daniel Enovero" class="lazy">
					</picture>

					<h1 class="typewriter">James Daniel Enovero</h1>

					<h2>I'm a <span class="typewrite" data-period="3000" data-type='["front-end developer.","back-end developer."]'></span></h2>

					<p>Hi there! I'm a web developer with over <strong>6 years</strong> of experience in <strong>HTML</strong>, <strong>CSS</strong>, <strong>JavaScript</strong>, and <strong>PHP</strong>. I also have expertise in <strong>Vue.js</strong> for developing web and mobile applications. I'm continuously learning and adapting to the latest industry trends.</p>

					<ul class="logo">
						<li><figure><a href="https://github.com/jamesxdaniel" target="_blank" rel="noopener"><img data-src="/images/github_logo.png" src="/images/github_logo.png" alt="GitHub" class="lazy"></a></figure></li>
						<li><figure><a href="https://www.linkedin.com/in/james-daniel-enovero/" target="_blank" rel="noopener"><img data-src="/images/linked_logo.png" src="/images/linked_logo.png" alt="LinkedIn" class="lazy"></a></figure></li>
						<li><figure><a href="https://www.instagram.com/jaamesdaaniel" target="_blank" rel="noopener"><img data-src="/images/instagram_logo.png" src="/images/instagram_logo.png" alt="Instagram" class="lazy"></a></figure></li>
						<li><figure><a href="mailto:jamesdaniel288@gmail.com"><img data-src="/images/email_logo.png" src="/images/email_logo.png" alt="Email" class="lazy"></a></figure></li>
					</ul>

				</div>

			</div>
		</div>
	</header>
</template>

<script>
import Typewriter from 'typewriter-effect/dist/core';

export default {
	data() {
		return {
			isDark: false
		}
	},
	mounted() {
		// Check for saved theme preference
		const savedTheme = localStorage.getItem('theme');
		if (savedTheme) {
			this.isDark = savedTheme === 'dark';
			document.documentElement.setAttribute('data-theme', savedTheme);
		} else {
			// Check system preference
			this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
			document.documentElement.setAttribute('data-theme', this.isDark ? 'dark' : 'light');
		}

		new Typewriter('.typewrite', {
			strings: ['front-end developer.', 'back-end developer.'],
			autoStart: true,
			loop: true,
			pauseFor: 1500
		});

		const typing = document.querySelectorAll('.typewriter');

		function type(element) {

			function randomOpacity() {
				return (Math.floor(Math.random() * 50) + 50) / 100;
			}

			function randomEms() {
				if (Math.random() > .8) {
					return (Math.floor(Math.random() * 100) - 50) / 800;
				} else {
					return 0;
				}
			}

			function wrap(char) {
				return '<span style="opacity:' + randomOpacity() + '; text-shadow:' + randomEms() + 'em ' + randomEms() + 'em currentColor;">' + char + '</span>';
			}

			const wrappedText = Array.from(element.textContent).map(wrap);

			element.innerHTML = wrappedText.join('');

        }

        typing.forEach(type);
	}
	,
	methods: {
		toggleTheme() {
			this.isDark = !this.isDark;
			const theme = this.isDark ? 'dark' : 'light';
			document.documentElement.setAttribute('data-theme', theme);
			localStorage.setItem('theme', theme);
		}
	}
}
</script>

<style>
:root[data-theme="light"] {
	--bg-color: #ffffff;
	--text-color: #333333;
	--header-bg: #ffffff;
	--header-text: #333333;
	--border-color: #333333;
	--pattern-invert: 1;
	--toggle-bg: #2d2d2d;
	--toggle-shadow: rgba(0, 0, 0, 0.2);
	--toggle-hover-shadow: rgba(0, 0, 0, 0.3);
}

:root[data-theme="dark"] {
	--bg-color: #1a1a1a;
	--text-color: #ffffff;
	--header-bg: #2d2d2d;
	--header-text: #ffffff;
	--border-color: #ffffff;
	--pattern-invert: 0;
	--toggle-bg: #e4e4e4;
	--toggle-shadow: rgba(255, 255, 255, 0.1);
	--toggle-hover-shadow: rgba(255, 255, 255, 0.2);
}

body {
	background-color: var(--bg-color);
	color: var(--text-color);
	transition: background-color 0.3s, color 0.3s;
}
</style>

<style scoped>
header {
	position: relative;
	min-height: 100vh;
	overflow-x: hidden;
}

.background-pattern {
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAIAAACRXR/mAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAABnSURBVHja7M5RDYAwDEXRDgmvEocnlrQS2SwUFST9uEfBGWs9c97nbGtDcquqiKhOImLs/UpuzVzWEi1atGjRokWLFi1atGjRokWLFi1atGjRokWLFi1af7Ukz8xWp8z8AAAA//8DAJ4LoEAAlL1nAAAAAElFTkSuQmCC") repeat 0 0;
	filter: invert(var(--pattern-invert));
	z-index: 0;
}

.header {
	min-height: 100vh;
	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;
	position: relative;
}

.wrapper {
	position: relative;
	z-index: 1;
}

.arrow_btn {
	position: absolute;
	bottom: 3em;
	left: 0;
	right: 0;
	margin: auto;
}

.arrow_btn a {
	position: relative;
}

.typewriter {
	font-family: monospace;
	line-height: 4.5;
	font-size: 16px;
	color: var(--header-text);
	font-weight: 900;
	padding: 20px 0;
	border-radius: 0;
}

.typewriter_wrap {
	border-right: 0.2em solid var(--header-text);
}

.header_con {
	display: flex;
	flex-flow: column wrap;
	background: var(--header-bg);
	max-width: 600px;
	margin: 100px auto 70px;
	padding: 30px;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
	position: relative;
	transition: background-color 0.3s, color 0.3s;
}

.header_con picture {
	position: relative;
	top: -70px;
	width: 140px;
	height: 140px;
	margin: 0 0 -70px 0;
}

.header_con picture img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.header_con h1 {
	font: bold 28px/100% monospace;
	color: var(--header-text);
	text-align: left;
	padding: 30px 0 15px;
}

.header_con h2 {
	font: normal 16px monospace;
	color: var(--header-text);
	text-align: left;
	padding-bottom: 30px;
}

.header_con p {
	font: normal 16px/26px monospace;
	color: var(--header-text);
	text-align: left;
	border-bottom: 1px solid var(--border-color);
	padding-bottom: 30px;
}

.header_con p strong {
	font-weight: bold;
}

.logo {
	display: block;
	margin: 30px 0 0;
}

.logo li {
	display: inline-block;
	height: auto;
	margin: 0 10px;
}

.logo li img {
	width: 100%;
	max-width: 42px;
	height: 100%;
	background: none;
	border: 1px solid var(--border-color);
	border-radius: 50%;
	padding: 0;
}

.logo li:hover img {
	transform: scale(.8);
	cursor: pointer;
}

.waves {
	position: absolute;
	width: 100%;
	height: 15vh;
	bottom: 0;
	min-height: 100px;
	max-height: 150px;
}

.theme-toggle {
	position: absolute;
	top: 20px;
	right: 20px;
	background: none;
	border: none;
	cursor: pointer;
	padding: 0;
	width: 40px;
	height: 40px;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	z-index: 10;
}

.theme-toggle:hover {
	transform: scale(1.1) rotate(5deg);
}

.theme-toggle:active {
	transform: scale(0.95);
}

.toggle-track {
	width: 40px;
	height: 40px;
	background: var(--toggle-bg);
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	box-shadow: 0 2px 5px var(--toggle-shadow);
	position: relative;
	overflow: hidden;
}

.toggle-track::before {
	content: '';
	position: absolute;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background: radial-gradient(circle at center, rgba(255,255,255,0.1) 0%, transparent 70%);
	opacity: 0;
	transition: opacity 0.3s ease;
}

.toggle-track:hover::before {
	opacity: 1;
}

.toggle-icon {
	font-size: 24px;
	line-height: 1;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
	transform-origin: center;
}

.theme-toggle:hover .toggle-icon {
	transform: scale(1.1);
}

@media only screen and (max-width : 768px) {
    .header_con {
        margin: 70px 20px;
    }

    .waves {
        height: 40px;
        min-height: 40px;
    }
}

@media only screen and (max-height : 800px) {
    .header_con {
        margin: 140px 20px 100px;
    }
}
</style>
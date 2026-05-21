<template>
    <header id="home">
		<div class="header">
			<div class="background-pattern"></div>
			<div class="wrapper">

				<div class="hero-stack">

					<div class="header_con">

						<button class="theme-toggle" @click="toggleTheme" :title="isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'">
						<div class="toggle-track">
							<div class="toggle-slider" :class="{ 'is-dark': isDark }">
								<span class="toggle-icon">{{ isDark ? '☀️' : '🌙' }}</span>
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

					<div class="social-row">
						<ul class="logo">
							<li>
								<figure>
									<a href="https://github.com/jamesxdaniel" target="_blank" rel="noopener" aria-label="GitHub">
										<svg class="social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
										</svg>
									</a>
								</figure>
							</li>
							<li>
								<figure>
									<a href="https://www.linkedin.com/in/james-daniel-enovero/" target="_blank" rel="noopener" aria-label="LinkedIn">
										<svg class="social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/>
											<rect x="2" y="9" width="4" height="12"/>
											<circle cx="4" cy="4" r="2"/>
										</svg>
									</a>
								</figure>
							</li>
							<li>
								<figure>
									<a href="https://www.instagram.com/jaamesdaaniel" target="_blank" rel="noopener" aria-label="Instagram">
										<svg class="social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
											<path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/>
											<line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
										</svg>
									</a>
								</figure>
							</li>
							<li>
								<figure>
									<a href="mailto:jamesdaniel288@gmail.com" aria-label="Email">
										<svg class="social-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
											<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
											<polyline points="22,6 12,13 2,6"/>
										</svg>
									</a>
								</figure>
							</li>
						</ul>

						<NuxtLink to="/blog/" class="blog-link">Read my Blog →</NuxtLink>
					</div>

					</div>

					<NuxtLink to="/apps/" class="apps-sticky-cta">View my Apps →</NuxtLink>

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
		const theme = document.documentElement.getAttribute('data-theme') || 'light';
		this.isDark = theme === 'dark';

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
	padding: 0 20px;
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

.hero-stack {
	max-width: 600px;
	margin: 100px auto 70px;
}

.header_con {
	display: flex;
	flex-flow: column wrap;
	background: var(--header-bg);
	padding: 30px;
	position: relative;
	transition: background-color 0.3s, color 0.3s;
	border-top-left-radius: 10px;
	border-top-right-radius: 10px;
	box-shadow: none;
	margin: 0;
}

.header_con picture {
	position: relative;
	top: -70px;
	width: 140px;
	height: 140px;
	margin: 0 0 -70px 0;
	border-radius: 10px;
	overflow: hidden;
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
}

.header_con p strong {
	font-weight: bold;
}

.social-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin-top: 30px;
	gap: 20px;
	flex-wrap: wrap;
}

.blog-link {
	display: inline-block;
	font: bold 14px monospace;
	color: var(--toggle-slider-bg);
	background: var(--toggle-bg);
	padding: 12px 24px;
	border-radius: 8px;
	text-decoration: none;
	transition: transform 0.3s, box-shadow 0.3s;
	white-space: nowrap;
}

.blog-link:hover {
	transform: translateY(-2px);
	box-shadow: 0 4px 12px var(--toggle-shadow);
}

.apps-sticky-cta {
	display: block;
	font: bold 14px monospace;
	color: var(--toggle-slider-bg);
	background: var(--toggle-bg);
	padding: 16px 24px;
	text-align: center;
	text-decoration: none;
	transition: transform 0.3s, box-shadow 0.3s;
	border-top: 1px solid var(--border-color);
	border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
	overflow: hidden;
}

.apps-sticky-cta:hover {
	transform: translateY(-2px);
}

.logo {
	display: flex;
	text-align: left;
	margin: 0;
	padding: 0;
	list-style: none;
	gap: 15px;
}

.logo li {
	display: inline-block;
	height: auto;
	margin: 0;
}

.logo li a {
	display: flex;
	align-items: center;
	justify-content: center;
	color: var(--header-text);
	text-decoration: none;
	border-radius: 8px;
	transition: transform 0.3s, background-color 0.3s, color 0.3s, box-shadow 0.3s;
}

.logo li a:hover {
	transform: translateY(-2px);
	background: var(--surface-hover);
	color: var(--toggle-bg);
	box-shadow: 0 4px 12px var(--toggle-shadow);
}

.social-icon {
	width: 24px;
	height: 24px;
	padding: 8px;
	box-sizing: content-box;
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
	width: 80px;
	height: 40px;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	z-index: 10;
}

.theme-toggle:hover {
	transform: scale(1.05);
}

.theme-toggle:active {
	transform: scale(0.95);
}

.toggle-track {
	width: 100%;
	height: 100%;
	background: var(--toggle-bg);
	border-radius: 20px;
	display: flex;
	align-items: center;
	justify-content: flex-start;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	box-shadow: 0 2px 5px var(--toggle-shadow);
	position: relative;
	overflow: hidden;
	padding: 3px;
}

.toggle-slider {
	width: 34px;
	height: 34px;
	background: var(--toggle-slider-bg);
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	transform: translateX(0);
	box-shadow: 0 2px 4px var(--toggle-shadow);
}

.toggle-slider.is-dark {
	transform: translateX(40px);
}

.toggle-icon {
	font-size: 20px;
	line-height: 1;
	transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
	display: flex;
	align-items: center;
	justify-content: center;
	width: 100%;
	height: 100%;
}

@media only screen and (max-width : 768px) {
    .hero-stack {
        margin: 70px auto;
    }

    .header_con {
        margin: 0;
    }

    .waves {
        height: 40px;
        min-height: 40px;
    }

    .social-row {
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }

    .logo {
        justify-content: center;
    }

    .blog-link {
        width: 100%;
        text-align: center;
    }
}

@media only screen and (max-height : 800px) {
    .hero-stack {
        margin: 140px 20px 100px;
    }
}
</style>
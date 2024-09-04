<template>
    <header id="home">
		<div class="header">

			<div class="wrapper">

				<div class="header_con">

					<picture class="me">
						<source srcset="/images/me.webp" type="image/webp">
						<img data-src="/images/me.jpg" src="/images/me.jpg" alt="James Daniel Enovero" class="lazy">
					</picture>

					<h1 class="typewriter">James Daniel Enovero</h1>

					<h2>I'm a <span class="typewrite" data-period="3000" data-type='["front-end developer.","back-end developer."]'></span></h2>

					<p>Hi there! I'm a web developer with over 6 years of experience in <strong>HTML</strong>, <strong>CSS</strong>, <strong>JavaScript</strong>, and <strong>PHP</strong>. I also have expertise in <strong>Vue.js</strong> for developing web and mobile applications. I'm continuously learning and adapting to the latest industry trends.</p>

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
import '~/assets/animation.css';
import Typewriter from 'typewriter-effect/dist/core';

export default {
	mounted() {
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
				}
				else {
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
}
</script>

<style scoped>
header {
	position: relative;
	min-height: 100vh;
	overflow-x: hidden;
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
	color: black;
	font-weight: 900;
	padding: 20px 0;
	border-radius: 0;
}

.typewriter_wrap {
	border-right: 0.2em solid white;
}

.header {
	min-height: 100vh;
	display: flex;
	justify-content: center;
	align-items: center;
	text-align: center;
}

.header_con {
	display: flex;
	flex-flow: column wrap;
	background: #fff;
	max-width: 600px;
	margin: 70px auto 0;
	padding: 0 30px;
	box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
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
	color: black;
	text-align: left;
	padding: 30px 0;
}

.header_con h2 {
	font: normal 16px monospace;
	color: black;
	text-align: left;
	padding-bottom: 30px;
}

.header_con p {
	font: normal 16px/26px monospace;
	color: black;
	text-align: left;
	border-bottom: 1px solid gainsboro;
	padding-bottom: 30px;
}

.header_con p strong {
	font-weight: bold;
}

.header_con picture:hover {
	transform: scale(.9);
}

.logo {
	display: block;
	margin: 30px 0;
}

.logo li {
	display: inline-block;
	height: auto;
	margin: 0 10px;
}

.logo li img {
	width: 32px;
	color: transparent;
}

.logo li:hover img {
	filter: invert(100%);
	transform: scale(.8);
	cursor: pointer;
}

.header .logo li img {
	width: 100%;
	max-width: 32px;
	height: 100%;
	background: none;
	padding: 0;
}

.waves {
	position: absolute;
	width: 100%;
	height: 15vh;
	bottom: 0;
	min-height: 100px;
	max-height: 150px;
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
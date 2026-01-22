export default defineNuxtConfig({
	ssr: true,
	modules: ['@nuxt/content'],
	content: {
		// Explicitly define content sources
		sources: {
			content: {
				driver: 'fs',
				base: './content'
			}
		}
	},
	css: ['~/assets/styles.css'],
	devServer: {
		port: 6767,
		host: '0.0.0.0'
	},
	router: {
		options: {
			strict: false
		}
	},
	routeRules: {
		'/blog/**': { trailingSlash: true }
	},
	app: {
		head: {
			title: 'James Daniel Enovero - Web Developer',
			htmlAttrs: {
				lang: 'en'
			},
			meta: [
				{ charset: 'utf-8' },
				{ name: 'viewport', content: 'width=device-width, initial-scale=1' },
				{ name: 'description', content: 'Professional web developer specializing in modern web technologies. View my portfolio and projects.' },
				{ name: 'keywords', content: 'web developer, portfolio, frontend developer, full stack developer, JavaScript, Vue.js, Nuxt.js' },
				{ name: 'author', content: 'James Daniel Enovero' },
				{ property: 'og:title', content: 'James Daniel Enovero - Web Developer' },
				{ property: 'og:description', content: 'Professional web developer specializing in modern web technologies. View my portfolio and projects.' },
				{ property: 'og:type', content: 'website' },
				{ property: 'og:url', content: 'https://jamesxdaniel.github.io' },
				{ property: 'og:image', content: 'https://jamesxdaniel.github.io/og-image.jpg' },
				{ name: 'twitter:card', content: 'summary_large_image' },
				{ name: 'twitter:title', content: 'James Daniel Enovero - Web Developer' },
				{ name: 'twitter:description', content: 'Professional web developer specializing in modern web technologies. View my portfolio and projects.' },
				{ name: 'twitter:image', content: 'https://jamesxdaniel.github.io/og-image.jpg' },
				{ name: 'robots', content: 'index, follow' },
				{ name: 'theme-color', content: '#ffffff' }
			],
			link: [
				{
					rel: 'icon',
					type: 'image/x-icon',
					href: '/favicon.ico'
				},
				{
					rel: 'canonical',
					href: 'https://jamesxdaniel.github.io'
				},
				{
					rel: 'sitemap',
					type: 'application/xml',
					href: '/sitemap.xml'
				}
			],
			script: [
				{
					src: 'https://smtpjs.com/v3/smtp.js',
					defer: true
				}
			]
		}
	},
	compatibilityDate: '2024-07-19'
})
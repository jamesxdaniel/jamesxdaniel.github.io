export default defineNuxtConfig({
	ssr: true,
  	css: ['~/assets/style.css'],
    app: {
      head: {
			title: 'James Daniel Enovero - Web Developer',
            htmlAttrs: {
                lang: 'en'
            },
            link: [
                {
                    rel: 'icon',
                    type: 'image/x-icon',
                    href: '/favicon.ico'
                }
            ],
            script: [
                {
                    src: 'https://smtpjs.com/v3/smtp.js',
                    defer: true
                }
            ]
        }
    }
})
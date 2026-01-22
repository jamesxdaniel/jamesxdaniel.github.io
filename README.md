# James Daniel Enovero - Portfolio & Blog

Personal portfolio website with an automated AI-powered blog, built with Nuxt 3 and deployed on GitHub Pages.

## Features

- Personal portfolio page
- Blog with markdown content support
- Automated weekly blog posts using Gemini AI (free tier compatible)
- Dark/light theme toggle
- Responsive design

## Automated Blog Posts

This project includes a GitHub Actions workflow that automatically generates blog posts using Google's Gemini 1.5 Flash AI.

**Note:** This is configured for **weekly posts** (every Monday) to work within the free tier limits. The free tier of Gemini API has strict rate limits (~15 requests/minute, ~1,500 requests/day). Weekly posts ensure reliable automation without exceeding quotas.

### Setting Up the Gemini API Key

1. Go to [Google AI Studio](https://aistudio.google.com/app/apikey) and create an API key
2. In your GitHub repository, go to **Settings** > **Secrets and variables** > **Actions**
3. Click **New repository secret**
4. Name: `GEMINI_API_KEY`
5. Value: Your Google AI API key
6. Click **Add secret**

### How It Works

- The workflow runs automatically at 6:00 AM UTC every Monday (configurable in `.github/workflows/daily-blog-post.yml`)
- It generates a tech-focused blog post using Gemini 1.5 Flash
- The script includes automatic retry logic for rate limit errors
- The post is committed to the `content/blog/` directory
- This triggers the normal Nuxt build and deployment to GitHub Pages
- You can also manually trigger it from the Actions tab

### Manual Generation

To generate a blog post locally:

```bash
GEMINI_API_KEY=your_api_key node scripts/generate-post.mjs
```

---

Look at the [Nuxt 3 documentation](https://nuxt.com/docs/getting-started/introduction) to learn more.

## Setup

Make sure to install the dependencies:

```bash
# npm
npm install

# pnpm
pnpm install

# yarn
yarn install

# bun
bun install
```

## Development Server

Start the development server on `http://localhost:3000`:

```bash
# npm
npm run dev

# pnpm
pnpm run dev

# yarn
yarn dev

# bun
bun run dev
```

## Production

Build the application for production:

```bash
# npm
npm run build

# pnpm
pnpm run build

# yarn
yarn build

# bun
bun run build
```

Locally preview production build:

```bash
# npm
npm run preview

# pnpm
pnpm run preview

# yarn
yarn preview

# bun
bun run preview
```

Check out the [deployment documentation](https://nuxt.com/docs/getting-started/deployment) for more information.

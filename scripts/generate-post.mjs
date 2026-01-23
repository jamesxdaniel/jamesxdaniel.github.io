import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const GEMINI_API_KEY = process.env.GEMINI_API_KEY;
// Using gemini-2.0-flash - only model confirmed to work with this API key
// Other models return 404, suggesting they're not available with current key configuration
const GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

// Tech and Gaming topics to rotate through
const TOPICS = [
	// Web Development
	'JavaScript tips and tricks for modern web development',
	'Vue.js best practices and patterns',
	'CSS tricks and techniques for beautiful UIs',
	'TypeScript tips for better code quality',
	'Node.js performance optimization',
	'Web accessibility best practices',
	'Frontend testing strategies',
	'React vs Vue: choosing the right framework',
	'Progressive Web Apps development guide',
	'Web performance optimization techniques',
	
	// Programming & DevOps
	'Git workflow tips for developers',
	'API design best practices',
	'Web security fundamentals',
	'Developer productivity tools and tips',
	'Code refactoring strategies',
	'Database optimization techniques',
	'Debugging tips for web developers',
	'Docker containerization for beginners',
	'CI/CD pipeline best practices',
	'Microservices architecture patterns',
	
	// Gaming Industry & Culture
	'The rise of indie game development',
	'Esports industry trends and opportunities',
	'Game streaming and content creation tips',
	'Monetization strategies for mobile games',
	'The future of cloud gaming platforms',
	'Virtual Reality gaming development',
	'Game localization and internationalization',
	'Building gaming communities and engagement',
	'Retro gaming and game preservation',
	'Cross-platform gaming development challenges'
];

function getRandomTopic() {
	// Use date-based seeding for some variety while being reproducible
	const today = new Date();
	const dayOfYear = Math.floor((today - new Date(today.getFullYear(), 0, 0)) / (1000 * 60 * 60 * 24));
	const index = dayOfYear % TOPICS.length;
	return TOPICS[index];
}

function generateSlug(title) {
	return title
		.toLowerCase()
		.replace(/[^a-z0-9\s-]/g, '')
		.replace(/\s+/g, '-')
		.replace(/-+/g, '-')
		.substring(0, 60)
		.replace(/-$/, '');
}

function getTodayDate() {
	const today = new Date();
	const year = today.getFullYear();
	const month = String(today.getMonth() + 1).padStart(2, '0');
	const day = String(today.getDate()).padStart(2, '0');
	return `${year}-${month}-${day}`;
}

async function generateBlogPost() {
	if (!GEMINI_API_KEY) {
		console.error('Error: GEMINI_API_KEY environment variable is not set');
		process.exit(1);
	}

	const topic = getRandomTopic();
	const today = getTodayDate();

	console.log(`Generating blog post about: ${topic}`);

	// Shorter prompt to reduce token usage
	const prompt = `Write a technical blog post about "${topic}" for a web developer's blog.

Requirements:
- Educational and practical (800-1000 words)
- Include code examples with markdown code blocks
- Use ## and ### headers
- Clear intro and conclusion

Format with frontmatter:
---
title: "Title"
description: "Brief description"
date: ${today}
tags: ["tag1", "tag2"]
---

[Content in markdown]`;

	// Retry logic for rate limits
	const maxRetries = 3;
	let retryCount = 0;
	let retryDelay = 1000; // Start with 1 second

	try {
		while (retryCount <= maxRetries) {
			try {
				const response = await fetch(`${GEMINI_API_URL}?key=${GEMINI_API_KEY}`, {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify({
						contents: [{
							parts: [{
								text: prompt
							}]
						}],
						generationConfig: {
							temperature: 0.7,
							topK: 40,
							topP: 0.95,
							maxOutputTokens: 1500, // Reduced to save tokens
						}
					})
				});

				if (!response.ok) {
					const errorText = await response.text();
					let errorData;
					try {
						errorData = JSON.parse(errorText);
					} catch (e) {
						throw new Error(`API request failed: ${response.status} - ${errorText}`);
					}

					// Handle rate limit errors (429)
					if (response.status === 429 && retryCount < maxRetries) {
						// Extract retry delay from error if available
						let delay = retryDelay;
						if (errorData.error?.details) {
							const retryInfo = errorData.error.details.find(d => d['@type'] === 'type.googleapis.com/google.rpc.RetryInfo');
							if (retryInfo?.retryDelay) {
								delay = Math.max(parseInt(retryInfo.retryDelay.replace('s', '')) * 1000, delay);
							}
						}

						console.log(`Rate limit hit. Retrying in ${delay / 1000} seconds... (attempt ${retryCount + 1}/${maxRetries})`);
						await new Promise(resolve => setTimeout(resolve, delay));
						retryCount++;
						retryDelay *= 2; // Exponential backoff
						continue;
					}

					throw new Error(`API request failed: ${response.status} - ${errorText}`);
				}

				const data = await response.json();

				if (!data.candidates || !data.candidates[0] || !data.candidates[0].content) {
					throw new Error('Invalid response from Gemini API');
				}

				let content = data.candidates[0].content.parts[0].text;

				// Clean up any code block wrappers if present (markdown, yaml, or plain)
				content = content.replace(/^```(?:markdown|yaml|md)?\n?/, '').replace(/\n?```$/, '');
				content = content.trim();

				// Extract title from frontmatter for filename
				const titleMatch = content.match(/title:\s*["'](.+?)["']/);
				if (!titleMatch) {
					throw new Error('Could not extract title from generated content');
				}

				const title = titleMatch[1];
				const slug = generateSlug(title);
				const filename = `${today}-${slug}.md`;

				// Ensure content directory exists
				const contentDir = path.join(__dirname, '..', 'content', 'blog');
				if (!fs.existsSync(contentDir)) {
					fs.mkdirSync(contentDir, { recursive: true });
				}

				// Write the blog post
				const filepath = path.join(contentDir, filename);
				fs.writeFileSync(filepath, content, 'utf-8');

				console.log(`Successfully generated blog post: ${filename}`);
				console.log(`Title: ${title}`);
				console.log(`Path: ${filepath}`);

				return { success: true, filename, title };

			} catch (error) {
				if (retryCount < maxRetries && (error.message.includes('429') || error.message.includes('RESOURCE_EXHAUSTED'))) {
					retryCount++;
					continue;
				}
				throw error;
			}
		}

		// If we exhausted all retries
		throw new Error('Max retries exceeded. Rate limit still in effect.');

	} catch (error) {
		console.error('Error generating blog post:', error.message);
		if (error.message.includes('429') || error.message.includes('RESOURCE_EXHAUSTED') || error.message.includes('quota')) {
			console.error('\n⚠️  Rate limit/quota exceeded. The free tier has strict limits.');
			console.error('Options:');
			console.error('1. Wait and try again later (free tier resets daily)');
			console.error('2. Upgrade to a paid Gemini API plan for daily automated posts');
			console.error('3. Reduce the frequency of posts (e.g., weekly instead of daily)');
		}
		process.exit(1);
	}
}

// Run the generator
generateBlogPost();

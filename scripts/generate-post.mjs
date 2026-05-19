import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const GEMINI_API_KEY = process.env.GEMINI_API_KEY;
const GEMINI_BASE_URL = 'https://generativelanguage.googleapis.com/v1beta/models';

// Models to try in order — first available free-tier model wins
const MODELS = [
	'gemini-2.0-flash-lite',
	'gemini-1.5-flash',
	'gemini-1.5-flash-8b',
	'gemini-2.0-flash',
];

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

async function tryModel(model, prompt) {
	const url = `${GEMINI_BASE_URL}/${model}:generateContent?key=${GEMINI_API_KEY}`;
	const maxRetries = 2;
	let retryCount = 0;
	let retryDelay = 1000;

	while (retryCount <= maxRetries) {
		const response = await fetch(url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({
				contents: [{ parts: [{ text: prompt }] }],
				generationConfig: {
					temperature: 0.7,
					topK: 40,
					topP: 0.95,
					maxOutputTokens: 1500,
				}
			})
		});

		if (!response.ok) {
			const errorText = await response.text();
			let errorData;
			try { errorData = JSON.parse(errorText); } catch (e) { errorData = {}; }

			const isQuotaExhausted = response.status === 429 && (
				errorData.error?.message?.includes('limit: 0') ||
				errorData.error?.details?.some(d =>
					d['@type'] === 'type.googleapis.com/google.rpc.QuotaFailure' &&
					d.violations?.some(v => v.quotaMetric?.includes('free_tier'))
				)
			);

			// If quota is completely exhausted (limit: 0), skip to next model
			if (isQuotaExhausted) {
				throw new Error(`QUOTA_EXHAUSTED:${model}`);
			}

			// Temporary rate limit — retry with backoff
			if (response.status === 429 && retryCount < maxRetries) {
				let delay = retryDelay;
				if (errorData.error?.details) {
					const retryInfo = errorData.error.details.find(d => d['@type'] === 'type.googleapis.com/google.rpc.RetryInfo');
					if (retryInfo?.retryDelay) {
						delay = Math.max(parseInt(retryInfo.retryDelay.replace('s', '')) * 1000, delay);
					}
				}
				console.log(`[${model}] Rate limit hit. Retrying in ${delay / 1000}s... (attempt ${retryCount + 1}/${maxRetries})`);
				await new Promise(resolve => setTimeout(resolve, delay));
				retryCount++;
				retryDelay *= 2;
				continue;
			}

			// Model not found or other error — skip to next model
			if (response.status === 404) {
				throw new Error(`MODEL_NOT_FOUND:${model}`);
			}

			throw new Error(`API request failed: ${response.status} - ${errorText}`);
		}

		const data = await response.json();
		if (!data.candidates?.[0]?.content) {
			throw new Error('Invalid response from Gemini API');
		}

		return data.candidates[0].content.parts[0].text;
	}

	throw new Error(`Max retries exceeded for model: ${model}`);
}

async function generateBlogPost() {
	if (!GEMINI_API_KEY) {
		console.error('Error: GEMINI_API_KEY environment variable is not set');
		process.exit(1);
	}

	const topic = getRandomTopic();
	const today = getTodayDate();

	console.log(`Generating blog post about: ${topic}`);

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

	let content = null;
	let usedModel = null;

	for (const model of MODELS) {
		try {
			console.log(`Trying model: ${model}`);
			content = await tryModel(model, prompt);
			usedModel = model;
			break;
		} catch (error) {
			if (error.message.startsWith('QUOTA_EXHAUSTED:') || error.message.startsWith('MODEL_NOT_FOUND:')) {
				console.log(`Skipping ${model}: ${error.message.split(':')[0].toLowerCase().replace('_', ' ')}`);
				continue;
			}
			throw error;
		}
	}

	if (!content) {
		console.error('\n⚠️  All models exhausted. Free tier quota is depleted across all available models.');
		console.error('Options:');
		console.error('1. Wait until tomorrow (free tier resets daily)');
		console.error('2. Upgrade to a paid Gemini API plan');
		process.exit(1);
	}

	// Clean up any code block wrappers if present
	content = content.replace(/^```(?:markdown|yaml|md)?\n?/, '').replace(/\n?```$/, '').trim();

	const titleMatch = content.match(/title:\s*["'](.+?)["']/);
	if (!titleMatch) {
		throw new Error('Could not extract title from generated content');
	}

	const title = titleMatch[1];
	const slug = generateSlug(title);
	const filename = `${today}-${slug}.md`;

	const contentDir = path.join(__dirname, '..', 'content', 'blog');
	if (!fs.existsSync(contentDir)) {
		fs.mkdirSync(contentDir, { recursive: true });
	}

	const filepath = path.join(contentDir, filename);
	fs.writeFileSync(filepath, content, 'utf-8');

	console.log(`Successfully generated blog post: ${filename}`);
	console.log(`Title: ${title}`);
	console.log(`Model used: ${usedModel}`);
	console.log(`Path: ${filepath}`);

	return { success: true, filename, title };
}

// Run the generator
generateBlogPost();

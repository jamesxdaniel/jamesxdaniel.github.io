---
title: "Getting Started with Vue 3 Composition API"
description: "Learn how to build better Vue applications using the Composition API with practical examples and best practices."
date: 2026-01-23
tags: ["vue", "javascript", "tutorial"]
---

## Introduction

The Vue 3 Composition API is a powerful way to organize and reuse logic in your Vue applications. Unlike the Options API, the Composition API allows you to group related code together, making it easier to maintain and understand.

## Why Use the Composition API?

The Composition API offers several advantages:

- **Better code organization** - Group related logic together
- **Improved TypeScript support** - Better type inference
- **Code reusability** - Easy to extract and share logic
- **Smaller bundle size** - Tree-shakeable

## Basic Example

Here's a simple example of using the Composition API:

```vue
<script setup>
import { ref, computed } from 'vue'

const count = ref(0)
const doubled = computed(() => count.value * 2)

function increment() {
  count.value++
}
</script>

<template>
  <div>
    <p>Count: {{ count }}</p>
    <p>Doubled: {{ doubled }}</p>
    <button @click="increment">Increment</button>
  </div>
</template>
```

## Reactive State with `ref` and `reactive`

Vue 3 provides two main ways to create reactive state:

### Using `ref`

```javascript
import { ref } from 'vue'

const message = ref('Hello World')
console.log(message.value) // Access with .value
```

### Using `reactive`

```javascript
import { reactive } from 'vue'

const state = reactive({
  count: 0,
  message: 'Hello'
})
console.log(state.count) // No .value needed
```

## Computed Properties

Computed properties are cached based on their dependencies:

```javascript
import { ref, computed } from 'vue'

const price = ref(100)
const quantity = ref(2)

const total = computed(() => price.value * quantity.value)
```

## Lifecycle Hooks

The Composition API provides lifecycle hooks that you can import:

```javascript
import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
  console.log('Component mounted!')
})

onUnmounted(() => {
  console.log('Component unmounted!')
})
```

## Composables - Reusable Logic

Create reusable logic with composables:

```javascript
// useCounter.js
import { ref } from 'vue'

export function useCounter(initialValue = 0) {
  const count = ref(initialValue)
  
  function increment() {
    count.value++
  }
  
  function decrement() {
    count.value--
  }
  
  return { count, increment, decrement }
}
```

Use it in your component:

```vue
<script setup>
import { useCounter } from './composables/useCounter'

const { count, increment, decrement } = useCounter(10)
</script>
```

## Best Practices

1. **Use `<script setup>`** - It's more concise and has better performance
2. **Group related logic** - Keep related code together
3. **Extract reusable logic** - Create composables for shared functionality
4. **Use TypeScript** - Get better type safety and autocomplete
5. **Keep composables focused** - Each composable should do one thing well

## Conclusion

The Vue 3 Composition API provides a flexible and powerful way to build Vue applications. By organizing code logically and creating reusable composables, you can write more maintainable and scalable applications.

Start small by converting one component at a time, and soon you'll appreciate the benefits of this modern approach to Vue development!

## Resources

- [Vue 3 Official Documentation](https://vuejs.org/)
- [Composition API RFC](https://github.com/vuejs/rfcs/blob/master/active-rfcs/0013-composition-api.md)
- [VueUse - Collection of Composables](https://vueuse.org/)
